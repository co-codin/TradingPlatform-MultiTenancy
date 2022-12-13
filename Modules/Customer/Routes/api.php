<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\LoginController;
use Modules\Customer\Http\Controllers\RegisterController;

Route::group(['prefix' => 'auth', 'as' => 'auth.', 'middleware' => ['web', 'tenant']], function () {
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('register', [RegisterController::class, 'register'])->name('register');
});
