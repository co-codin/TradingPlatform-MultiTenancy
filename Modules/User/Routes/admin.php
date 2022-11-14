<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Admin\AuthController;
use Modules\User\Http\Controllers\Admin\Brand\UserBrandController;
use Modules\User\Http\Controllers\Admin\Country\UserCountryController;
use Modules\User\Http\Controllers\Admin\Department\UserDepartmentController;
use Modules\User\Http\Controllers\Admin\Desk\UserDeskController;
use Modules\User\Http\Controllers\Admin\ForgetController;
use Modules\User\Http\Controllers\Admin\Language\UserLanguageController;
use Modules\User\Http\Controllers\Admin\UserController;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/forget-password', [ForgetController::class, 'forget'])->name('forget');
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/user', [AuthController::class, 'user'])->name('user');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    // Desk
    Route::put('/users/{id}/desk', [UserDeskController::class, 'update'])->name('user.desk.update');

    // Brand
    Route::put('/users/{id}/brand', [UserBrandController::class, 'update'])->name('user.brand.update');

    // Language
    Route::put('/users/{id}/language', [UserLanguageController::class, 'update'])->name('user.language.update');

    // Country
    Route::put('/users/{id}/country', [UserCountryController::class, 'update'])->name('user.language.update');

    // Department
    Route::put('/users/{id}/department', [UserDepartmentController::class, 'update'])->name('user.language.update');


    # Ban
    Route::patch('/users/{id}/ban', [UserController::class, ['ban']])->name('users.ban');
    Route::patch('/users/{id}/unban', [UserController::class, ['unban']])->name('users.unban');

    Route::apiResource('users', UserController::class);
});
