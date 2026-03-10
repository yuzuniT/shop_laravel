<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Livewire\Component;

class ResetPassword extends Component
{
    public string $token;
    public string $email='';
    public string $password='';
    public string $password_confirmation='';

    // マウント時にURLのクエリパラメータからemailを取得（あれば）
    public function mount(string $token)
    {
        $this->token=$token;
        $this->email=request()->query('email','');
    }

    public function resetPassword()
    {
        $this->validate([
            'token'=>'required',
            'email'=>'required|email',
            'password'=>'required|min:8|confirmed',
        ]);

        // Laravel標準のパスワードリセット処理を実行
        $status=Password::reset(
            [
                'token'=>$this->token,
                'email'=>$this->email,
                'password'=>$this->password,
                'password_confirmation'=>$this->password_confirmation,
            ],
            function (User $user,string $password) {
                // ここで実際にパスワードを更新する処理
                $user->forceFill([
                    'password'=>Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                // パスワードリセットイベントを発行
                event(new PasswordReset($user));
            }

        );

        if ($status===Password::PASSWORD_RESET) {
            session()->flash('success','パスワードを再設定しました。');
            return redirect()->route('login');
        }

        // 失敗時（トークン切れなど）
        $this->addError('email',__($status));
    }



    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
