<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Language\Http\Controllers\Admin\LanguageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'tenant'], function () {
    Route::apiResource('languages', LanguageController::class);
});
