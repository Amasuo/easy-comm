<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;

Route::get('', [ProductController::class, 'getAll'])
    ->name('app.product.get-all');

Route::get('/{id}', [ProductController::class, 'getItem'])
    ->where('id', '[0-9]+')
    ->name('app.product.get-one');

Route::post('', [ProductController::class, 'store'])
    ->name('app.product.create');

Route::match(['put', 'patch'],'/{id}', [ProductController::class, 'update'])
    ->where('id', '[0-9]+')
    ->name('app.product.update');

Route::delete('/{id}', [ProductController::class, 'delete'])
    ->where('id', '[0-9]+')
    ->name('app.product.delete');