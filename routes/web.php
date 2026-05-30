<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CheckoutController;
use App\Livewire\RegisterComponent;
use App\Livewire\LoginComponent;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/',[ProductController::class, 'index'])
    ->name('products.index');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

Route::prefix('cart')->group(function () {

    // カートが空のときに表示するページ
    Route::get('/empty',[CartController::class,'empty'])
        ->name('cart.empty');

    // カートに商品を追加する
    Route::POST('/add',[CartController::class,'add'])
        ->name('cart.add');

    Route::POST('/update',[CartController::class, 'update'])
        ->name('cart.update');

    Route::POST('/delete',[CartController::class,'delete'])
        ->name('cart.delete');

    // カートの中身を見るページ
    Route::middleware('cart.not-empty')->group(function () {

        Route::get('/',[CartController::class, 'index'])
            ->name('cart.index');
    });
});

Route::prefix('checkout')->group(function () {

    Route::get('/complete',[CheckoutController::class,'complete']) // 完了画面の表示はセッション削除のあと 
        ->name('checkout.complete');
    
    Route::middleware('cart.not-empty')->group(function () {

        Route::get('/delivery_form',[CheckoutController::class, 'delivery_form'])
            ->name('checkout.delivery_form');

        Route::post('/confirm',[CheckoutController::class, 'confirm'])
            ->name('checkout.confirm');

        Route::post('/store',[CheckoutController::class, 'store'])
            ->name('checkout.store');
    });
});

Route::get('/products/{product}',[ProductController::class,'show'])
    ->name('products.show');


Route::prefix('contact')->group(function() {

Route::get('/', [ContactController::class,'create'])
    ->name('contact.create');

Route::post('/store',[ContactController::class, 'store'])
    ->name('contact.store');

Route::post('/confirm',[ContactController::class, 'confirm'])
    ->name('contact.confirm');

Route::get('/complete',[ContactController::class, 'complete'])
    ->name('contact.complete');
});


Route::get('/register',function(){
    return view('register');
})
    ->name('register');

Route::middleware('guest')->group(function () {
    Route::get('/login',function () {
        return view('login');
    })
        ->name('login');
});

Route::post('/logout', function (Request $request) {
    auth()->guard('web')->logout();
    $request->session()->invalidate(); // セッションを無効化
    $request->session()->regenerateToken(); // CSRFトークンを再生成
    return redirect('/')
        ->with('success','ログアウトしました。またのご利用をお待ちしております。');
})->name('logout');

Route::get('/login-component', LoginComponent::class);

Route::get('/register-component', RegisterComponent::class);

// 登録時のEメール認証
Route::prefix('email')->group(function() {
    // 認証待ち画面
    Route::get('/verify', function() {
        return view('auth.verify-email');
    })->middleware('auth')
    ->name('verification.notice');

    // メール内のリンクをクリックしたときの検証処理
    Route::get('/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('products.index')->with('status','メール認証が完了しました！');
    })->middleware(['auth','signed'])
    ->name('verification.verify');

    // 認証メールの再送信処理
    Route::post('/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware(['auth','throttle:6,1'])
    ->name('verification.send');

});

//メールアドレス入力画面
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')
    ->name('password.request');

// パスワード再設定画面
Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')
    ->name('password.reset');

// 管理者ルート
Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function () {
    Route::view('/', 'admin.dashboard')->name('admin.dashboard');
    
    // 商品管理
    Route::view('/products', 'admin.products')->name('admin.products');

    // ユーザー管理
    Route::view('/users', 'admin.users')->name('admin.users');

    // 注文管理
    Route::view('/orders', 'admin.orders')->name('admin.orders');
});