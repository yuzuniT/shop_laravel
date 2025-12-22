<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckCartNotEmpty;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //ミドルウェアの登録
        $middleware->alias(['cart.not-empty'=>CheckCartNotEmpty::class]);
        $middleware->redirectUsersTo(fn (Request $request) => route('products.index'));
        $middleware->trustProxies(at: '*'); // 全てのプロキシを信頼（開発用）
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
