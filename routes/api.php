<?php

use App\Helpers\DashboardHelper;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
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

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

/** Routes under auth middleware (routes that need authentication) */
Route::middleware('auth:api')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'getAll']);
    
    Route::match(['put', 'patch'],'/account', [AuthController::class, 'update'])
        ->name('app.user.account.update');

    /** register attempt routes */
    Route::name('app.register-attempt')
        ->prefix('register-attempt')
        ->group(__DIR__ . '/api/register-attempt.php');

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

    /** product routes */
    Route::name('app.product')
    ->prefix('product')
    ->group(__DIR__ . '/api/product.php');

    /** product option routes */
    Route::name('app.product-option')
    ->prefix('product-option')
    ->group(__DIR__ . '/api/product-option.php');

    /** product option value routes */
    Route::name('app.product-option-value')
    ->prefix('product-option-value')
    ->group(__DIR__ . '/api/product-option-value.php');

    /** product variant routes */
    Route::name('app.product-variant')
    ->prefix('product-variant')
    ->group(__DIR__ . '/api/product-variant.php');

    /** order routes */
    Route::name('app.order')
    ->prefix('order')
    ->group(__DIR__ . '/api/order.php');

    /**product gender routes */
    Route::name('app.product-gender')
    ->prefix('product-gender')
    ->group(__DIR__ . '/api/product-gender.php');

    /** language routes */
    Route::name('app.language')
    ->prefix('language')
    ->group(__DIR__ . '/api/language.php');

    /** role routes */
    Route::name('app.role')
    ->prefix('role')
    ->group(__DIR__ . '/api/role.php');

    /** order status routes */
    Route::name('app.order-status')
    ->prefix('order-status')
    ->group(__DIR__ . '/api/order-status.php');
});
