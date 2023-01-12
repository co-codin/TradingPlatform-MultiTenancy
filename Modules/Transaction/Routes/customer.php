<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Transaction\Http\Controllers\Customer\TransactionController;

Route::group(['middleware' => ['auth:api-customer', 'tenant']], function () {
    Route::apiResource('transactions', TransactionController::class);
});
