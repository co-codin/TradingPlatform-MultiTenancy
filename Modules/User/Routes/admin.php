<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Admin\AuthController;
use Modules\User\Http\Controllers\Admin\Brand\UserBrandController;
use Modules\User\Http\Controllers\Admin\Country\UserCountryController;
use Modules\User\Http\Controllers\Admin\Department\UserDepartmentController;
use Modules\User\Http\Controllers\Admin\Desk\UserDeskController;
use Modules\User\Http\Controllers\Admin\DisplayOption\UserDisplayOptionController;
use Modules\User\Http\Controllers\Admin\Impersonate\UserImpersonateController;
use Modules\User\Http\Controllers\Admin\Language\UserLanguageController;
use Modules\User\Http\Controllers\Admin\PasswordController;
use Modules\User\Http\Controllers\Admin\Preset\UserPresetController;
use Modules\User\Http\Controllers\Admin\SocialAuthController;
use Modules\User\Http\Controllers\Admin\UserController;
use Modules\User\Http\Controllers\Admin\UserExportController;
use Modules\User\Http\Controllers\TokenAuthController;
use Modules\User\Http\Controllers\TokenController;

Route::group(['prefix' => 'auth', 'as' => 'auth.', 'middleware' => 'web'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/login/{provider}', [SocialAuthController::class, 'redirect'])->name('social.login');
    Route::get('/callback/{provider}', [SocialAuthController::class, 'callback'])->name('social.callback');

    Route::post('/forget-password', [PasswordController::class, 'forget'])->name('password.forget');
    Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.reset');

    Route::group(['middleware' => 'auth:web'], function () {
        Route::get('/user', [AuthController::class, 'user'])->name('user');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

Route::group(['prefix' => 'token-auth', 'as' => 'token-auth.', 'middleware' => 'api'], function () {
    Route::post('/login', [TokenAuthController::class, 'login'])->name('login');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/logout', [TokenAuthController::class, 'logout'])->name('logout');
    });
});

Route::group(['prefix' => 'token', 'as' => 'token.', 'middleware' => ['api', 'auth:api']], function () {
    Route::post('/create', [TokenController::class, 'create'])->name('create');
    Route::delete('/delete', [TokenController::class, 'delete'])->name('delete');
});

Route::group(['middleware' => ['api', 'auth:api']], function () {
    Route::group(['prefix' => 'workers', 'as' => 'users.'], function () {
        // Ban
        Route::patch('/ban', [UserController::class, 'ban'])->name('ban');
        Route::patch('/unban', [UserController::class, 'unban'])->name('unban');

        // Batch
        Route::patch('/update/batch', [UserController::class, 'updateBatch'])->name('batch.update');

        // Brand
        Route::put('/{id}/brand', [UserBrandController::class, 'update'])->name('brand.update');

        // Impersonate
        Route::post('/{id}/impersonate/token', [UserImpersonateController::class, 'token'])->name('impersonate.token');

        // Workers export
        Route::group(['prefix' => 'export'], function () {
            Route::get('excel', [UserExportController::class, 'excel'])->name('export.excel');
            Route::get('csv', [UserExportController::class, 'csv'])->name('export.csv');
        });

        // Country
        Route::put('/{id}/country', [UserCountryController::class, 'update'])->name('country.update');

        // Language
        Route::put('/{id}/language', [UserLanguageController::class, 'update'])->name('language.update');

        Route::group(['middleware' => 'tenant'], function () {
            // Department
            Route::put('/{id}/department', [UserDepartmentController::class, 'update'])->name('department.update');

            // Desk
            Route::put('/{id}/desk', [UserDeskController::class, 'update'])->name('desk.update');
        });
    });

    Route::apiResource('workers', UserController::class)
        ->names([
            'index' => 'users.index',
            'show' => 'users.show',
            'store' => 'users.store',
            'update' => 'users.update',
            'destroy' => 'users.destroy',
        ]);

    Route::apiResource('workers.display-options', UserDisplayOptionController::class)
        ->names([
            'index' => 'users.display-options.index',
            'show' => 'users.display-options.show',
            'store' => 'users.display-options.store',
            'update' => 'users.display-options.update',
            'destroy' => 'users.display-options.destroy',
        ]);

//    Route::apiResource('workers.presets', UserPresetController::class)
//        ->except([
//            'index',
//        ])
//        ->names([
//            'show' => 'users.presets.show',
//            'store' => 'users.presets.store',
//            'update' => 'users.presets.update',
//            'destroy' => 'users.presets.destroy',
//        ]);
});
