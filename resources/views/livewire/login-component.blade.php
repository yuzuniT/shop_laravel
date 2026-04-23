<div class="px-8 py-6">
    <h1 class="text-2xl font-bold mb-7">ログイン</h1>

    <form wire:submit="login" class="space-y-5">

        @error('login') <p class="text-red-600 text-sm">{{ $message}}</p>@enderror
        <div>
            <label class="block">メール</label>
            <input type="email" wire:model.defer="email" class="border-2 w-full p-2 bg-white">
            @error('email') <p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block">パスワード</label>
            <input type="password" wire:model.defer="password" class="border-2 w-full p-2 bg-white">
            @error('password') <p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>

        <div class="flex justify-end">
            <button class="bg-green-600 text-white px-4 py-2 rounded cursor-pointer hover:bg-green-700">
                ログイン
            </button>
        </div>
    </form>

    <hr class="my-8 border-gray-200">

    <div class="text-center text-sm text-gray-600">
        会員登録はお済みですか？ <br>
        まだの場合は
        <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">
            こちらから新規会員登録
        </a>
        をお願いします。
    </div>

    <hr class="my-8 border-gray-200">

    <div class="text-center text-sm text-gray-600">
        パスワードをお忘れですか？ <br>
        その場合は
        <a href="{{ route('password.request') }}" class="text-blue-600 font-bold hover:underline">
            こちらからパスワード再発行
        </a>
        をお願いします。
    </div>

</div>