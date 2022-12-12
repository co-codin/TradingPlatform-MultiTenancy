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

Route::group(['prefix' => 'communication', 'as' => 'communication.'], function () {
    Route::get('/providers/all', [CommunicationProviderController::class, 'all'])->name('providers.all');
    Route::apiResource('providers', CommunicationProviderController::class);
    Route::apiResource('extensions', CommunicationExtensionController::class);
});
