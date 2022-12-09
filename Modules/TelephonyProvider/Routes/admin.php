<?php

declare(strict_types=1);

use Modules\TelephonyProvider\Http\Controllers\TelephonyExtensionController;
use Modules\TelephonyProvider\Http\Controllers\TelephonyProviderController;

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

Route::group(['middleware' => 'tenant', 'as' => 'telephony.'], function () {
    Route::resource('providers', TelephonyProviderController::class);
    Route::resource('extensions', TelephonyExtensionController::class);
});
