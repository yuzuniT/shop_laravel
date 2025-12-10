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
            <button class="bg-green-600 text-white px-4 py-2 rounded">
                ログイン
            </button>
        </div>
    </form>

    
</div>