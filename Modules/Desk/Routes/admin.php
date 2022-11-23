<?php

use Illuminate\Support\Facades\Route;
use Modules\Desk\Http\Controllers\Admin\DeskController;

Route::group(['middleware' => 'tenant.set:1'], function () {
    Route::get('/desks/all', [DeskController::class, 'all'])->name('desks.all');
    Route::resource('desks', DeskController::class);
});
