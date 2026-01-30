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
                {{-- 💡 情報アイコン (サークル) --}}
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