<?php

use Illuminate\Support\Facades\Route;
use Modules\Transaction\Http\Controllers\Admin\TransactionsMt5TypeController;
use Modules\Transaction\Http\Controllers\Admin\TransactionStatusController;
use Modules\Transaction\Http\Controllers\Admin\TransactionsWalletController;

Route::apiResource('transaction-statuses', TransactionStatusController::class);
Route::apiResource('transaction-mt5-types', TransactionsMt5TypeController::class);
Route::apiResource('transaction-wallets', TransactionsWalletController::class);
