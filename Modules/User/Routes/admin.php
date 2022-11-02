<?php

use Modules\User\Http\Controllers\Admin\AuthController;
use Modules\User\Http\Controllers\Admin\UserController;
use Modules\User\Http\Controllers\Admin\ForgetController;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/forget-password', [ForgetController::class, 'login'])->name('forget-password');
    Route::post('/reset-password', [ForgetController::class, 'reset'])->name('reset-password');
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/user', [AuthController::class, 'user'])->name('user');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

Route::middleware('auth:sanctum')->apiResource('users', UserController::class);
