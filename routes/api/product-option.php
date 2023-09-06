<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductOptionController;

Route::get('', [ProductOptionController::class, 'getAll'])
    ->name('app.product-option.get-all');

Route::get('/{id}', [ProductOptionController::class, 'getItem'])
    ->where('id', '[0-9]+')
    ->name('app.product-option.get-one');

Route::post('', [ProductOptionController::class, 'store'])
    ->name('app.product-option.create');

Route::match(['put', 'patch'],'/{id}', [ProductOptionController::class, 'update'])
    ->where('id', '[0-9]+')
    ->name('app.product-option.update');

Route::delete('/{id}', [ProductOptionController::class, 'delete'])
    ->where('id', '[0-9]+')
    ->name('app.product-option.delete');