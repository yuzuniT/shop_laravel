@extends('layouts.common')


@section('title','Sound Space/買い物かごは空です')


@section('content')
        <x-message-box :title="'買い物かごは空です'">
                <p class="text-gray-400 text-base">

                        現在、買い物かごには商品が入っていません。ぜひお買い物をお楽しみください。
                        <br>
                        ご利用をお待ちしております。
                        <br>
                        <br>
                        <a class="text-blue-500 hover:text-blue-700" href="{{ route('products.index') }}">トップページはこちら</a>
                </p>
        </x-message-box>

@endsection
