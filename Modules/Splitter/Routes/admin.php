<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Splitter\Http\Controllers\Admin\SplitterController;

Route::group(['middleware' => 'tenant'], function () {
    Route::apiResource('splitter', SplitterController::class);
});
