<?php

use Illuminate\Support\Facades\Route;
use Modules\Transaction\Http\Controllers\Admin\TransactionStatusController;

Route::group(['middleware' => ['api', 'auth:api']], function () {
    Route::apiResource('transaction-statuses', TransactionStatusController::class);
});
