<div class="px-8 py-6">
    <h1 class="text-2xl font-bold mb-7">新しいパスワードの設定</h1>

    <form wire:submit="resetPassword" class="space-y-5">

        <div>
            <label class="block">メールアドレス</label>
            <input type="email" wire:model="email" class="border-2 w-full p-2 bg-gray-100" readonly>
            @error('email')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block">新しいパスワード</label>
            <input type="password" wire:model="password" class="border-2 w-full p-2 bg-white">
            @error('password')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block">パスワード（確認）</label>
            <input type="password" wire:model="password_confirmation" class="border-2 w-full p-2 bg-white">
        </div>

        <div class="flex justify-end">
            <button class="bg-green-600 text-white px-4 py-2 rounded cursor-pointer hover:bg-green-700">
                パスワードを更新する
            </button>
        </div>
    </form>
</div>