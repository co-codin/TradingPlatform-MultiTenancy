<?php

use Illuminate\Support\Facades\Route;
use Modules\Token\Http\Controllers\Admin\TokenController;

Route::get('/tokens/all', [TokenController::class, 'all'])->name('tokens.all');
Route::resource('tokens', TokenController::class);
