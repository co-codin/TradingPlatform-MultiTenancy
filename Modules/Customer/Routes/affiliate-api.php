<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\Affiliate\RegisterController;

Route::post('auth/register', [RegisterController::class, 'register']);
