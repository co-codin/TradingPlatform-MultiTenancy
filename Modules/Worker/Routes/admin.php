<?php

use Illuminate\Support\Facades\Route;
use Modules\Worker\Http\Controllers\Admin\AuthController;
use Modules\Worker\Http\Controllers\Admin\Desk\WorkerDeskController;

# Desk
Route::put('workers/{worker}/desk', [WorkerDeskController::class, 'update'])->name('worker.desk.update');

# Auth
Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/user', [AuthController::class, 'user'])->name('user');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

# User
