<?php

declare(strict_types=1);

use Modules\CommunicationProvider\Http\Controllers\CommunicationExtensionController;
use Modules\CommunicationProvider\Http\Controllers\CommunicationProviderController;

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

Route::group(['middleware' => 'tenant', 'as' => 'communication.'], function () {
    Route::resource('providers', CommunicationProviderController::class);
    Route::resource('extensions', CommunicationExtensionController::class);
});
