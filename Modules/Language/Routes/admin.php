<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Language\Http\Controllers\Admin\LanguageController;

Route::get('languages/all', [LanguageController::class, 'all']);
Route::apiResource('languages', LanguageController::class);
