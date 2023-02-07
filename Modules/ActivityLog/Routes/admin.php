<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\ActivityLog\Http\Controllers\Admin\ActivityLogController;

Route::apiResource('activity_logs', ActivityLogController::class)->except('create', 'store', 'update', 'destroy');
