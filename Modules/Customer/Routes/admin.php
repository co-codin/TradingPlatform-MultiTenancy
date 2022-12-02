<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\Admin\CustomerController;
use Modules\Customer\Http\Controllers\Admin\CustomerExportController;
use Modules\Customer\Http\Controllers\Admin\CustomerImportController;

Route::group(['middleware' => 'tenant.set:1'], function () {
    Route::group(['prefix' => 'customers'], function () {
        // Customers export
        Route::group(['prefix' => 'export'], function () {
            Route::post('excel', [CustomerExportController::class, 'excel'])->name('customers.export.excel');
            Route::post('csv', [CustomerExportController::class, 'csv'])->name('customers.export.csv');
        });

        // Customers import
        Route::group(['prefix' => 'import'], function () {
            Route::post('excel', [CustomerImportController::class, 'excel'])->name('customers.import.excel');
            Route::post('csv', [CustomerImportController::class, 'csv'])->name('customers.import.csv');
        });

        // Customers CRUD
        Route::get('all', [CustomerController::class, 'all'])->name('customers.all');
        Route::resource('/', CustomerController::class);
    });
});
