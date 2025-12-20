@extends('layouts.common')

@section('title', 'お問い合わせ受付完了')

@section('content')
<div class="max-w-xl mx-auto py-12 text-center bg-white p-8 rounded-xl shadow-lg">

    {{-- 完了メッセージ --}}
    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-green-500 mx-auto mb-6" viewBox="0 0 20 20" fill="currentColor">
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
    </svg>

    <h1 class="text-3xl font-extrabold text-gray-800 mb-4">
        お問い合わせを受け付けました
    </h1>

    @if ($contactId)
        {{-- お問い合わせIDの表示 --}}
        <div class="inline-block bg-gray-100 p-4 rounded-lg border border-gray-200 mb-8">
            <p class="text-sm text-gray-500 mb-1">お問い合わせ番号</p>
            <p class="text-4xl font-mono font-bold text-gray-800">{{ $contactId }}</p>
        </div>

        <p class="text-gray-600 mb-10">
            お問い合わせ内容の確認メールを、ご入力いただいたメールアドレス宛に送信しました。
        </p>
    @else
        {{-- contactIdがセッションに残っていない場合の代替メッセージ --}}
        <p class="text-red-500 mb-10">
            お問い合わせ番号を取得できませんでしたが、お問い合わせは確定しています。<br>
            メールをご確認ください。
        </p>
    @endif

    <div class="text-center text-gray-600 mb-8 space-y-2">
        <p class="mt-6">内容を確認の上、3営業日以内に担当者よりご連絡を差し上げます。</p>
    </div>

    {{-- ボタン類 --}}
    <div class="flex justify-center space-y-4">
        <a href="{{ route('products.index') }}"
            class="block w-full lg:w-80 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
            お買い物を続ける
        </a>
    </div>
</div>
@endsection
