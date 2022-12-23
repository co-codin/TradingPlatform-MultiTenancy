<?php

use Illuminate\Support\Facades\Route;
use Modules\Transaction\Http\Controllers\Admin\TransactionStatusController;

Route::apiResource('transaction-statuses', TransactionStatusController::class);
