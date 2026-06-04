<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Validation\Rule;

class AdminOrders extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedOrder = null;
    public $showDetail = false;

    public function render()
    {
        $orders = Order::with('user', 'items.product')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('family_name', 'like', "%{$this->search}%")
                      ->orWhere('last_name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%")
                      ->orWhere('id', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin-orders', [
            'orders' => $orders,
        ]);
    }

    public function showOrderDetail($orderId)
    {
        $this->selectedOrder = Order::with('user', 'items.product')->findOrFail($orderId);
        $this->showDetail = true;
    }

    public function closeDetail()
    {
        $this->showDetail = false;
        $this->selectedOrder = null;
    }

    public function updateReadyStatus($orderItemId, $status)
    {
        $orderItem = OrderItem::findOrFail($orderItemId);
        $orderItem->ready_status = $status;
        $orderItem->save();

        $this->selectedOrder = Order::with('user', 'items.product')->findOrFail($this->selectedOrder->id);

        session()->flash('message', '準備状況を更新しました。');
    }
    
    public function updateOrderStatus($orderId, $status)
    {
        // バリデーション
        $validator = validator(
            ['order_status' => $status],
            ['order_status' => ['required', Rule::in([
                'pending', 'processing', 'shipped', 'delivered', 'cancelled'
            ])]]
        );

        if ($validator->fails()) {
            $this->addError('order_status', '無効なステータスです。');
            return;
        }

        $order = Order::findOrFail($orderId);
        $order->order_status = $status;
        $order->save();

        // 注文詳細を再取得して更新
        if ($this->showDetail && $this->selectedOrder) {
            $this->selectedOrder = Order::with('user', 'items.product')
                ->findOrFail($orderId);
        }

        session()->flash('message', '注文ステータスを更新しました。');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedPage()
    {
        $this->showDetail = false;
        $this->selectedOrder = null;
    }
}