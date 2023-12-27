<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductVariantController;

Route::get('', [ProductVariantController::class, 'getAll'])
    ->name('app.product-variant.get-all');



Route::post('', [ProductVariantController::class, 'store'])
    ->name('app.product-variant.create');

Route::middleware(["check-user-product-variant-related"])->group(function () {
    Route::get('/{id}', [ProductVariantController::class, 'getItem'])
    ->where('id', '[0-9]+')
    ->name('app.product-variant.get-one');

    Route::match(['put', 'patch'],'/{id}', [ProductVariantController::class, 'update'])
    ->where('id', '[0-9]+')
    ->name('app.product-variant.update');

    Route::delete('/{id}', [ProductVariantController::class, 'delete'])
    ->where('id', '[0-9]+')
    ->name('app.product-variant.delete');

    Route::post('/{id}/product-option-values', [ProductVariantController::class, 'storeProductOptionValues'])
    ->where('id', '[0-9]+')
    ->name('app.product-variant.product-option-values.store');
});