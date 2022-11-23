<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Admin\AuthController;
use Modules\User\Http\Controllers\Admin\Brand\UserBrandController;
use Modules\User\Http\Controllers\Admin\Country\UserCountryController;
use Modules\User\Http\Controllers\Admin\Department\UserDepartmentController;
use Modules\User\Http\Controllers\Admin\Desk\UserDeskController;
use Modules\User\Http\Controllers\Admin\DisplayOption\UserDisplayOptionController;
use Modules\User\Http\Controllers\Admin\ForgetController;
use Modules\User\Http\Controllers\Admin\Language\UserLanguageController;
use Modules\User\Http\Controllers\Admin\SocialAuthController;
use Modules\User\Http\Controllers\Admin\UserController;
use Modules\User\Http\Controllers\TokenAuthController;
use Modules\User\Http\Controllers\TokenController;

Route::group(['prefix' => 'auth', 'as' => 'auth.', 'middleware' => 'web'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/login/{provider}', [SocialAuthController::class, 'redirect'])->name('social.login');
    Route::get('/callback/{provider}', [SocialAuthController::class, 'callback'])->name('social.callback');

    Route::post('/forget-password', [ForgetController::class, 'forget'])->name('password.forget');
    Route::post('/reset-password', [ForgetController::class, 'reset'])->name('password.reset');

    Route::group(['middleware' => 'auth:web'], function () {
        Route::get('/user', [AuthController::class, 'user'])->name('user');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

Route::group(['prefix' => 'token-auth', 'as' => 'token-auth.', 'middleware' => 'api'], function () {
    Route::post('/login', [TokenAuthController::class, 'login'])->name('login');
    Route::post('/logout', [TokenAuthController::class, 'logout'])->middleware('auth:api')->name('logout');
});

Route::group(['prefix' => 'token', 'as' => 'token.', 'middleware' => 'api'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/create', [TokenController::class, 'create'])->name('create');
        Route::delete('/delete', [TokenController::class, 'delete'])->name('delete');
    });
});

Route::group(['middleware' => ['api', 'auth:api']], function () {
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
    Route::put('/workers/{id}/department', [UserDepartmentController::class, 'update'])
        ->name('users.department.update');

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
                'show',
            ],
            'names' => [
                'store' => 'users.display-options.store',
                'update' => 'users.display-options.update',
                'destroy' => 'users.display-options.destroy',
            ],
        ]
    );
});
