<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;

class UpdateCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->input('product_id');
        $product = Product::find($productId);
        $maxQuantity = $product ? $product->stock_quantity : 0;

        return [
            'product_id' => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:1', 'max:' . $maxQuantity],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => '商品IDは必須です。',
            'quantity.required' => '数量は必須です。',
            'quantity.integer' => '数量は整数で入力してください。',
            'quantity.min' => '数量は1以上を指定してください。',
            'quantity.max' => '在庫が不足しています。',
        ];
    }
}
