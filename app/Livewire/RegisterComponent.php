<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class RegisterComponent extends Component
{
    public string $family_name='';
    public string $last_name='';
    public string $email='';
    public string $password='';
    public string $password_confirmation='';

    protected function rules(): array
    {
        return (new RegisterRequest)->rules();
    }

    protected function messages(): array
    {
        return (new RegisterRequest)->messages();
    }

    public function register()
    {
        $this->validate();

        $user = User::create([
            'family_name' => $this->family_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        auth()->login($user);

        return redirect()->route('products.index')
            ->with('success', '登録が完了しました。');
    }

    public function render()
    {
        return view('livewire.register-component');
    }
}
