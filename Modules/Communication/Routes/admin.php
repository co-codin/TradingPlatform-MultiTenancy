<?php

use Illuminate\Support\Facades\Route;
use Modules\Communication\Http\Controllers\Admin\CommentController;

Route::group(['middleware' => 'tenant'], function () {
    Route::apiResource('comments', CommentController::class);
    Route::apiResource('emails', EmailController::class);
});
