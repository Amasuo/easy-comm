<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

/** Routes under auth middleware (routes that need authentication) */
Route::middleware('auth:api')->group(function () {
    /** user routes */
    Route::name('app.user')
        ->prefix('user')
        ->group(__DIR__ . '/api/user.php');

    /** store routes */
    Route::name('app.store')
        ->prefix('store')
        ->group(__DIR__ . '/api/store.php');

    /** delivery company routes */
    Route::name('app.delivery-company')
    ->prefix('delivery-company')
    ->group(__DIR__ . '/api/delivery-company.php');

    /** delivery driver routes */
    Route::name('app.delivery-driver')
    ->prefix('delivery-driver')
    ->group(__DIR__ . '/api/delivery-driver.php');

    /** customer routes */
    Route::name('app.customer')
    ->prefix('customer')
    ->group(__DIR__ . '/api/customer.php');
});
