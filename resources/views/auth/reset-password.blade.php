@extends('layouts.common')
@section('content')
    <x-message-box class="max-w-md mx-auto">
        @livewire('auth.reset-password',['token'=>$token])
    </x-message-box>
@endsection