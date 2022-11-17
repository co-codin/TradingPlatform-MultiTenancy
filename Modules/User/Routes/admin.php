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
use Modules\User\Http\Controllers\Admin\DisplayOption\UserDisplayOptionController;

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
    Route::put('/workers/{id}/desk', [UserDeskController::class, 'update'])->name('workers.desk.update');

    // Brand
    Route::put('/workers/{id}/brand', [UserBrandController::class, 'update'])->name('users.brand.update');

    // Ban
    Route::patch('/workers/ban', [UserController::class, 'ban'])->name('users.ban');
    Route::patch('/workers/unban', [UserController::class, 'unban'])->name('users.unban');

    // Batch
    Route::patch('/workers/update/batch', [UserController::class, 'updateBatch'])->name('users.batch.update');

    // Country
    Route::put('/workers/{id}/country', [UserCountryController::class, 'update'])->name('users.country.update');

    // Department
    Route::put('/workers/{id}/department', [UserDepartmentController::class, 'update'])->name('users.department.update');

    // Language
    Route::put('/workers/{id}/language', [UserLanguageController::class, 'update'])->name('users.language.update');

    Route::apiResource('workers', UserController::class)->names([
        'index' => 'users.index',
        'show' => 'users.show',
        'store' => 'users.store',
        'update' => 'users.update',
        'destroy' => 'users.destroy',
    ]);

    Route::apiResource(
        'workers.display-options',
        UserDisplayOptionController::class,
        [
            'except' => [
                'index',
                'show'
            ],
            'names' => [
                'store' => 'users.display-options.store',
                'update' => 'users.display-options.update',
                'destroy' => 'users.display-options.destroy',
            ],
        ]
    );
});
