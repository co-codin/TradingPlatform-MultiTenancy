<?php

use Illuminate\Support\Facades\Route;
use Modules\Department\Http\Controllers\Admin\DepartmentController;

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

Route::apiResource('departments', DepartmentController::class);
