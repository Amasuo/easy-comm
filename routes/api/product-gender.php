<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductGenderController;

Route::get('', [ProductGenderController::class, 'getAll'])
    ->name('app.product-gender.get-all');
    
Route::get('/{id}', [ProductGenderController::class, 'getItem'])
    ->where('id', '[0-9]+')
    ->name('app.product-gender.get-one');

Route::middleware(["check-admin"])->group(function () {
    Route::post('', [ProductGenderController::class, 'store'])
    ->name('app.product-gender.create');

    Route::match(['put', 'patch'],'/{id}', [ProductGenderController::class, 'update'])
    ->where('id', '[0-9]+')
    ->name('app.product-gender.update');

    Route::delete('/{id}', [ProductGenderController::class, 'delete'])
    ->where('id', '[0-9]+')
    ->name('app.product-gender.delete');
});