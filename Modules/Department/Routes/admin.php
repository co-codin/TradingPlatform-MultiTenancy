<?php

use Illuminate\Support\Facades\Route;
use Modules\Department\Http\Controllers\Admin\DepartmentController;
use Modules\Department\Http\Controllers\Admin\DepartmentUserController;

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
    Route::get('departments/workers', [DepartmentUserController::class, 'allByDepartments'])->name('departments.users.allByDepartments');
    Route::get('departments/all', [DepartmentController::class, 'all']);
    Route::apiResource('departments', DepartmentController::class);
});
