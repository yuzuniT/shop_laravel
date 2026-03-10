<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;

class ForgotPassword extends Component
{
    public string $email='';
    public string $status='';

    protected function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
        ];
    }

    protected function messages() :array
    {
        return [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '正しいメールアドレスの形式で入力してください。',
            'email.exists' => 'このメールアドレスは登録されていません。', // 実務上はセキュリティ対策としてエラーメッセージを表示しないのもアリ
        ];
    }

    public function sendResetLink(){
        $this->validate();

        // Laravel標準機能を使ってリセットメールを送信
        $status=Password::sendResetLink(['email'=>$this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->status = 'パスワード再設定用のメールを送信しました。';
        } else {
            $this->addError('email','メールを送信できませんでした。再度お試しください。');
        }
    }
    
    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
