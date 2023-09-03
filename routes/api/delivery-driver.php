<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DeliveryDriverController;

Route::get('', [DeliveryDriverController::class, 'getAll'])
    ->name('app.delivery-driver.get-all');

Route::get('/{id}', [DeliveryDriverController::class, 'getItem'])
    ->where('id', '[0-9]+')
    ->name('app.delivery-driver.get-one');

Route::post('', [DeliveryDriverController::class, 'store'])
    ->name('app.delivery-driver.create');

Route::match(['put', 'patch'],'/{id}', [DeliveryDriverController::class, 'update'])
    ->where('id', '[0-9]+')
    ->name('app.delivery-driver.update');

Route::delete('/{id}', [DeliveryDriverController::class, 'delete'])
    ->where('id', '[0-9]+')
    ->name('app.delivery-driver.delete');