@extends('layouts.common')

@section('title',$product->product_name)

@section('content')
<div class="max-w-6xl mx-auto py-8">

    <div class="flex flex-col md:flex-row gap-8">

        {{-- 商品画像 --}}
        <div class="w-full md:w-1/2">
            <img src="{{ $product->image_url }}" class="w-full max-w-[600px] rounded shadow">
        </div>

        {{-- 商品情報 --}}
        <div class="w-full md:w-1/2 flex flex-col justify-between">

            <h1 class="text-4xl font-bold mb-4">
                {{ $product->product_name}}
            </h1>

            <div class="text-4xl text-blue-600 font-bold mb-6">
                ¥{{ number_format($product->base_price )}}
            </div>

            <p class="text-2xl mb-6 text-gray-700">
                {{ $product->description }}
            </p>

            {{-- 在庫 --}}
            <div class="text-2xl mb-4">
                在庫：
                @if ($product->stock_quantity > 0)
                    <span class="text-green-600 font-semibold">{{ $product->stock_quantity }} 個</span>
                @else
                    <span class="text-red-500 font-semibold">在庫なし</span>
                @endif
            </div>

            {{-- カートに追加ボタン（後で作る）--}}
            <div class="text-right">
                <button class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">
                    カートに追加
                </button>
            </div>


        </div>
    </div>

</div>
@endsection
