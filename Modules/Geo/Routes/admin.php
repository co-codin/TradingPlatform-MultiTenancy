<?php

use Illuminate\Support\Facades\Route;
use Modules\Geo\Http\Controllers\Admin\CountryController;

Route::get('countries/all', [CountryController::class, 'all']);
Route::apiResource('countries', CountryController::class);
