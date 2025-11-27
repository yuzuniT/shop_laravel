<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Middleware\CheckCartNotEmpty;

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

    // カートの中身を見るページ
    Route::middleware('cart.not-empty')->group(function () {

        Route::get('/',[CartController::class, 'index'])
            ->name('cart.index');

        Route::get('delivery_form',[CheckoutController::class, 'delivery_form'])
            ->name('checkout.delivery_form');

        Route::get('/confirm',[CheckoutController::class, 'confirm'])
            ->name('checkout.confirm');

        Route::get('/complete',[CheckoutController::class,'complete'])
            ->name('checkout.complete');
    });
});

Route::get('/products/{product}',[ProductController::class,'show'])
    ->name('products.show');

Route::get('contact', [ContactController::class,'create'])
    ->name('contact.create');

Route::post('contact',[ContactController::class, 'store'])
    ->name('contact.store');

Route::get('contact_confirm',[ContactController::class, 'confirm'])
    ->name('contact.confirm');