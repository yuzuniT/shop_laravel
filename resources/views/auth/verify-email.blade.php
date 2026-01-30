@extends('layouts.common')

@section('content')
<div class="max-w-md mx-auto my-12 p-8 bg-white rounded-xl shadow-lg border border-gray-100">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">メールアドレスの確認</h2>

    <p class="text-gray-600 mb-6 leading-relaxed">
        ご登録ありがとうございます！<br>
        ご入力いただいたメールアドレスに確認用リンクを送信しました。届いたメール内のリンクをクリックして、登録を完了させてください。
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg text-sm border-green-200">
            新しい認証メールを送信しました。
        </div>
    @endif

    <div class="flex flex-col gap-4">
        {{-- 再送ボタン --}}
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg cursor-pointer hover:bg-blue-700 transition">
                認証メールを再送信する
            </button>
        </form>

        {{-- ログアウト（もし間違ったメアドで登録した場合用） --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-gray-500 cursor-pointer hover:text-gray-700 text-sm underline">
                ログアウト
            </button>
        </form>
    </div>
</div>
@endsection
