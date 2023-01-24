<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Communication\Http\Controllers\Admin\CallController;
use Modules\Communication\Http\Controllers\Admin\ChatController;
use Modules\Communication\Http\Controllers\Admin\CommentController;
use Modules\Communication\Http\Controllers\Admin\EmailController;
use Modules\Communication\Http\Controllers\Admin\EmailSendController;
use Modules\Communication\Http\Controllers\Admin\EmailTemplatesController;
use Modules\Communication\Http\Controllers\Admin\NotificationController;
use Modules\Communication\Http\Controllers\Admin\NotificationTemplateController;
use Modules\Communication\Http\Controllers\CommunicationExtensionController;
use Modules\Communication\Http\Controllers\CommunicationProviderController;

Route::group(['middleware' => 'tenant'], function () {
    Route::apiResource('comments', CommentController::class);

    Route::group(['prefix' => 'communication', 'as' => 'communication.'], function () {
        Route::get('providers/all', [CommunicationProviderController::class, 'all'])->name('providers.all');
        Route::apiResource('providers', CommunicationProviderController::class);

        Route::get('extensions/all', [CommunicationExtensionController::class, 'all'])->name('extensions.all');
        Route::put('extensions/bulk-replace-by-worker', [CommunicationExtensionController::class, 'bulkReplaceByUser'])
            ->name('extensions.bulk-replace-by-worker');
        Route::apiResource('extensions', CommunicationExtensionController::class);

        Route::apiResource('call', CallController::class);

        Route::apiResource('emails', EmailController::class);
        Route::apiResource('email-templates', EmailTemplatesController::class);
        Route::post('email-send', [EmailSendController::class, 'emailSend'])
            ->name('email.send');

        Route::post('notifications/send', [NotificationController::class, 'send'])
            ->name('notifications.send');
        Route::apiResource('notifications', NotificationController::class);

        Route::get('notification-templates/all', [NotificationTemplateController::class, 'all'])
            ->name('notification-templates.all');
        Route::post('notification-templates/send', [NotificationTemplateController::class, 'send'])
            ->name('notification-templates.send');
        Route::apiResource('notification-templates', NotificationTemplateController::class);

        Route::post('chat-message-history', [ChatController::class, 'history'])->name('chat.history');
        Route::post('chat-message-send', [ChatController::class, 'store'])->name('chat.store');
        Route::post('chat-message-delivery', [ChatController::class, 'delivery'])->name('chat.delivery');
    });
});
