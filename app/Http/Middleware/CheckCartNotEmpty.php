<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class CheckCartNotEmpty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // セッションからカートを取得
        // カートが空の場合、空配列を取得する
        $cart = $request->session()->get('cart',[]);

        // カートが空（または存在しない）場合
        if(empty($cart)){
            return redirect()->route('cart.empty') // カートが空のときリダイレクト
                ->with('error', 'カートに商品が入っていません。'); 
        }

        return $next($request); // カートに商品があれば次へ
    }
}
