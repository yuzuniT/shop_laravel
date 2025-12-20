@extends('layouts.common')

@section('title', '注文内容の確認')

@section('content')

<div class="max-w-6xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-8">ご注文内容の確認</h1>

    {{-- 注文確定フォーム --}}
    <form method="POST" action="{{ route('checkout.store') }}">

        @csrf


        <div class="flex flex-col lg:flex-row gap-8">

            {{-- 左側：配送先情報とカート詳細 --}}
            <div class="flex-1 space-y-8">

                {{-- 配送先情報 --}}
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-semibold mb-4 border-b pb-2">お届先情報</h2>

                    <div class="space-y-3 text-gray-700">
                        <p><strong>お名前：</strong>{{ $checkoutData['family_name'] }} {{$checkoutData['last_name'] }}</p>
                        <p><strong>メールアドレス：</strong>{{ $checkoutData['email'] }}</p>
                        <p><strong>郵便番号：</strong>{{ $checkoutData['postal_code'] }}</p>
                        <p><strong>住所：</strong>{{ $checkoutData['address'] }}</p>
                        @if($checkoutData['phone_number'])
                            <p><strong>電話番号：</strong> {{ $checkoutData['phone_number'] }}</p>
                        @endif
                        <p><strong>お支払い方法：</strong>
                            {{
                                [
                                    'credit_card'=>'クレジットカード決済',
                                    'bank_transfer'=>'銀行振込',
                                    'cash_on_delivery'=>'代金引換（着払い）',
                                    'convenient_store'=>'コンビニ決済',
                                ][$checkoutData['payment_method']] ?? '不明'
                            }}
                        </p>
                    </div>
                </div>

                {{-- カート詳細 --}}
                <div clas="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-semibold mb-4 border-b pb-2">ご注文商品 ({{ count($cart) }}点)</h2>

                    @foreach($cart as $item)
                        <div class="flex items-center gap-4 py-3 border-b last:border-b-0">
                            <img src="{{$item['image_url'] ?? asset('image/products/placeholder.png') }}"
                                alt="{{ $item['product_name'] }}"
                                class="w-20 h-20 object-cover rounded">

                            <div class="flex-1">
                                <div class="font-semibold">{{ $item['product_name'] }}</div>
                                <div class="text-gray-600">
                                    単価： ¥{{ number_format($item['price'])}} / 数量： {{ $item['quantity'] }}
                                </div>
                            </div>
                            <div class="font-bold text-lg text-red-600">
                                ¥{{ number_format($item['price'] * $item['quantity']) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 右側：合計金額 --}}
            <div class="lg:w-80">
                <div class="sticky top-4 bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-2xl font-bold mb-4">合計金額</h2>

                    <div class="space-y-3">
                        <div class="flex justify-between text-lg text-gray-700">
                            <span>商品小計</span>
                            <span>¥{{ number_format($checkoutData['subtotal']) }}</span>
                        </div>

                        <div class="flex justify-between text-lg text-gray-700">
                            <span>送料</span>
                            <span>¥{{ number_format($checkoutData['shipping_fee']) }}</span>
                        </div>

                        <div class="border-t my-4 pt-4"></div>

                        <div class="flex justify-between text-3xl font-extrabold text-blue-700">
                            <div class="flex items-end">
                                <span>合計</span>
                                <span class="text-lg">(税込)</span>
                            </div>
                            <span>¥{{ number_format($checkoutData['total_amount']) }}</span>
                        </div>
                    </div>
                
            

                    <div class="mt-8 space-y-3">
                        {{-- 注文確定ボタン --}}
                        <button type="submit"
                            class="w-full px-6 py-4 bg-red-600 text-white text-xl fon-bold rounded-lg cursor-pointer
                                hover:bg-red-700 transition duration-150 shadow-md">
                            注文を確定する
                        </button>

                        {{-- 戻るボタン --}}
                        <a href="{{ route('checkout.delivery_form') }}"
                            class="w-full inline-block text-center px-6 py-2 border border-gray-300 rounded-lg
                                text-gray-600 hover:bg-gray-100 transition duration-150">
                            配送先情報を修正する
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection