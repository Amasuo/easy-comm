<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrderStatusController;

Route::middleware(["check-store-admin"])->group(function () {
    Route::get('', [OrderStatusController::class, 'getAll'])
    ->name('app.order-status.get-all');
});