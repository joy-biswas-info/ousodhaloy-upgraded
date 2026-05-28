<?php
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;

// ── Public ─────────────────────────────────────────────────────────────────
Route::post('/auth/login', [AuthController::class, 'login']);

// ── Protected (staff/manager only) ─────────────────────────────────────────
Route::middleware(['auth:sanctum', 'api.manager'])->group(function () {

    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Dashboard stats
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::post('/orders/{order}/note', [OrderController::class, 'addNote']);
    Route::post('/orders/{order}/steadfast', [OrderController::class, 'pushToSteadfast']);
    Route::post('/orders/{order}/pathao', [OrderController::class, 'pushToPathao']);
    Route::post('/orders/{order}/sync-courier', [OrderController::class, 'syncCourier']);

    // Products / Inventory
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::patch('/products/{product}/stock', [ProductController::class, 'updateStock']);
    Route::get('/products/low-stock', [ProductController::class, 'lowStock']);
    Route::get('/products/expiring', [ProductController::class, 'expiring']);
});