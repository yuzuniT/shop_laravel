@extends('layouts.common')

@section('title','会員登録')

@section('content')

<x-message-box class="max-w-md mx-auto">

@livewire('register-component')

</x-message-box>

@endsection
