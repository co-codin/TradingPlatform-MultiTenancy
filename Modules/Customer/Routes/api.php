<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\LoginController;
use Modules\Customer\Http\Controllers\RegisterController;

Route::group(['prefix' => 'auth', 'as' => 'auth.', 'middleware' => 'web-customer'], function () {
    Route::post('auth/login', [LoginController::class, 'login'])->name('auth.login');
    Route::post('auth/register', [RegisterController::class, 'register'])->name('auth.register');
});
