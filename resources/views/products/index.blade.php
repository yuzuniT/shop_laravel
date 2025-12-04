@extends('layouts.common')

@section('title','Sound Space')

@section('content')
<div class="container mx-auto py-8">

    <h1 class="text-2xl font-bold mb-6">商品一覧</h1>

    @if(request('search'))
        <p class="mb-4 text-gray-600">
            「{{ request('search') }}」の検索結果：{{ $products->total() }}件
        </p>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($products as $product)
            <div class="border p-4 rounded-lg shadow-sm bg-white">
                <a href="{{ route('products.show',$product->id) }}">
                    <img src={{ $product->image_url }} class="mb-4 scale-100 hover:scale-110 transition-transform duraion-100 hover:shadow-lg">
                </a>
                <div class="font-bold text-lg mb-1">
                    {{ $product->product_name }}
                </div>

                <div class="text-sm text-gray-600 mb-2">
                    {{ $product->category->category_name ?? 'カテゴリなし' }}
                </div>

                <div class="text-2xl text-blue-600 font-bold mb-3">
                    ¥{{ number_format($product->base_price) }}
                </div>

                <div class="flex justify-around">

                    <a href="{{ route('products.show', $product->id) }}"
                        class="inline-block bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600">
                        購入する
                    </a>

                    <a href="{{ route('products.show', $product->id) }}"
                        class="inline-block bg-blue-500 text-white px-5 py-2 rounded hover:bg-blue-600">
                        詳細を見る
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
@endsection