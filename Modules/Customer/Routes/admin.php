<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\Admin\CustomerController;
use Modules\Customer\Http\Controllers\Admin\CustomerExportController;
use Modules\Customer\Http\Controllers\Auth\PasswordController;

Route::group([
    'as' => 'admin.',
    'prefix' => 'admin',
    'middleware' => ['api', 'auth:api', 'tenant.set:1']
], function () {
    // Customers export
    Route::group(['prefix' => 'customers/export'], function () {
        Route::post('excel', [CustomerExportController::class, 'excel'])->name('customers.export.excel');
        Route::post('csv', [CustomerExportController::class, 'csv'])->name('customers.export.csv');
    });

    // Customers CRUD
    Route::get('customers/all', [CustomerController::class, 'all'])->name('customers.all');
    Route::resource('customers', CustomerController::class);
});

Route::group(['middleware' => ['web', 'tenant.set:1']], function () {
    Route::post('customers/reset-password', [PasswordController::class, 'reset'])->name('customers.password.reset');
});
