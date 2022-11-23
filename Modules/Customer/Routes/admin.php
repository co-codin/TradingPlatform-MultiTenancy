<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\Admin\CustomerController;


Route::group(['middleware' => 'tenant.set:1'], function () {
    Route::get('/customers/all', [CustomerController::class, 'all'])->name('customers.all');
    Route::resource('customers', CustomerController::class);
});

