<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Desk\Http\Controllers\Admin\DeskController;

Route::group(['middleware' => 'tenant'], function () {
    Route::get('/desks/all', [DeskController::class, 'all'])->name('desks.all');
    Route::apiResource('desks', DeskController::class);
});
