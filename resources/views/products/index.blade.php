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
            <div class="border p-4 rounded-lg shadow-sm bg-white flex flex-col">
                <a href="{{ route('products.show',$product->id) }}" class="block overflow-hidden rounded-md mb-4">
                    <img src={{ $product->image_url }} class="w-full h-auto scale-100 hover:scale-110 transition-transform duraion-100 hover:shadow-lg">
                </a>
                <div class="font-bold text-lg mb-1 line-clamp-4 h-[5.5rem] leading-7">
                    {{ $product->product_name }}
                </div>

                <div class="text-sm text-gray-600 mb-2">
                    {{ $product->category->category_name ?? 'カテゴリなし' }}
                </div>

                <div class="text-2xl text-blue-600 font-bold mb-3 mt-auto">
                    ¥{{ number_format($product->base_price) }}
                </div>

                <div class="flex flex-col md:flex-row gap-2">
                    <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}"">
                        <input type="hidden" name="quantity" value="1">

                        <button type="submit"
                            class="w-full bg-red-500 text-white px-2 py-2 rounded text-sm font-bold hover:bg-red-600 cursor-pointer whitespace-nowrap">
                            購入する
                        </button>
                    </form>

                    <a href="{{ route('products.show', $product->id) }}"
                        class="flex-1 bg-blue-500 text-white px-2 py-2 rounded text-sm font-bold hover:bg-blue-600 text-center whitespace-nowrap">
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