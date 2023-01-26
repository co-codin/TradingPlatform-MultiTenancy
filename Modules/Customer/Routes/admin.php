<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\Admin\Auth\PasswordController;
use Modules\Customer\Http\Controllers\Admin\CustomerController;
use Modules\Customer\Http\Controllers\Admin\CustomerExportController;
use Modules\Customer\Http\Controllers\Admin\CustomerImpersonateController;
use Modules\Customer\Http\Controllers\Admin\CustomerImportController;

Route::group(['middleware' => 'tenant'], function () {
    Route::group(['middleware' => ['api', 'auth:api']], function () {
        // Customers export
        Route::group(['prefix' => 'customers/export'], function () {
            Route::get('excel', [CustomerExportController::class, 'excel'])->name('customers.export.excel');
            Route::get('csv', [CustomerExportController::class, 'csv'])->name('customers.export.csv');
        });

        // Customers import
        Route::group(['prefix' => 'customers/import'], function () {
            Route::post('excel', [CustomerImportController::class, 'excel'])->name('customers.import.excel');
            Route::post('csv', [CustomerImportController::class, 'csv'])->name('customers.import.csv');
        });

        // Customers CRUD
        Route::get('customers/all', [CustomerController::class, 'all'])->name('customers.all');
        Route::apiResource('customers', CustomerController::class);

        // Reset password
        Route::post('customers/{customer}/reset-password', [PasswordController::class, 'reset'])
            ->name('customers.password.reset');

        // Сhange password
        Route::post('customers/{customer}/change-password', [PasswordController::class, 'change'])
        ->name('customers.password.change');

        // Impersonation
        Route::post('customers/{id}/impersonate/token', [CustomerImpersonateController::class, 'token'])
            ->name('customers.impersonate.token');
    });

    Route::group(['middleware' => 'web'], function () {
        Route::post('customers/impersonate/session/logout', [CustomerImpersonateController::class, 'sessionLogout'])
            ->name('customers.impersonate.session.logout');
        Route::middleware('auth:web')
            ->post('customers/{id}/impersonate/session', [CustomerImpersonateController::class, 'session'])
            ->name('customers.impersonate.session');
    });
});
