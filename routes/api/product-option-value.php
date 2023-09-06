<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductOptionValueController;

Route::get('', [ProductOptionValueController::class, 'getAll'])
    ->name('app.product-option-value.get-all');

Route::get('/{id}', [ProductOptionValueController::class, 'getItem'])
    ->where('id', '[0-9]+')
    ->name('app.product-option-value.get-one');

Route::post('', [ProductOptionValueController::class, 'store'])
    ->name('app.product-option-value.create');

Route::match(['put', 'patch'],'/{id}', [ProductOptionValueController::class, 'update'])
    ->where('id', '[0-9]+')
    ->name('app.product-option-value.update');

Route::delete('/{id}', [ProductOptionValueController::class, 'delete'])
    ->where('id', '[0-9]+')
    ->name('app.product-option-value.delete');