<?php

use Illuminate\Support\Facades\Route;
use Modules\Brand\Http\Controllers\Admin\BrandController;
use Modules\Brand\Http\Controllers\Admin\DB\BrandDBController;

Route::get('/brands/all', [BrandController::class, 'all'])->name('brands.all');
Route::apiResource('brands', BrandController::class);

Route::group(['middleware' => \Modules\Brand\Http\Middleware\BrandDBMiddleware::class], function () {
    Route::post('/brands/{brand}/db/import', [BrandDBController::class, 'import'])->name('brands.db.import');
});
