<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RegisterAttemptController;

Route::middleware(["check-admin"])->group(function () {
    Route::get('', [RegisterAttemptController::class, 'getAll'])
    ->name('app.register-attempt.get-all');

    Route::get('/{id}', [RegisterAttemptController::class, 'getItem'])
    ->where('id', '[0-9]+')
    ->name('app.register-attempt.get-one');

    Route::post('/{id}/confirm', [RegisterAttemptController::class, 'confirm'])
    ->where('id', '[0-9]+')
    ->name('app.register-attempt.confirm');
});