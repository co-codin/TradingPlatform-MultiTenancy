<?php

use Illuminate\Support\Facades\Route;
use Modules\Worker\Http\Controllers\Admin\Desk\WorkerDeskController;

# Desk
Route::put('workers/{worker}/desk', [WorkerDeskController::class, 'update'])->name('worker.desk.update');

