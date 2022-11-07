<?php

use Illuminate\Support\Facades\Route;
use Modules\Worker\Http\Controllers\Admin\AuthController;
use Modules\Worker\Http\Controllers\Admin\Desk\WorkerDeskController;
use Modules\Worker\Http\Controllers\Admin\ForgetController;

# Reset password without admin name prefix
Route::post('admin/auth/reset-password/{token}', [ForgetController::class, 'reset'])->name('password.reset');


# 'admin.*' routes
Route::group(['as' => 'admin.'], function() {
    # Desk
    Route::put('workers/{worker}/desk', [WorkerDeskController::class, 'update'])->name('worker.desk.update');

    # Auth
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/forget-password', [ForgetController::class, 'forget'])->name('forget');
        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::get('/user', [AuthController::class, 'user'])->name('user');
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        });
    });

    # Authentificated routes
    Route::group(['middleware' => 'auth:sanctum'], function () {

    });
});
