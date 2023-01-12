<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Campaign\Http\Controllers\Admin\CampaignController;

Route::group(['middleware' => 'tenant'], function () {
    Route::apiResource('campaign', CampaignController::class);
});
