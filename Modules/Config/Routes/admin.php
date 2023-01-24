<?php

use Illuminate\Support\Facades\Route;
use Modules\Config\Http\Controllers\Admin\ConfigController;
use Modules\Config\Http\Controllers\Admin\ConfigTypeController;

Route::group(['middleware' => 'tenant'], function () {
    Route::get('/configs/types/all', [ConfigTypeController::class, 'all'])->name('configs.types.all');
    Route::apiResource('/configs/types', ConfigTypeController::class, ['as' => 'configs']);
    Route::apiResource('/configs', ConfigController::class);
});
