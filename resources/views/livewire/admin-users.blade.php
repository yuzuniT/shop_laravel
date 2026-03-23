<div class="container mx-auto py-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">ユーザー管理</h1>
                    <p class="text-gray-600 mt-1">ユーザー情報の閲覧・編集・有効/無効化ができます。</p>
                </div>

                <div class="flex items-center gap-2">
                    <input 
                        type="text" 
                        wire:model.live="search" 
                        placeholder="名前・メールで検索..."
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-96">
                    <button 
                        wire:click="openForm()" 
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold px-4 py-2 rounded-lg cursor-pointer">
                        新規ユーザー作成
                    </button>
                </div>
            </div>

            @if($message)
            <div class="mb-4 p-4 rounded-lg {{ $messageType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $message }}
            </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b-2 border-gray-300">
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">名前</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">メールアドレス</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">ロール</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700">状態</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700">アクション</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr wire:key="user-{{ $user->id }}" class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-800">{{ $user->family_name }} {{ $user->last_name }}</td>
                            <td class="px-4 py-3 text-gray-800">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-gray-800">{{ $user->role === 'admin' ? '管理者' : '一般' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($user->is_deleted)
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">無効</span>
                                @else
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">有効</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center space-x-2">
                                <button 
                                    wire:click="openForm('{{ $user->id }}')" 
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-sm cursor-pointer">
                                    編集
                                </button>

                                <button 
                                    wire:click="toggleDeleted('{{ $user->id }}')"
                                    class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-1 px-3 rounded text-sm cursor-pointer">
                                    {{ $user->is_deleted ? '復元' : '無効化' }}
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                ユーザーが見つかりません
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6
                [&_a]:cursor-pointer
                [&_button]:cursor-pointer
                [&_a:hover]:!bg-gray-100
                [&_a:hover]:!text-gray-700
                [&_[aria-current='page']>span]:!bg-gray-200
                [&_[aria-current='page']>span]:!text-gray-700">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    @if($showForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ $editingId ? 'ユーザーを編集' : '新規ユーザーを作成' }}
                    </h2>
                    <button 
                        wire:click="closeForm()" 
                        class="text-gray-600 hover:text-gray-800 text-2xl">
                        &times;
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">姓 <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                wire:model="form.family_name" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('form.family_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">名 <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                wire:model="form.last_name" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('form.last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">メールアドレス <span class="text-red-500">*</span></label>
                        <input 
                            type="email" 
                            wire:model="form.email" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('form.email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">パスワード {{ $editingId ? '(変更する場合のみ入力)' : '' }} <span class="text-red-500">{{ $editingId ? '' : '*' }}</span></label>
                            <input 
                                type="password" 
                                wire:model="form.password" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('form.password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">ロール</label>
                            <select 
                                wire:model="form.role" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="user">一般</option>
                                <option value="admin">管理者</option>
                            </select>
                            @error('form.role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <input 
                            type="checkbox" 
                            wire:model="form.is_deleted" 
                            id="user_disabled" 
                            class="rounded border-gray-300 focus:ring-2 focus:ring-blue-500">
                        <label for="user_disabled" class="text-sm font-semibold text-gray-700">無効化（ログイン不可にします）</label>
                    </div>

                    <div class="flex justify-end space-x-4 pt-4">
                        <button 
                            type="button" 
                            wire:click="closeForm()" 
                            class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50">
                            キャンセル
                        </button>
                        <button 
                            type="submit" 
                            class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg">
                            {{ $editingId ? '更新' : '作成' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
