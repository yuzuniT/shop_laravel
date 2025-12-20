<div class="px-8 py-6">
    <h1 class="text-2xl font-bold mb-7">会員登録</h1>

    <form wire:submit="register" class="space-y-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- 姓 --}}
            <div>
                <label class="block">姓</label>
                <input type="text" wire:model.defer="family_name" class="border-2 w-full p-2 bg-white">
                @error('family_name') <p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            {{-- 名 --}}
            <div>
                <label class="block">名</label>
                <input type="text" wire:model.defer="last_name" class="border-2 w-full p-2 bg-white">
                @error('last_name') <p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>
        </div>



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

        <div>
            <label class="block">パスワード（確認）</label>
            <input type="password" wire:model.defer="password_confirmation" class="border-2 w-full p-2 bg-white">
        </div>

        <div class="flex justify-end">
            <button class="bg-green-600 text-white px-4 py-2 rounded cursor-pointer hover:bg-green-700">
                登録
            </button>
        </div>
    </form>

    
</div>