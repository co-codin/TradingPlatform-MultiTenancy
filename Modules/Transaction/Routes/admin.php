<?php

use Illuminate\Support\Facades\Route;
use Modules\Transaction\Http\Controllers\Admin\TransactionsMt5TypeController;
use Modules\Transaction\Http\Controllers\Admin\TransactionStatusController;

Route::apiResource('transaction-statuses', TransactionStatusController::class);
Route::apiResource('transaction-mt5-types', TransactionsMt5TypeController::class);
