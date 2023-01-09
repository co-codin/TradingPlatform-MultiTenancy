<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\Affiliate\CustomerController;
use Modules\Customer\Http\Controllers\Affiliate\RegisterController;

Route::post('auth/register', [RegisterController::class, 'register']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('customers', CustomerController::class)->only(['index', 'show']);
});
