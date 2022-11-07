<?php

use Illuminate\Support\Facades\Route;
use Modules\Worker\Http\Controllers\Admin\AuthController;
use Modules\Worker\Http\Controllers\Admin\Desk\WorkerDeskController;
use Modules\Worker\Http\Controllers\Admin\ForgetController;

# Desk
Route::put('workers/{worker}/desk', [WorkerDeskController::class, 'update'])->name('worker.desk.update');

# Auth
Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/forget-password', [ForgetController::class, 'forget'])->name('forget');
    Route::post('/reset-password/{token}', [ForgetController::class, 'reset'])->name('reset');
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/user', [AuthController::class, 'user'])->name('user');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

# User
