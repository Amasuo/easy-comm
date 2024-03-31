<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoleController;

Route::middleware(["check-store-admin"])->group(function () {
    Route::get('', [RoleController::class, 'getAll'])
    ->name('app.store.get-all');
});