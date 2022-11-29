<?php

use Illuminate\Support\Facades\Route;
use Modules\Sale\Http\Controllers\Admin\SaleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'tenant.set'], function () {
    Route::apiResource('sale', SaleController::class);
});

