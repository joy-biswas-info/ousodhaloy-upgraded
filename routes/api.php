<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DeviceTokenController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

// ── Public ─────────────────────────────────────────────────────────────────
Route::post('/auth/login', [AuthController::class, 'login']);

// ── Protected (staff/manager only — reopen is further gated to admin
//    inside OrderController::reopen(), see routes/web.php for the same
//    pattern on the web admin side) ───────────────────────────────────────
Route::middleware(['auth:sanctum', 'api.manager'])->group(function () {

    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::post('/device-token', [DeviceTokenController::class, 'store']);
    Route::delete('/device-token', [DeviceTokenController::class, 'destroy']);

    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Orders — specific routes before the {order} wildcard
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
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
