<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrderController;

Route::get('', [OrderController::class, 'getAll'])
    ->name('app.order.get-all');

Route::get('/{id}', [OrderController::class, 'getItem'])
    ->where('id', '[0-9]+')
    ->name('app.order.get-one');

Route::post('', [OrderController::class, 'store'])
    ->name('app.order.create');

Route::match(['put', 'patch'],'/{id}', [OrderController::class, 'update'])
    ->where('id', '[0-9]+')
    ->name('app.order.update');

Route::delete('/{id}', [OrderController::class, 'delete'])
    ->where('id', '[0-9]+')
    ->name('app.order.delete');