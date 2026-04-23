<div class="px-8 py-6 min-h-[300px] flex flex-col justify-center">
    @if ($status)
        <div class="text-center animate-fade-in">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-6">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-800 mb-3">メールを送信しました</h1>
            
            <p class="text-gray-600 mb-8 leading-relaxed">
                {{ $status }}<br>
                <span class="text-sm">※届かない場合は、迷惑メールフォルダもご確認ください。</span>
            </p>

            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors">
                &larr; ログイン画面へ戻る
            </a>
        </div>
    @else
        <h1 class="text-2xl font-bold mb-2">パスワード再発行</h1>
        <p class="text-gray-500 mb-8 text-sm">
            ご登録済みのメールアドレスを入力してください。<br>再設定用のリンクをお送りします。
        </p>

        <form wire:submit="sendResetLink" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">メールアドレス</label>
                <input type="email" 
                       wire:model="email" 
                       class="border-2 w-full p-2.5 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all @error('email') border-red-500 @enderror"
                       placeholder="example@mail.com">
                @error('email') 
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col space-y-4">
                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="w-full bg-blue-600 text-white py-2.5 rounded-md font-bold hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center cursor-pointer">
                    
                    <span wire:loading wire:target="sendResetLink" class="mr-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    
                    <span wire:loading.remove wire:target="sendResetLink">リセットリンクを送信</span>
                    <span wire:loading wire:target="sendResetLink">送信中...</span>
                </button>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700">キャンセル</a>
                </div>
            </div>
        </form>
    @endif
</div>