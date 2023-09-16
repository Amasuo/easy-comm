<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

Route::get('', [UserController::class, 'getAll'])
    ->name('app.user.get-all');

Route::get('/{id}', [UserController::class, 'getItem'])
    ->where('id', '[0-9]+')
    ->name('app.user.get-one');

Route::post('', [UserController::class, 'store'])
    ->name('app.user.create');

Route::match(['put', 'patch'],'/{id}', [UserController::class, 'update'])
    ->where('id', '[0-9]+')
    ->name('app.user.update');

Route::delete('/{id}', [UserController::class, 'delete'])
    ->where('id', '[0-9]+')
    ->name('app.user.delete');
    
Route::post('/{id}/role/{roleId}', [UserController::class, 'assignRole'])
    ->where('id', '[0-9]+')
    ->where('roleId', '[0-9]+')
    ->name('app.user.role.assign');

Route::delete('/{id}/role/{roleId}', [UserController::class, 'removeRole'])
    ->where('id', '[0-9]+')
    ->where('roleId', '[0-9]+')
    ->name('app.user.role.remove');