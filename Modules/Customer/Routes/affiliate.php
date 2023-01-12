<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\Affiliate\CustomerController;
use Modules\Customer\Http\Controllers\Affiliate\FTDCustomerController;

Route::group(['middleware' => 'affiliation-token'], function () {
    Route::apiResource('customers', CustomerController::class)->only(['index', 'store']);
    Route::apiResource('ftd-customers', FTDCustomerController::class)->only(['index']);
});
