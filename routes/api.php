<?php

use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

// -------------------------
// Test API
// -------------------------
Route::get('/test', fn() => ['status' => 'API working good']);

// -------------------------
// Public API (no auth required)
// -------------------------
Route::get('/foods', [MenuController::class, 'index']);
Route::get('/foods/{id}', [MenuController::class, 'details']);
Route::get('/categories', [MenuController::class, 'categories']);
Route::get('/categories/{id}/foods', [MenuController::class, 'foodsByCategory']);

// -------------------------
// Authentication
// -------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// -------------------------
// Protected API routes (requires token)
// -------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

   Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::post('/cart/update', [CartController::class, 'update']);
    Route::post('/cart/remove', [CartController::class, 'remove']);
    Route::post('/checkout', [CartController::class, 'checkout']);
});
    // Checkout
    Route::post('/checkout', [CartController::class, 'checkout']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
});