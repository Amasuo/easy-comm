<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductOptionController;

Route::get('', [ProductOptionController::class, 'getAll'])
    ->name('app.product-option.get-all');

Route::post('', [ProductOptionController::class, 'store'])
    ->name('app.product-option.create');

Route::middleware(["check-user-product-variant-related"])->group(function () {
    Route::get('/{id}', [ProductOptionController::class, 'getItem'])
    ->where('id', '[0-9]+')
    ->name('app.product-option.get-one');

    Route::match(['put', 'patch'],'/{id}', [ProductOptionController::class, 'update'])
    ->where('id', '[0-9]+')
    ->name('app.product-option.update');

Route::delete('/{id}', [ProductOptionController::class, 'delete'])
    ->where('id', '[0-9]+')
    ->name('app.product-option.delete');
});