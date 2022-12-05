<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\Admin\CustomerController;
use Modules\Customer\Http\Controllers\Admin\CustomerExportController;
use Modules\Customer\Http\Controllers\Admin\CustomerImpersonateController;
use Modules\Customer\Http\Controllers\Admin\CustomerImportController;
use Modules\Customer\Http\Controllers\Auth\PasswordController;

Route::group([
    'middleware' => ['tenant.set:1'],
], function () {
    // Customers export
    Route::group(['prefix' => 'customers/export'], function () {
        Route::post('excel', [CustomerExportController::class, 'excel'])->name('customers.export.excel');
        Route::post('csv', [CustomerExportController::class, 'csv'])->name('customers.export.csv');
    });

    // Customers import
    Route::group(['prefix' => 'customers/import'], function () {
        Route::post('excel', [CustomerImportController::class, 'excel'])->name('customers.import.excel');
        Route::post('csv', [CustomerImportController::class, 'csv'])->name('customers.import.csv');
    });

    // Customers CRUD
    Route::get('customers/all', [CustomerController::class, 'all'])->name('customers.all');
    Route::resource('customers', CustomerController::class);

    // Impersonation
    Route::post('customers/{customer}/impersonate', [CustomerImpersonateController::class, 'impersonate'])->name('customers.impersonate');

    // Reset password
    Route::post('customers/reset-password', [PasswordController::class, 'reset'])->name('customers.password.reset');
});
