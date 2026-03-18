<div class="container mx-auto py-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- ヘッダー -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">商品管理</h1>
                <button 
                    wire:click="openForm()" 
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition">
                    新規商品を追加
                </button>
            </div>

            <!-- メッセージ通知 -->
            @if($message)
                <div class="mb-4 p-4 rounded-lg {{ $messageType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $message }}
                </div>
            @endif

            <!-- 検索フォーム -->
            <div class="mb-6">
                <input 
                    type="text" 
                    wire:model.live="search" 
                    placeholder="商品ID または商品名で検索..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- 商品一覧テーブル -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b-2 border-gray-300">
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">商品ID</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">商品名</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">カテゴリー</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">価格</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">在庫</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700">状態</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700">アクション</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-800">{{ $product->id }}</td>
                                <td class="px-4 py-3 text-gray-800">{{ $product->product_name }}</td>
                                <td class="px-4 py-3 text-gray-800">
                                    {{ $product->category?->category_name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-right text-gray-800">¥{{ number_format($product->base_price) }}</td>
                                <td class="px-4 py-3 text-right text-gray-800">{{ $product->stock_quantity }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($product->is_active)
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">有効</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">無効</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center space-x-2">
                                    <button 
                                        wire:click="openForm('{{ $product->id }}')" 
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-sm transition">
                                        編集
                                    </button>
                                    <button 
                                        wire:click="delete('{{ $product->id }}')"
                                        wire:confirm="この商品を削除してもよろしいですか？"
                                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-sm transition">
                                        削除
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                    商品が見つかりません
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ページネーション -->
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <!-- モーダルフォーム -->
    @if($showForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ $editingId ? '商品を編集' : '新規商品を作成' }}
                    </h2>
                    <button 
                        wire:click="closeForm()" 
                        class="text-gray-600 hover:text-gray-800 text-2xl">
                        &times;
                    </button>
                </div>

                <form wire:submit="save" class="space-y-6">
                    <!-- 商品ID -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            商品ID <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text"
                            wire:model="form.id"
                            @if($editingId) disabled @endif
                            placeholder="例: PROD001"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @if($editingId) bg-gray-100 @endif">
                        @error('form.id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- 商品名 -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            商品名 <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text"
                            wire:model="form.product_name"
                            placeholder="商品名を入力"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('form.product_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- カテゴリー -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">カテゴリー</label>
                        <select 
                            wire:model="form.category_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">選択してください</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                        @error('form.category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- 説明 -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">説明</label>
                        <textarea 
                            wire:model="form.description"
                            placeholder="商品の説明を入力"
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        @error('form.description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- 価格 -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            価格(税抜き) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number"
                            step="0.01"
                            wire:model="form.base_price"
                            placeholder="0.00"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('form.base_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- 在庫数量 -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            在庫数量 <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number"
                            wire:model="form.stock_quantity"
                            placeholder="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('form.stock_quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- アクティブ状態 -->
                    <div>
                        <label class="flex items-center space-x-2">
                            <input 
                                type="checkbox"
                                wire:model="form.is_active"
                                class="rounded border-gray-300 focus:ring-2 focus:ring-blue-500">
                            <span class="text-sm font-semibold text-gray-700">商品公開（有効）</span>
                        </label>
                        @error('form.is_active') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- ボタン -->
                    <div class="flex justify-end space-x-4 pt-4">
                        <button 
                            type="button"
                            wire:click="closeForm()"
                            class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                            キャンセル
                        </button>
                        <button 
                            type="submit"
                            class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition">
                            {{ $editingId ? '更新' : '作成' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
