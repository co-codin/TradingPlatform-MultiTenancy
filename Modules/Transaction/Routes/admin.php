<?php

use Illuminate\Support\Facades\Route;
use Modules\Transaction\Http\Controllers\Admin\TransactionExportController;
use Modules\Transaction\Http\Controllers\Admin\TransactionsMethodController;
use Modules\Transaction\Http\Controllers\Admin\TransactionsMt5TypeController;
use Modules\Transaction\Http\Controllers\Admin\TransactionStatusController;
use Modules\Transaction\Http\Controllers\Admin\TransactionsWalletController;
use Modules\Transaction\Http\Controllers\TransactionController;

Route::group(['prefix' => 'transactions'], function () {
    // Export
    Route::group(['prefix' => 'export'], function () {
        Route::get('excel', [TransactionExportController::class, 'excel'])->name('transactions.export.excel');
        Route::get('csv', [TransactionExportController::class, 'csv'])->name('transactions.export.csv');
    });

    Route::apiResource('statuses', TransactionStatusController::class);
    Route::apiResource('mt5-types', TransactionsMt5TypeController::class);
    Route::apiResource('wallets', TransactionsWalletController::class);
    Route::apiResource('methods', TransactionsMethodController::class);
});

Route::apiResource('transactions', TransactionController::class);
