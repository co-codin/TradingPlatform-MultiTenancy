<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\LoginController;
use Modules\Customer\Http\Controllers\RegisterController;

Route::post('auth/login', [LoginController::class, 'login'])->name('auth.login');
Route::post('auth/register', [RegisterController::class, 'register'])->name('auth.register');
