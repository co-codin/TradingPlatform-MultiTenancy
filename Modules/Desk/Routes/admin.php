<?php

use Illuminate\Support\Facades\Route;
use Modules\Desk\Http\Controllers\Admin\DeskController;

Route::resource('desks', DeskController::class);
