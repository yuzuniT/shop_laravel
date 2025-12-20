@extends('layouts.common')

@section('title','お問い合わせ内容確認')

@section('content')
<div class="max-w-3xl mx-auto">

    <h1 class="text-2xl font-semibold mb-6">お問い合わせ内容の確認</h1>

    <p class="mb-6 text-gray-600">
        入力内容をご確認ください。よろしければ「送信する」ボタンを押してください。
    </p>

    <div class="bg-white border rounded-lg overflow-hidden shadow-sm mb-8">
        {{-- 入力内容のリスト --}}
        <dl class="divide-y divide-gray-200">
            {{-- お名前 --}}
            <div class="grid grid-cols-1 divide-x divide-gray-200 md:grid-cols-3 gap-4 px-6 py-4">
                <dt class="font-bold text-gray-700">お名前</dt>
                <dd class="md:col-span-2 text-gray-900">
                    {{ $contact_data['family_name']}} {{ $contact_data['last_name']}}
                </dd>
            </div>

            {{-- メールアドレス --}}
            <div class="grid grid-cols-1 divide-x divide-gray-200 md:grid-cols-3 gap-4 px-6 py-4">
                <dt class="font-bold text-gray-700">メールアドレス</dt>
                <dd class="md:col-span-2 text-gray-900">
                    {{ $contact_data['email']}}
                </dd>
            </div>

            {{-- 電話番号 --}}
            <div class="grid grid-cols-1 divide-x divide-gray-200 md:grid-cols-3 gap-4 px-6 py-4">
                <dt class="font-bold text-gray-700">電話番号</dt>
                <dd class="md:col-span-2 text-gray-900">
                    {{ $contact_data['phone_number']}}
                </dd>
            </div>

            {{-- お問い合わせの種類 --}}
            <div class="grid grid-cols-1 divide-x divide-gray-200 md:grid-cols-3 gap-4 px-6 py-4">
                <dt class="font-bold text-gray-700">お問い合わせの種類</dt>
                <dd class="md:col-span-2 text-gray-900">
                    {{
                        [
                            'product' => '商品について',
                            'order' => '注文・発送について',
                            'return' => '返品・交換',
                            'payment' => '支払いについて',
                            'other' => 'その他',
                        ][$contact_data['contact_type']] ?? '不明'
                    }}
                </dd>
            </div>

            {{-- 件名 --}}
            <div class="grid grid-cols-1 divide-x divide-gray-200 md:grid-cols-3 gap-4 px-6 py-4">
                <dt class="font-bold text-gray-700">件名</dt>
                <dd class="md:col-span-2 text-gray-900">
                    {{ $contact_data['contact_title']}}
                </dd>
            </div>

            {{-- お問い合わせ内容 --}}
            <div class="grid grid-cols-1 divide-x divide-gray-200 md:grid-cols-3 gap-4 px-6 py-4">
                <dt class="font-bold text-gray-700">お問い合わせ内容</dt>
                <dd class="md:col-span-2 text-gray-900 whitespace-pre-wrap">{{ $contact_data['message']}}</dd>
            </div>
        </dl>
    </div>

    {{-- 送信・戻るボタン --}}
    <form action="{{ route('contact.store') }}" method="POST">
        @csrf

        <div class="flex flex-col md:flex-row items-center justify-center gap-5">
            <button type="submit" name="back" value="true"
                class="w-full md:w-40 bg-gray-200 text-gray-700 font-bold py-3 rounded-md cursor-pointer hover:bg-gray-300 transition duration-200">
                修正する
            </button>

            <button type="submit"
                class="w-full md:w-64 bg-blue-600 text-white font-bold py-3 rounded-md cursor-pointer hover:bg-blue-700 shadow-md transition duration-200">
                この内容で送信する
            </button>
        </div>
    </form>
</div>
@endsection