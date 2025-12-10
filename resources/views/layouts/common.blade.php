<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Sound Space')</title>
    {{--<link rel="stylesheet" href="{{asset('style.css')}}">--}}
    @stack('styles')
    {{-- Vite が生成したCSS/JSを自動読み込み --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-gray-50">
<header class="w-full bg-white shadow">
    <div class="max-w-7xl mx-auto flex items-center justify-between py-4 px-4">

        <a href="{{ route('products.index') }}" class="flex items-center">
            <img src={{asset("img/logo/SoundSpace.png")}} class="w-50 h-auto hover:opacity-70">
        </a>

        <div class="flex-1 px-6">

            <form action="{{ route('products.index') }}" method="GET" class="flex">
                <input type="search" name="search"  value="{{ request('search') }}" placeholder="商品を検索"
                class="w-full min-w-[100px] border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
                <button type="submit"
                class="bg-blue-500 text-white whitespace-nowrap px-4 rounded-r-md hover:bg-blue-600">
            <svg class="w-5 h-5" data-slot="icon" aria-hidden="true" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
  <path d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg></button>
            </form>

        </div>

        <div class="mr-4">

            {{-- ログイン済 --}}
            @auth
            <div>
                <p class="flex text-sm">
                    <span>ようこそ、</span>
                    <div class="whitespace-nowrap">
                        <span class="font-bold text-blue-600">{{ Auth::user()->full_name }}</span>
                        <span>さん！</span>
                    </div>
                </p>
            </div>
            @endauth

            {{-- 未ログイン --}}
            @guest
            <div>
                <a href="{{ route('login') }}"
                class="bg-green-600 text-white px-5 py-2 rounded-full shadow-lg whitespace-nowrap hover:bg-green-700">ログイン</a>
            </div>    
            @endguest

        </div>

        <div class="flex items-center gap-4">

            <!--カートを見る-->
            <a href="{{ route('cart.index') }}">
                <img src={{asset("img/main_buttons/cart.png")}} class="w-18 h-auto hover:opacity-70">
            </a>
            <!--お問い合わせ-->
            <a href="{{ route('contact.create') }}">
                <img src={{asset("img/main_buttons/question.png")}} class="w-18 h-auto hover:opacity-70">
            </a>
            <!--ログアウト-->
            @auth
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="p-0 border-0 bg-transparent cursor-pointer w-18 h-auto hover:opacity-70">
                    <img src="{{asset("img/main_buttons/logout.png")}}">
                </button>
            </form>
            @endauth

        </div>


    </div>


</header>

<main class="max-w-7xl mx-auto mt-5 px-4">
    



        <div class="mt-4 mb-5">
            <h1 class="text-2xl font-semibold">@yield('content_title')</h1>
        </div>

        <div class="content">
            @yield('content')
        </div>
</main>

        <footer class="mt-10 bg-white shadow-inner py-4">
            <div class="max-w-7xl mx-auto text-center text-sm text-zinc-500">
                2025 Sound Space Co. Ltd. All Rights Reserved.
            </div>
        </footer>
    @stack('scripts')
</body>


</html>
