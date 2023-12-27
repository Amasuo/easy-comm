<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

Route::get('/current', [UserController::class, 'getCurrent'])
    ->name('app.user.get-current');
    
Route::middleware(['check-store-admin'])->group(function () {
    Route::get('', [UserController::class, 'getAll'])
    ->name('app.user.get-all');

    Route::post('', [UserController::class, 'store'])
    ->name('app.user.create');

    Route::middleware(['check-user-user-related'])->group(function () {
        Route::get('/{id}', [UserController::class, 'getItem'])
        ->where('id', '[0-9]+')
        ->name('app.user.get-one');
    
        Route::match(['put', 'patch'],'/{id}', [UserController::class, 'update'])
        ->where('id', '[0-9]+')
        ->name('app.user.update');
    
        Route::delete('/{id}', [UserController::class, 'delete'])
        ->where('id', '[0-9]+')
        ->name('app.user.delete');
    });
    
});

Route::middleware(['check-admin'])->group(function () {
    Route::post('/{id}/role/{roleId}', [UserController::class, 'assignRole'])
    ->where('id', '[0-9]+')
    ->where('roleId', '[0-9]+')
    ->name('app.user.role.assign');

    Route::delete('/{id}/role/{roleId}', [UserController::class, 'removeRole'])
    ->where('id', '[0-9]+')
    ->where('roleId', '[0-9]+')
    ->name('app.user.role.remove');
});