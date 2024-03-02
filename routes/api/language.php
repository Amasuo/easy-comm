<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LanguageController;

Route::get('', [LanguageController::class, 'getAll'])
    ->name('app.language.get-all');
    
Route::get('/{id}', [LanguageController::class, 'getItem'])
    ->where('id', '[0-9]+')
    ->name('app.language.get-one');

Route::middleware(["check-admin"])->group(function () {
    Route::post('', [LanguageController::class, 'store'])
    ->name('app.language.create');

    Route::match(['put', 'patch'],'/{id}', [LanguageController::class, 'update'])
    ->where('id', '[0-9]+')
    ->name('app.language.update');

    Route::delete('/{id}', [LanguageController::class, 'delete'])
    ->where('id', '[0-9]+')
    ->name('app.language.delete');
});