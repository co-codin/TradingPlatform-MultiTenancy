<?php

use Illuminate\Support\Facades\Route;
use Modules\Communication\Http\Controllers\Admin\CommentController;

Route::group(['middleware' => 'tenant.set:1'], function () {
    Route::apiResource('comments', CommentController::class);
});
