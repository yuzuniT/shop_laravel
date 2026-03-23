<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Validation\Rule;

class AdminUsers extends Component
{
    use WithPagination;

    public $search = '';
    public $showForm = false;
    public $editingId = null;

    public $form = [
        'family_name' => '',
        'last_name' => '',
        'email' => '',
        'role' => 'user',
        'password' => '',
        'is_deleted' => false,
    ];

    public $message = '';
    public $messageType = ''; // success | error

    protected function rules()
    {
        return [
            'form.family_name' => 'required|string|max:255',
            'form.last_name' => 'required|string|max:255',
            'form.email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->editingId),
            ],
            'form.role' => ['required', Rule::in(['admin', 'user'])],
            'form.password' => $this->editingId ? 'nullable|string|min:8' : 'required|string|min:8',
            'form.is_deleted' => 'boolean',
        ];
    }

    public function render()
    {
        $query = User::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('family_name', 'like', "%{$this->search}%")
                    ->orWhere('last_name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin-users', [
            'users' => $users,
        ]);
    }

    public function openForm($userId = null)
    {
        if ($userId) {
            $user = User::findOrFail($userId);
            $this->editingId = $userId;
            $this->form = [
                'family_name' => $user->family_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'role' => $user->role,
                'password' => '',
                'is_deleted' => $user->is_deleted,
            ];
        } else {
            $this->resetForm();
            $this->editingId = null;
        }

        $this->showForm = true;
        $this->message = '';  // フォーム開く時だけメッセージをリセット
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
        // メッセージはリセットしない（ユーザーが確認できるように）
    }

    public function resetForm()
    {
        $this->form = [
            'family_name' => '',
            'last_name' => '',
            'email' => '',
            'role' => 'user',
            'password' => '',
            'is_deleted' => false,
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editingId) {
                $user = User::findOrFail($this->editingId);

                // セルフ削除を防ぐ
                if ($user->id === auth()->id() && $this->form['is_deleted']) {
                    throw new \Exception('自身のアカウントを無効化することはできません。');
                }

                $user->family_name = $this->form['family_name'];
                $user->last_name = $this->form['last_name'];
                $user->email = $this->form['email'];
                $user->role = $this->form['role'];
                $user->is_deleted = $this->form['is_deleted'];

                if (!empty($this->form['password'])) {
                    $user->password = bcrypt($this->form['password']);
                }

                $user->save();

                $this->message = 'ユーザー情報を更新しました。';
            } else {
                User::create([
                    'family_name' => $this->form['family_name'],
                    'last_name' => $this->form['last_name'],
                    'email' => $this->form['email'],
                    'role' => $this->form['role'],
                    'password' => bcrypt($this->form['password']),
                    'is_deleted' => $this->form['is_deleted'],
                    'email_verified_at' => now(),
                ]);
                $this->message = 'ユーザーを作成しました。';
            }

            $this->messageType = 'success';
            $this->closeForm();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->message = 'エラーが発生しました: ' . $e->getMessage();
            $this->messageType = 'error';
        }
    }

    public function toggleDeleted($userId)
    {
        if ($userId === auth()->id()) {
            $this->message = '自分のアカウントの削除/復元はできません。';
            $this->messageType = 'error';
            return;
        }

        $user = User::findOrFail($userId);
        $user->is_deleted = !$user->is_deleted;
        $user->save();

        $this->message = $user->is_deleted ? 'ユーザーを無効にしました。' : 'ユーザーを有効化しました。';
        $this->messageType = 'success';
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedPage()
    {
        $this->showForm = false;
        $this->resetForm();
    }
}
