<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CustomerController;

Route::get('', [CustomerController::class, 'getAll'])
    ->name('app.customer.get-all');

Route::post('', [CustomerController::class, 'store'])
    ->name('app.customer.create');

Route::middleware(["check-user-order-related"])->group(function () {
    Route::get('/{id}', [CustomerController::class, 'getItem'])
    ->where('id', '[0-9]+')
    ->name('app.customer.get-one');

    Route::match(['put', 'patch'],'/{id}', [CustomerController::class, 'update'])
    ->where('id', '[0-9]+')
    ->name('app.customer.update');

    Route::delete('/{id}', [CustomerController::class, 'delete'])
    ->where('id', '[0-9]+')
    ->name('app.customer.delete');
});