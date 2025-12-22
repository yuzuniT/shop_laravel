<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Sound Space')</title>
    @stack('styles')
    {{-- Vite が生成したCSS/JSを自動読み込み --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-gray-50">
<header class="w-full bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-3">

        {{-- 上段：ロゴとアイコン類 --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

            {{-- ロゴ --}}
            <div class="flex items-center justify-between md:justify-start">
                <a href="{{ route('products.index') }}" class="flex items-center">
                    <img src="{{asset('img/logo/SoundSpace.png')}}" class="w-32 md:w-48 h-auto hover:opacity-70">
                </a>

                {{-- アイコン（スマホ用） --}}
                <div class="flex md:hidden items-center gap-2">
                    <!--カートを見る-->
                    <a href="{{ route('cart.index') }}">
                        <img src={{asset("img/main_buttons/cart.png")}} class="w-14 md:w-16 h-auto hover:opacity-70">
                    </a>
                    <!--お問い合わせ-->
                    <a href="{{ route('contact.create') }}">
                        <img src={{asset("img/main_buttons/question.png")}} class="w-14 md:w-16 h-auto hover:opacity-70">
                    </a>
                    <!--ログアウト-->
                    @auth
                    <form method="POST" action="{{ route('logout') }}" class="flex items-center">
                        @csrf
                        <button type="submit" class="p-0 border-0 bg-transparent cursor-pointer hover:opacity-70">
                            <img src="{{asset("img/main_buttons/logout.png")}}" class="block w-14 md:w-16 h-auto">
                        </button>
                    </form>
                    @endauth
                </div>
            </div>

        {{-- 中央（検索フォーム） --}}
        <div class="flex-1 w-full md:max-w-4xl">

            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-6">

                <form action="{{ route('products.index') }}" method="GET" class="flex flex-1">
                    <input type="search" name="search"  value="{{ request('search') }}" placeholder="商品を検索"
                    class="w-full border rounded-l-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 text-sm">
                    <button type="submit"
                    class="bg-blue-500 text-white px-4 rounded-r-md hover:bg-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" data-slot="icon" aria-hidden="true" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                </form>
            {{-- 下段：未ログイン（ログインリンク） --}}
                @guest
                <div class="flex justify-end md:shrink-0">
                    <a href="{{ route('login') }}" class="text-green-600 text-lg font-bold hover:underline flex items-center gap-1 py-1 md:py-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                        </svg>
                        ログインはこちら
                    </a>
                </div>
                @endguest
            </div>

        </div>

            {{-- 右側：ユーザー名（PCのみ）とPC用アイコン --}}
            <div class="hidden md:flex items-center gap-4">

                {{-- ログイン済メッセージ（中画面以上で表示） --}}
                @auth
                <div class="text-xs lg:text-sm whitespace-nowrap">
                    <p>
                        ようこそ、<span class="font-bold text-blue-600">{{ Auth::user()->full_name }}</span>さん！
                    </p>
                </div>
                @endauth


                {{-- アイコン類 --}}
                <div class="flex items-center gap-2">

                    <!--カートを見る-->
                    <a href="{{ route('cart.index') }}">
                        <img src={{asset("img/main_buttons/cart.png")}} class="w-14 md:w-16 h-auto hover:opacity-70">
                    </a>
                    <!--お問い合わせ-->
                    <a href="{{ route('contact.create') }}">
                        <img src={{asset("img/main_buttons/question.png")}} class="w-14 md:w-16 h-auto hover:opacity-70">
                    </a>
                    <!--ログアウト-->
                    @auth
                    <form method="POST" action="{{ route('logout') }}" class="flex items-center">
                        @csrf
                        <button type="submit" class="p-0 border-0 bg-transparent cursor-pointer hover:opacity-70">
                            <img src="{{asset("img/main_buttons/logout.png")}}" class="block w-14 md:w-16 h-auto">
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
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

@if (session('success') || session('error') || session('status'))

    {{-- セッションにフラッシュメッセージが存在する場合のみ表示 --}}
    <div x-data="{ show: true }" 
         x-init="setTimeout(() => show = false, 5000)" {{-- 💡 5秒後に 'show' を false にする --}}
         x-show="show" {{-- 'show' が true の間だけ表示 --}}
         x-transition:leave.duration.500ms
         class="fixed bottom-4 right-4 z-50 p-4 rounded-lg shadow-xl text-white max-w-md"
         style="{{ session('success') ? 'background-color: #48BB78;' : 
                 (session('error') ? 'background-color: #F56565;' : 'background-color: #4299E1;') }}">
        
        <p class="font-semibold flex items-center">
            @if (session('success'))
                {{-- 💡 成功アイコン (Solid) --}}
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.536-2.006-2.006a.75.75 0 1 0-1.06 1.06l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.606Z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            @elseif (session('error'))
                {{-- 💡 エラーアイコン (Solid) --}}
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                </svg>
                {{ session('error') }}
            @elseif (session('status'))
                {{-- 💡 情報アイコン (前回提示のもの、サークル) --}}
                <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                {{ session('status') }}
            @endif
        </p>

        {{-- ユーザーが手動で閉じるためのボタン (オプション) --}}
        <button @click="show = false" class="absolute top-1 right-1 text-white/80 hover:text-white">&times;</button>
        
    </div>

@endif

</main>

        <footer class="mt-10 bg-white shadow-inner py-4">
            <div class="max-w-7xl mx-auto text-center text-sm text-zinc-500">
                2025 Sound Space Co. Ltd. All Rights Reserved.
            </div>
        </footer>
    @stack('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>


</html>
