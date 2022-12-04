<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Sale\Http\Controllers\Admin\SaleStatusController;

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

Route::group(['middleware' => 'tenant.set:1'], function () {
    Route::apiResource('sale-statuses', SaleStatusController::class);
});
