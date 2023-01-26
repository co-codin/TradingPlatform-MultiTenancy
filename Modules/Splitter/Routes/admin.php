<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Splitter\Http\Controllers\Admin\SplitterController;

Route::post('splitter/update-positions', [SplitterController::class, 'updatePositions'])->name('splitter.update-positions');
Route::apiResource('splitter', SplitterController::class);
