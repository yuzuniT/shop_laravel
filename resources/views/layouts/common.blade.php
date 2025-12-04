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
            <img src={{asset("img/logo/SoundSpace.png")}} class="w-50 h-auto">
        </a>

        <div class="flex-1 px-6">

            <form action="{{ route('products.index') }}" method="GET" class="flex">
                <input type="search" name="search"  value="{{ request('search') }}" placeholder="商品を検索"
                class="w-full min-w-[100px] border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
                <button type="submit"
                class="bg-blue-500 text-white whitespace-nowrap px-4 rounded-r-md hover:bg-blue-600">検索</button>
            </form>

        </div>

        <div class="mr-4">

            <!--ログイン済 : ようこそ！◯◯さん
                未ログイン : 「ログイン」リンクで誘導-->
            <div>
                <p class="text-sm"><span>ようこそ！</span></p>
            </div>
{{--ディレクティブで記述する
            <?php if (!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["name"]))):?>
                <a href='user_login/login.php'>ログイン</a>
            <?php endif; ?>
--}}
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
{{--ディレクティブで記述する
            <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true):?>
                <a class="main_button" href="user_login/logout.php">
                    <img class="main_button" src={{asset("img/main_buttons/logout.png")}}>
                </a>
            <?php endif; ?>
--}}

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
