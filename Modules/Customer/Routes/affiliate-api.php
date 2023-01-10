<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\Affiliate\RegisterController;

Route::group(['middleware' => 'affiliation-token'], function () {
    Route::post('auth/register', [RegisterController::class, 'register']);
});
