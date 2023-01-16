<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Campaign\Http\Controllers\Admin\CampaignController;

Route::patch('campaign/{campaign}/change-status', [CampaignController::class, 'changeStatus'])->name('campaign.change-status');
Route::apiResource('campaign', CampaignController::class)->except('destroy');
