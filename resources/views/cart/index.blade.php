@extends('layouts.common')

@section('title','カート')

@section('content')

<div class="max-w-6xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">カート</h1>

    {{-- 左：商品一覧 --}}
    <div class="flex flex-col md:flex-row gap-8 bg-white round-xl shadow">
        <div class="flex-1">
            @foreach($cart as $item)
                <div class="flex flex-col md:flex-row items-center gap-4 border-b py-4">
                    {{-- 商品画像 --}}
                    <img src="{{$item['image_url'] ?? asset('img/products/placeholder.png')}}"
                        alt="{{ $item['product_name']}}"
                        class="w-28 h-28 object-cover rounded">

                    {{-- 商品情報 --}}
                    <div class="flex-1 px-4">
                        <div class="font-semibold text-lg">{{ $item['product_name'] }}</div>
                        <div class="text-gray-600">¥{{ number_format($item['price'])}}</div>
                    </div>

                    {{-- 数量更新フォーム --}}
                    <div class="flex items-center gap-2">

                        {{-- 更新 --}}
                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">

                            <div class="flex items-center gap-3">

                                <button type="button"
                                    data-input-counter-decrement="{{ $item['product_id'] }}"
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-l border bg-gray-200 hover:bg-gray-300">
                                    −
                                </button>
                                
                                <input type="text"
                                    name="quantity"
                                    id="{{ $item['product_id'] }}"
                                    data-input-counter
                                    data-input-counter-min="1"
                                    data-input-counter-max="{{ $item['stock_quantity'] }}"
                                    class="h-10 w-16 border-t border-b text-center"
                                    value="{{ $item['quantity'] }}" />

                                <button type="button"
                                    data-input-counter-increment="{{ $item['product_id'] }}"
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-r border bg-gray-200 hover:bg-gray-300">
                                    +
                                </button>

                                <button type="submit"
                                    class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                    更新
                                </button>
                            </div>
                        </form>


                        {{-- 削除 --}}
                        <form action="{{ route('cart.delete') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                            <button type="submit"
                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                削除
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>


        {{-- 右：合計 --}}
        <div class="mt-10 bg-white p-6 rounded-xl shadow">
            <div class="flex justify-between text-lg">
                <span>商品小計</span>
                <span>¥{{ number_format($subtotal) }}</span>
            </div>

            <div class="flex justify-between text-lg mt-2">
                <span>送料</span>
                <span>¥{{ number_format($shipping)}}</span>
            </div>

            <div class="border-t my-4"></div>

            <div class="flex justify-between text-2xl font-bold">
                <span>合計</span>
                <span>¥{{ number_format($total) }}</span>
            </div>

            <div class="text-right mt-6">
                <a href="{{ route('checkout.delivery_form') }}"
                    class="inline-block px-8 py-3 bg-green-600 text-white rounded-lg">
                    購入手続きへ進む
                </a>
            </div>
        </div>

    </div>

</div>
@endsection