<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\AuthControllers;
use App\Http\Controllers\Api\DeviceTokenController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

// ── Public ─────────────────────────────────────────────────────────────────
// This app's 'api' middleware group (see bootstrap/app.php) carries no
// throttle at all — Laravel's usual default isn't actually registered here.
// That's a tolerable gap for the routes below (Sanctum + api.manager already
// require a valid staff token first), but login is the one route anyone
// unauthenticated can hit, and without this it had zero brute-force
// protection. Matches the web login route's own throttle:5,1.
Route::post('/auth/login', [AuthControllers::class, 'login'])->middleware('throttle:5,1');

// ── Protected (staff/manager only — reopen is further gated to admin
//    inside OrderController::reopen(), see routes/web.php for the same
//    pattern on the web admin side) ───────────────────────────────────────
Route::middleware(['auth:sanctum', 'api.manager'])->group(function () {

    Route::post('/auth/logout', [AuthControllers::class, 'logout']);
    Route::get('/auth/me', [AuthControllers::class, 'me']);

    Route::post('/device-token', [DeviceTokenController::class, 'store']);
    Route::delete('/device-token', [DeviceTokenController::class, 'destroy']);

    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Orders — specific routes before the {order} wildcard
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::get('/orders/{order}/pathao-success-rate', [OrderController::class, 'pathaoSuccessRate']);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::post('/orders/{order}/note', [OrderController::class, 'addNote']);
    Route::post('/orders/{order}/steadfast', [OrderController::class, 'pushToSteadfast']);
    Route::post('/orders/{order}/pathao', [OrderController::class, 'pushToPathao']);
    Route::post('/orders/{order}/sync-courier', [OrderController::class, 'syncCourier']);
    Route::post('/orders/{order}/reopen', [OrderController::class, 'reopen']);

    // Products / Inventory — specific routes before the {product} wildcard
    Route::get('/products/low-stock', [ProductController::class, 'lowStock']);
    Route::get('/products/expiring', [ProductController::class, 'expiring']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::patch('/products/{product}/stock', [ProductController::class, 'updateStock']);
});
