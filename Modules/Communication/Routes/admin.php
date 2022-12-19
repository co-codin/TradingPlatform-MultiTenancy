<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Communication\Http\Controllers\Admin\CommentController;
use Modules\Communication\Http\Controllers\CommunicationExtensionController;
use Modules\Communication\Http\Controllers\CommunicationProviderController;

Route::group(['middleware' => 'tenant'], function () {
    Route::apiResource('comments', CommentController::class);

    Route::group(['prefix' => 'communication', 'as' => 'communication.'], function () {
        Route::get('/providers/all', [CommunicationProviderController::class, 'all'])->name('providers.all');
        Route::apiResource('providers', CommunicationProviderController::class);
        Route::apiResource('extensions', CommunicationExtensionController::class);
    });
});
