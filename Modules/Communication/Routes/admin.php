<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Communication\Http\Controllers\Admin\CommentController;
use Modules\Communication\Http\Controllers\CommunicationExtensionController;
use Modules\Communication\Http\Controllers\CommunicationProviderController;
use Modules\Communication\Http\Controllers\Admin\CallController;

Route::group(['middleware' => 'tenant'], function () {
    Route::apiResource('comments', CommentController::class);

    Route::group(['prefix' => 'communication', 'as' => 'communication.'], function () {
        Route::get('/providers/all', [CommunicationProviderController::class, 'all'])->name('providers.all');
        Route::get('/extensions/all', [CommunicationExtensionController::class, 'all'])->name('extensions.all');
        Route::apiResource('providers', CommunicationProviderController::class);
        Route::apiResource('extensions', CommunicationExtensionController::class);
        Route::apiResource('call', CallController::class);
    });
});
