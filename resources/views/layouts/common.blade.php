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
                class="inline-flex  items-center gap-2 bg-green-600 text-white px-5 py-2 rounded-full shadow-lg whitespace-nowrap hover:bg-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                    </svg>
                    ログイン
                </a>
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
