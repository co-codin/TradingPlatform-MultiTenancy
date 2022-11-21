<?php

use Illuminate\Support\Facades\Route;
use Modules\Config\Http\Controllers\Admin\ConfigTypeController;
use Modules\Config\Http\Controllers\Admin\ConfigController;

Route::apiResource('/config-types', ConfigTypeController::class);
Route::apiResource('/configs', ConfigController::class);
