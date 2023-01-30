<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Role\Http\Controllers\Admin\ActionController;
use Modules\Role\Http\Controllers\Admin\ColumnController;
use Modules\Role\Http\Controllers\Admin\ModelController;
use Modules\Role\Http\Controllers\Admin\Permission\RolePermissionController;
use Modules\Role\Http\Controllers\Admin\PermissionController;
use Modules\Role\Http\Controllers\Admin\RoleController;
use Modules\Role\Http\Controllers\Admin\RoleModelController;

Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions']);
Route::get('roles/all', [RoleController::class, 'all'])->name('roles.all');

Route::put('roles/{id}/permission/{permissionId}/columns', [RolePermissionController::class, 'syncColumns'])
    ->name('roles.permission.columns');
Route::get('roles/{id}/permission/{permissionId}/columns', [RolePermissionController::class, 'getColumns'])
    ->name('roles.permission.columns');

Route::get('roles/{id}/models', [RoleModelController::class, 'index'])->name('roles.models.index');
Route::get('roles/{id}/models/{modelId}', [RoleModelController::class, 'show'])->name('roles.models.show');

Route::apiResource('roles', RoleController::class);

Route::get('permissions/all', [PermissionController::class, 'all']);
Route::get('permissions/count', [PermissionController::class, 'count']);

Route::get('permissions/columns/all', [ColumnController::class, 'all'])->name('permissions.columns.all');

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

Route::get('actions/all', [ActionController::class, 'all'])->name('actions.all');
Route::get('models/all', [ModelController::class, 'all'])->name('models.all');
