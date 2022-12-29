<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Transaction\Http\Controllers\TransactionController;

Route::group(['middleware' => 'auth:api-customer'], function () {
    Route::apiResource('transactions', TransactionController::class);
});
