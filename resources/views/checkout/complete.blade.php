@extends('layouts.common')

@section('title', 'ご注文完了')

@section('content_title', 'ご注文が完了しました')

@section('content')
<div class="max-wo-xl mx-auto py-12 text-center bg-white p-8 rounded-xl shadow-lg">

    {{-- 完了メッセージ --}}
    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-green-500 mx-auto mb-6" viewBox="0 0 20 20" fill="currentColor">
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
    </svg>

    <h1 class="text-3xl font-extrabold text-green-600 mb-4">
        ご注文ありがとうございます！
    </h1>

    <p class="text-gray-700 text-lg mb-8">
        お客様のご注文は正常に受け付けられました。
    </p>

    @if ($orderId)
        {{-- 注文IDの表示 --}}
        <div class="inline-block bg-gray-100 p-4 rounded-lg border border-gray-200 mb-8">
            <p class="text-sm text-gray-500 mb-1">ご注文番号</p>
            <p class="text-4xl font-mono font-bold text-gray-800">{{ $orderId }}</p>
        </div>

        <p class="text-gray-600 mb-10">
            ご注文内容の確認メールを、ご登録いただいたメールアドレス宛に送信しました。
        </p>
    @else
        {{-- orderIdがセッションに残っていない場合の代替メッセージ --}}
        <p class="text-red-500 mb-10">
            注文番号を取得できませんでしたが、ご注文は確定しています。
            メールをご確認ください。
        </p>
    @endif

    <hr class="my-6">

    <div class="flex justify-center space-y-4">
        <a href="{{ route('products.index') }}"
            class="block w-full lg:w-80 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
            お買い物を続ける
        </a>
    </div>

</div>
@endsection