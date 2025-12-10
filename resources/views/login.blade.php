<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public string $email='';
    public string $password='';

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
    ]);

    if (! Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
        $this->addError('email', 'メールアドレスまたはパスワードが正しくありません。');
        return;
    }

    session()->regenerate();

    return redirect()->intended()->route('products.index');

    }
}; ?>

@extends('layouts.common')

@section('title','ログイン')

@section('content')

<x-message-box class="max-w-md mx-auto">

@livewire('login-component')

</x-message-box>

@endsection
