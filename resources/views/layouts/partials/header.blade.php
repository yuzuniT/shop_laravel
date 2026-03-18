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

                @if(auth()->user()->isAdmin())
                <div class="text-xs lg:text-sm whitespace-nowrap">
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-blue-600 hover:underline">
                        管理画面へ
                    </a>
                </div>
                @endif
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