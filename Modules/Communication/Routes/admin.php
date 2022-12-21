<?php

use Illuminate\Support\Facades\Route;
use Modules\Communication\Http\Controllers\Admin\CommentController;
use Modules\Communication\Http\Controllers\Admin\EmailController;
use Modules\Communication\Http\Controllers\Admin\EmailSendController;
use Modules\Communication\Http\Controllers\Admin\EmailTemplatesController;

Route::group(['middleware' => 'tenant'], function () {
    Route::apiResource('comments', CommentController::class);
    Route::apiResource('emails', EmailController::class);
    Route::apiResource('email-templates', EmailTemplatesController::class);
    Route::post('email-send-to-customer', [EmailSendController::class, 'emailSendToCustomer'])->name('email.send.to.customer');
});
