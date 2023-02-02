<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Splitter\Http\Controllers\Admin\SplitterChoiceController;
use Modules\Splitter\Http\Controllers\Admin\SplitterController;

Route::post('splitter/update-positions', [SplitterController::class, 'updatePositions'])->name('splitter.update-positions');
Route::put('splitter/splitter-choice/{splitter_choice}/desk', [SplitterChoiceController::class, 'desk'])->name('splitter-choice.desk');
Route::put('splitter/splitter-choice/{splitter_choice}/worker', [SplitterChoiceController::class, 'worker'])->name('splitter-choice.worker');
Route::apiResource('splitter', SplitterController::class);
