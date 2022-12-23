<?php

use Illuminate\Support\Facades\Route;
use Modules\Currency\Http\Controllers\Admin\CurrencyController;

Route::apiResource('currencies', CurrencyController::class);
