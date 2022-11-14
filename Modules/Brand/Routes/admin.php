<?php

use Illuminate\Support\Facades\Route;
use Modules\Brand\Http\Controllers\Admin\BrandController;

Route::get('/brands/all', [BrandController::class, 'all'])->name('brands.all');
Route::post('/brands/db/import', [BrandController::class, ''])->name('brands.db.import');
Route::apiResource('brands', BrandController::class);
