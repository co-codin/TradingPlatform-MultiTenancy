<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Role\Http\Controllers\Admin\PermissionColumnController;
use Modules\Role\Http\Controllers\Admin\PermissionController;
use Modules\Role\Http\Controllers\Admin\RoleController;

Route::group(['middleware' => 'tenant.set'], function () {
    Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions']);
    Route::apiResource('roles', RoleController::class);

    Route::get('permissions/all', [PermissionController::class, 'all']);
    Route::get('permissions/count', [PermissionController::class, 'count']);
    Route::apiResource('permissions', PermissionController::class)->only('index', 'show');

    // Columns
    Route::put('/permissions/{id}/columns', [PermissionColumnController::class, 'update'])->name('permissions.columns.update');
});
