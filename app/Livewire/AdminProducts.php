<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Validation\Rule;

class AdminProducts extends Component
{
    use WithPagination;

    public $search = '';
    public $showForm = false;
    public $editingId = null;
    
    public $form = [
        'id' => '',
        'product_name' => '',
        'description' => '',
        'base_price' => '',
        'stock_quantity' => '',
        'category_id' => '',
        'is_active' => true,
    ];

    public $message = '';
    public $messageType = ''; // 'success' or 'error'

    protected function rules()
    {
        return [
            'form.id' => [
                'required',
                'string',
                'max:10',
                Rule::unique('products', 'id')->ignore($this->editingId, 'id')
            ],
            'form.product_name' => 'required|string|max:255',
            'form.description' => 'nullable|string',
            'form.base_price' => 'required|numeric|min:0',
            'form.stock_quantity' => 'required|integer|min:0',
            'form.category_id' => 'nullable|exists:categories,id',
            'form.is_active' => 'boolean',
        ];
    }

    public function mount()
    {
        // 初期化
    }

    public function render()
    {
        $products = Product::where('product_name', 'like', "%{$this->search}%")
            ->orWhere('id', 'like', "%{$this->search}%")
            ->paginate(10);

        $categories = Category::all();

        return view('livewire.admin-products', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function openForm($productId = null)
    {
        if ($productId) {
            // 編集モード
            $product = Product::findOrFail($productId);
            $this->editingId = $productId;
            $this->form = [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'description' => $product->description,
                'base_price' => $product->base_price,
                'stock_quantity' => $product->stock_quantity,
                'category_id' => $product->category_id,
                'is_active' => $product->is_active,
            ];
        } else {
            // 新規作成モード
            $this->resetForm();
            $this->editingId = null;
        }
        $this->showForm = true;
        $this->message = '';  // フォーム開く時だけメッセージをリセット
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
        // メッセージはリセットしない（ユーザーが確認できるように）
    }

    public function resetForm()
    {
        $this->form = [
            'id' => '',
            'product_name' => '',
            'description' => '',
            'base_price' => '',
            'stock_quantity' => '',
            'category_id' => '',
            'is_active' => true,
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editingId) {
                // 更新
                $product = Product::findOrFail($this->editingId);
                $product->update([
                    'product_name' => $this->form['product_name'],
                    'description' => $this->form['description'],
                    'base_price' => $this->form['base_price'],
                    'stock_quantity' => $this->form['stock_quantity'],
                    'category_id' => $this->form['category_id'],
                    'is_active' => $this->form['is_active'],
                ]);
                $this->message = '商品を更新しました。';
            } else {
                // 作成
                Product::create($this->form);
                $this->message = '商品を作成しました。';
            }
            $this->messageType = 'success';
            $this->closeForm();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->message = 'エラーが発生しました: ' . $e->getMessage();
            $this->messageType = 'error';
        }
    }

    public function delete($productId)
    {
        try {
            Product::findOrFail($productId)->delete();
            $this->message = '商品を削除しました。';
            $this->messageType = 'success';
            $this->resetPage();
        } catch (\Exception $e) {
            $this->message = 'エラーが発生しました: ' . $e->getMessage();
            $this->messageType = 'error';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
