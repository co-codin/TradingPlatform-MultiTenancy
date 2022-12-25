<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\AuthController;
use Modules\Customer\Http\Controllers\CustomerChatController;
use Modules\Customer\Http\Controllers\PasswordController;
use Modules\Customer\Http\Controllers\RegisterController;
use Modules\Customer\Http\Controllers\TokenAuthController;

Route::group(['prefix' => 'auth', 'as' => 'auth.', 'middleware' => ['web', 'tenant']], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [RegisterController::class, 'register'])->name('register');

    Route::post('forget-password', [PasswordController::class, 'forget'])->name('password.forget');
    Route::post('reset-password', [PasswordController::class, 'reset'])->name('password.reset');

    Route::group(['middleware' => 'auth:web-customer'], function () {
        Route::get('/me', [AuthController::class, 'me'])->name('me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

Route::group(['prefix' => 'token-auth', 'as' => 'token-auth.', 'middleware' => ['api', 'tenant']], function () {
    Route::post('login', [TokenAuthController::class, 'login'])->name('login');
    Route::group(['middleware' => 'auth:api-customer'], function () {
        Route::post('logout', [TokenAuthController::class, 'logout'])->name('logout');
    });
});

Route::group(['middleware' => 'auth:api-customer'], function () {
    Route::post('chat-message-history', [CustomerChatController::class, 'history'])->name('chat.history');
    Route::post('chat-message-send', [CustomerChatController::class, 'store'])->name('chat.store');
    Route::post('chat-message-delivery', [CustomerChatController::class, 'delivery'])->name('chat.delivery');
});
