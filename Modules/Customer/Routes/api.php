<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\AuthController;
use Modules\Customer\Http\Controllers\RegisterController;

Route::group(['prefix' => 'auth', 'as' => 'auth.', 'middleware' => 'web-customer'], function () {
    Route::post('auth/login', [AuthController::class, 'login'])->name('login');
    Route::post('auth/register', [RegisterController::class, 'register'])->name('register');

    Route::group(['middleware' => 'auth:web-customer'], function () {
        Route::get('/me', [AuthController::class, 'me'])->name('me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
