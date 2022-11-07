<?php

use Illuminate\Support\Facades\Route;
use Modules\Worker\Http\Controllers\Admin\AuthController;
use Modules\Worker\Http\Controllers\Admin\Desk\WorkerDeskController;
use Modules\Worker\Http\Controllers\Admin\ForgetController;
use Modules\Worker\Http\Controllers\Admin\Brand\WorkerBrandController;

# Desk
Route::put('workers/{worker}/desk', [WorkerDeskController::class, 'update'])->name('worker.desk.update');

# Brand
Route::put('workers/{worker}/brand', [WorkerBrandController::class, 'update'])->name('worker.brand.update');

# Auth
Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/user', [AuthController::class, 'user'])->name('user');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

# User
