<div class="container mx-auto py-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- ヘッダー -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">注文管理</h1>
            </div>

            <!-- メッセージ通知 -->
            @if(session('message'))
                <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-800">
                    {{ session('message') }}
                </div>
            @endif

            <!-- 検索フォーム -->
            <div class="mb-6">
                <input
                    type="text"
                    wire:model.live="search"
                    placeholder="注文ID、名前、またはメールアドレスで検索..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- 注文一覧テーブル -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b-2 border-gray-300">
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">注文ID</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">注文者</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">メールアドレス</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">合計金額</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700">注文日時</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700">アクション</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr wire:key="order-{{ $order->id }}" class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-800">{{ $order->id }}</td>
                                <td class="px-4 py-3 text-gray-800">{{ $order->family_name }} {{ $order->last_name }}</td>
                                <td class="px-4 py-3 text-gray-800">{{ $order->email }}</td>
                                <td class="px-4 py-3 text-right text-gray-800">¥{{ number_format($order->total_amount) }}</td>
                                <td class="px-4 py-3 text-center text-gray-800">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <button
                                        wire:click="showOrderDetail({{ $order->id }})"
                                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded text-sm transition cursor-pointer">
                                        詳細
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                    注文が見つかりません
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ページネーション -->
            <div class="mt-6
                [&_a]:cursor-pointer
                [&_button]:cursor-pointer
                [&_a:hover]:!bg-gray-100
                [&_a:hover]:!text-gray-700
                [&_[aria-current='page']>span]:!bg-gray-200
                [&_[aria-current='page']>span]:!text-gray-700">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    <!-- 注文詳細モーダル -->
    @if($showDetail && $selectedOrder)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-lg p-8 max-w-4xl w-full max-h-screen overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">注文詳細 #{{ $selectedOrder->id }}</h2>
                    <button wire:click="closeDetail" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>

                <!-- 注文情報 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-3">注文者情報</h3>
                        <p><strong>名前:</strong> {{ $selectedOrder->family_name }} {{ $selectedOrder->last_name }}</p>
                        <p><strong>メール:</strong> {{ $selectedOrder->email }}</p>
                        <p><strong>電話:</strong> {{ $selectedOrder->phone_number }}</p>
                        <p><strong>住所:</strong> {{ $selectedOrder->postal_code }} {{ $selectedOrder->address }}</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-3">注文情報</h3>
                        <p><strong>注文日時:</strong> {{ $selectedOrder->created_at->format('Y-m-d H:i:s') }}</p>
                        <p><strong>支払い方法:</strong> {{ ['credit_card'=>'クレジットカード決済', 'bank_transfer'=>'銀行振込', 'cash_on_delivery'=>'代金引換（着払い）', 'convenient_store'=>'コンビニ決済'][$selectedOrder->payment_method] ?? '不明' }}</p>
                        <p><strong>送料:</strong> ¥{{ number_format($selectedOrder->shipping_fee) }}</p>
                        <p><strong>合計金額:</strong> ¥{{ number_format($selectedOrder->total_amount) }}</p>
                    </div>
                </div>

                <!-- 注文商品 -->
                <h3 class="text-lg font-semibold mb-3">注文商品</h3>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left border border-gray-300">商品名</th>
                                <th class="px-4 py-2 text-right border border-gray-300">価格</th>
                                <th class="px-4 py-2 text-center border border-gray-300">数量</th>
                                <th class="px-4 py-2 text-right border border-gray-300">小計</th>
                                <th class="px-4 py-2 text-center border border-gray-300">準備状況</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($selectedOrder->items as $item)
                                <tr class="border border-gray-300">
                                    <td class="px-4 py-2 border border-gray-300">{{ $item->product->product_name }}</td>
                                    <td class="px-4 py-2 text-right border border-gray-300">¥{{ number_format($item->price) }}</td>
                                    <td class="px-4 py-2 text-center border border-gray-300">{{ $item->quantity }}</td>
                                    <td class="px-4 py-2 text-right border border-gray-300">¥{{ number_format($item->price * $item->quantity) }}</td>
                                    <td class="px-4 py-2 text-center border border-gray-300">
                                        <select wire:change="updateReadyStatus({{ $item->id }}, $event.target.value)" class="border border-gray-300 rounded px-2 py-1">
                                            <option value="pending" {{ $item->ready_status == 'pending' ? 'selected' : '' }}>準備中</option>
                                            <option value="ready" {{ $item->ready_status == 'ready' ? 'selected' : '' }}>準備完了</option>
                                            <option value="shipped" {{ $item->ready_status == 'shipped' ? 'selected' : '' }}>発送済み</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 text-right">
                    <button wire:click="closeDetail" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition">
                        閉じる
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>