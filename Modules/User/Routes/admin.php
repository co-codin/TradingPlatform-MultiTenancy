<?php
use Modules\User\Http\Controllers\Admin\AuthController;
use Modules\User\Http\Controllers\Admin\Department\UserDepartmentController;
use Modules\User\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Admin\ForgetController;
use Modules\User\Http\Controllers\Admin\Desk\UserDeskController;
use Modules\User\Http\Controllers\Admin\Brand\UserBrandController;
use Modules\User\Http\Controllers\Admin\Language\UserLanguageController;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/forget-password', [ForgetController::class, 'forget'])->name('forget');
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/user', [AuthController::class, 'user'])->name('user');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    # Desk
    Route::put('users/{user}/desk', [UserDeskController::class, 'update'])->name('users.desk.update');

    # Brand
    Route::put('users/{user}/brand', [UserBrandController::class, 'update'])->name('users.brand.update');

    # Language
    Route::put('/users/{user}/language', [UserLanguageController::class, 'update'])->name('users.language.update');


    # Ban
    Route::patch('/users/{id}/ban', [UserController::class, ['ban']])->name('users.ban');
    Route::patch('/users/{id}/unban', [UserController::class, ['unban']])->name('users.unban');

    Route::apiResource('users', UserController::class);

});
