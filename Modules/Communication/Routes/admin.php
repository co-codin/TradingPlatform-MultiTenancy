<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Communication\Http\Controllers\Admin\CommentController;
use Modules\Communication\Http\Controllers\CommunicationExtensionController;
use Modules\Communication\Http\Controllers\CommunicationProviderController;
use Modules\Communication\Http\Controllers\Admin\CallController;
use Modules\Communication\Http\Controllers\Admin\EmailController;
use Modules\Communication\Http\Controllers\Admin\EmailSendController;
use Modules\Communication\Http\Controllers\Admin\EmailTemplatesController;
use Modules\Communication\Http\Controllers\Admin\ChatController;

Route::group(['middleware' => 'tenant'], function () {
    Route::apiResource('comments', CommentController::class);

    Route::group(['prefix' => 'communication', 'as' => 'communication.'], function () {
        Route::get('/providers/all', [CommunicationProviderController::class, 'all'])->name('providers.all');
        Route::get('/extensions/all', [CommunicationExtensionController::class, 'all'])->name('extensions.all');
        Route::apiResource('providers', CommunicationProviderController::class);
        Route::apiResource('extensions', CommunicationExtensionController::class);
        Route::apiResource('call', CallController::class);
        Route::apiResource('emails', EmailController::class);
        Route::apiResource('email-templates', EmailTemplatesController::class);
        Route::post('email-send-to-customer', [EmailSendController::class, 'emailSendToCustomer'])->name('email.send.to.customer');
        Route::post('chat-message-history', [ChatController::class, 'history'])->name('chat.history');
        Route::post('chat-message-send', [ChatController::class, 'store'])->name('chat.store');
        Route::post('chat-message-delivery', [ChatController::class, 'delivery'])->name('chat.delivery');
    });
});
