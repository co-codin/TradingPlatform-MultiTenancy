<?php

use Illuminate\Support\Facades\Route;
use Modules\Geo\Http\Controllers\Admin\CountryController;

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

Route::group(['middleware' => 'tenant'], function () {
    Route::apiResource('countries', CountryController::class);
});
