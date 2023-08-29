<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StoreController;

Route::get('', [StoreController::class, 'getAll'])
    ->name('app.store.get-all');

Route::get('/{id}', [StoreController::class, 'getItem'])
    ->where('id', '[0-9]+')
    ->name('app.store.get-one');

Route::post('', [StoreController::class, 'store'])
    ->name('app.store.create');

Route::match(['put', 'patch'],'/{id}', [StoreController::class, 'update'])
    ->where('id', '[0-9]+')
    ->name('app.store.update');

Route::delete('/{id}', [StoreController::class, 'delete'])
    ->where('id', '[0-9]+')
    ->name('app.store.delete');