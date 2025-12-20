<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Http\Requests\DeliveryFormRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;


class CheckoutController extends Controller
{
    // 配送先情報入力画面
    public function delivery_form()
    {
        $user=Auth::user();

        return view('checkout.delivery_form', [
            'user'=>$user,  
        ]);
    }

    // 注文内容確認画面
    public function confirm(DeliveryFormRequest $request, CartService $cartService)
    {
        // 前ページの入力をバリデーション
        $validated=$request->validated();

        $cart=session()->get('cart',[]);

        $checkoutData = array_merge($validated, [
            'subtotal' => $cartService->subtotal($cart),
            'shipping_fee' => $cartService->shipping(),
            'total_amount' => $cartService->total($cart),
        ]);

        session()->put('checkout_data',$checkoutData);

        return view('checkout.confirm',[
            'cart'=>$cart,
            'checkoutData'=>$checkoutData,
        ]);
    }

    // 注文確定処理
    public function store()
    {
        // セッションからデータとカート情報を取得
        $cart=session()->get('cart',[]);
        $checkoutData=session()->get('checkout_data',[]);

        if (empty($cart) || empty($checkoutData)) {
            return redirect()->route('cart.index')
                ->with('error','注文情報が不足しています。');
        }

        // データベーストランザクション開始
        DB::beginTransaction();
        try {
            // 注文ヘッダーを作成
            $order = Order::create([
                'user_id' => Auth::id(), // ログインユーザーID
                'family_name' => $checkoutData['family_name'],
                'last_name' => $checkoutData['last_name'],
                'postal_code' => $checkoutData['postal_code'],
                'email' => $checkoutData['email'],
                'phone_number' => $checkoutData['phone_number'] ?? null,
                'address' => $checkoutData['address'],
                'payment_method' => $checkoutData['payment_method'],
                'total_amount' => $checkoutData['total_amount'],
                'shipping_fee' => $checkoutData['shipping_fee'],
                'order_status' => 'pending',
            ]);

            // 注文明細を作成し、在庫を更新
            foreach ($cart as $item) {
                // 在庫の確認（念の為再度）
                $product = Product::find($item['product_id']);
                if (!$product || $product->stock_quantity < $item['quantity']){
                    DB::rollback();
                    return redirect()->route('cart.index')
                        ->with('error', $item['product_name']. 'の在庫が不足しています。');
                }

                // 注文明細を作成
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);

                // 在庫を減らす
                $product->decrement('stock_quantity', $item['quantity']);                
                
            }

                // 注文完了後の処理
                session()->forget('cart');
                session()->forget('checkout_data');

                DB::commit();

                // メール送信
                Mail::to($order->email)->queue(new OrderConfirmation($order));

                // 注文完了画面へリダイレクト
                return redirect()->route('checkout.complete')
                    ->with('order_completed',true)
                    ->with('order_id', $order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            // ログに記録
            \Log::error("Order processing failed: " .$e->getMessage());
            return redirect()->route('cart.index')
                ->with('error', '注文処理中にシステムエラーが発生しました。再度お試しください。');
        }
    }

    // 注文完了画面を表示
    public function complete(Request $request)
    {
        // 'order_completed'フラグがない場合、不正なアクセスとみなしリダイレクト
        if (!$request->session()->has('order_completed')) {
            return redirect()->route('products.index')
                ->with('error','不正なアクセスです。');
        }

        // 注文IDがセッションに残っていれば取得し、ビューに渡す
        $orderId = session('order_id', null);

        return view('checkout.complete',[
            'orderId'=> $orderId,
        ]);
    }
}
