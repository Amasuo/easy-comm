<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DeliveryCompanyController;

Route::get('', [DeliveryCompanyController::class, 'getAll'])
    ->name('app.delivery-company.get-all');

Route::get('/{id}', [DeliveryCompanyController::class, 'getItem'])
    ->where('id', '[0-9]+')
    ->name('app.delivery-company.get-one');

Route::post('', [DeliveryCompanyController::class, 'store'])
    ->name('app.delivery-company.create');

Route::match(['put', 'patch'],'/{id}', [DeliveryCompanyController::class, 'update'])
    ->where('id', '[0-9]+')
    ->name('app.delivery-company.update');

Route::delete('/{id}', [DeliveryCompanyController::class, 'delete'])
    ->where('id', '[0-9]+')
    ->name('app.delivery-company.delete');