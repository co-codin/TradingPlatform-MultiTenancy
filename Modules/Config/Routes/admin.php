<?php

use Illuminate\Support\Facades\Route;
use Modules\Config\Http\Controllers\Admin\ConfigTypeController;
use Modules\Config\Http\Controllers\Admin\ConfigController;

Route::group(['middleware' => 'tenant'], function () {
    Route::apiResource('/configs/types', ConfigTypeController::class, ['as' => 'configs']);
    Route::apiResource('/configs', ConfigController::class);
});
