@extends('layouts.common')
@section('content')
    <x-message-box class="max-w-md mx-auto">
        @livewire('auth.forgot-password')
    </x-message-box>
@endsection