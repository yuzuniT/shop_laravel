<?php

namespace App\Livewire;

use Livewire\Component;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginComponent extends Component
{
    public string $email='';
    public string $password='';

    protected function rules(): array
    {
        return (new LoginRequest)->rules();
    }

    protected function messages(): array
    {
        return (new LoginRequest)->messages();
    }

    public function login()
    {
        $this->validate();

        $email=$this->email;
        $password=$this->password;

        if(! Auth::attempt(['email'=>$email, 'password'=>$password]))
        {
            $this->addError('login', 'メールアドレスまたはパスワードが正しくありません。');
            return;
        }

        session()->regenerate();

        return redirect()->route('products.index')
            ->with('success','ログインに成功しました。');
        
    }

    public function render()
    {
        return view('livewire.login-component');
    }
}
