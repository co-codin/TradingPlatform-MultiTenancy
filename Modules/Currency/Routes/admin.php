<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('currencies', \Modules\User\Http\Controllers\Admin\CurrencyController::class);
