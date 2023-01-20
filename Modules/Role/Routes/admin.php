<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Role\Http\Controllers\Admin\ColumnController;
use Modules\Role\Http\Controllers\Admin\PermissionColumnController;
use Modules\Role\Http\Controllers\Admin\PermissionController;
use Modules\Role\Http\Controllers\Admin\RoleController;

Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions']);
Route::get('roles/all', [RoleController::class, 'all'])->name('roles.all');
Route::apiResource('roles', RoleController::class);

Route::get('permissions/all', [PermissionController::class, 'all']);
Route::get('permissions/count', [PermissionController::class, 'count']);

Route::apiResource('permissions/columns', ColumnController::class)->names([
    'index' => 'permissions.columns.index',
    'show' => 'permissions.columns.show',
    'store' => 'permissions.columns.store',
    'update' => 'permissions.columns.update',
    'destroy' => 'permissions.columns.destroy',
]);

Route::apiResource('permissions', PermissionController::class)->only('index', 'show')->names([
    'index' => 'permissions.index',
    'show' => 'permissions.show',
    'store' => 'permissions.store',
    'update' => 'permissions.update',
    'destroy' => 'permissions.destroy',
]);

// Columns
Route::put('/permissions/{id}/columns', [PermissionColumnController::class, 'update'])
    ->name('permissions.columns.update');
