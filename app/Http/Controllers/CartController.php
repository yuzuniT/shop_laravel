<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\CartService;

class CartController extends Controller
{
    public function index(CartService $cartService)
    {
        $cart = session()->get('cart',[]);
        return view('cart.index', [
            'cart' => $cart,
            'subtotal' => $cartService->subtotal($cart),
            'shipping' => $cartService->shipping(),
            'total' => $cartService->total($cart),
        ]);
    }

    public function empty()
    {
        return view('cart.empty');
    }

    public function add(Request $request)
    {
        $product_id=$request->product_id;
        $quantity=max((int)$request->quantity,1); // 数量として不正な値が渡された時に最低値1を保証する

        $product=Product::findOrFail($product_id);

        // 現在のカート情報取得
        $cart=session()->get('cart',[]);

        // カートに商品がすでにある場合は数量を加算
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            // カートに商品がない場合
            $cart[$product_id] = [
                'product_id'=>$product->id,
                'product_name'=>$product->product_name,
                'price'=>$product->base_price,
                'quantity'=>$quantity,
                'image_url'=>$product->image_url,
                'stock_quantity'=>$product->stock_quantity,
            ];
        }

        // セッションに保存
        session()->put('cart',$cart);

        return redirect()->route('cart.index');
    }

    public function update(Request $request)
    {
        $product_id=$request->product_id;
        $new_quantity=max((int)$request->quantity,1);

        // 現在のカート情報取得
        $cart=session()->get('cart',[]);

        // カートに商品があるか確認して更新
        if (isset($cart[$product_id])){
            $max=$cart[$product_id]['stock_quantity'];
            $cart[$product_id]['quantity'] = min($new_quantity, $max); // 在庫より大きい数はセットできない
        }

        // セッションに保存
        session()->put('cart',$cart);

        return redirect()->route('cart.index');
    }

    public function delete(Request $request)
    {
        $product_id=$request->product_id;

        // 現在のカート情報取得
        $cart=session()->get('cart',[]);

        // カートに商品があるか確認して削除
        if (isset($cart[$product_id])){
            unset($cart[$product_id]);
        }

        // セッションに保存
        session()->put('cart',$cart);

        return redirect()->route('cart.index');
    }
}
