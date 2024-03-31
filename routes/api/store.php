<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StoreController;

Route::middleware(["check-store-admin"])->group(function () {
    Route::get('', [StoreController::class, 'getAll'])
    ->name('app.store.get-all');

    Route::post('', [StoreController::class, 'store'])
    ->name('app.store.create');

    Route::delete('/{id}', [StoreController::class, 'delete'])
    ->where('id', '[0-9]+')
    ->name('app.store.delete');
});

Route::middleware(["check-user-store-related"])->group(function () {
    Route::get('/{id}', [StoreController::class, 'getItem'])
    ->where('id', '[0-9]+')
    ->name('app.store.get-one');

    Route::match(['put', 'patch'],'/{id}', [StoreController::class, 'update'])
    ->where('id', '[0-9]+')
    ->name('app.store.update');
});