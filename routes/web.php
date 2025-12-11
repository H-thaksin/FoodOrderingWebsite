<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController, AuthController, MenuController, CartController, OrderController,
    AdminController, AdminCategoryController, AdminFoodController, AdminOrderController
};

// -------------------------
// Home
// -------------------------
Route::get('/', [HomeController::class, 'index'])->name('home');

// -------------------------
// Authentication
// -------------------------
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Profile (customer)
Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth')->name('profile');

// -------------------------
// Menu / Foods / Categories
// -------------------------
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/foods', [MenuController::class, 'allFoods'])->name('foods.all');
Route::get('/categories', [MenuController::class, 'categories'])->name('categories.index');
Route::get('/categories/{id}/foods', [MenuController::class, 'foods'])->name('menu.foods');
Route::get('/food/{id}', [MenuController::class, 'details'])->name('menu.details');

// -------------------------
// Cart (allow guests, checkout requires auth)
// -------------------------
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/remove', [CartController::class, 'remove'])->name('cart.remove');

    Route::middleware('auth')->group(function () {
        Route::get('/checkout', [CartController::class, 'checkoutForm'])->name('checkout.form');
        Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout.submit');
    });
});

// -------------------------
// Customer Orders
// -------------------------
Route::middleware('auth')->group(function () {
    Route::get('/orders/history', [OrderController::class, 'userOrders'])->name('orders.history');
    Route::resource('orders', OrderController::class)->only(['index', 'show']);

    // Order Tracking
    Route::get('/orders/{order}/track', [OrderController::class, 'track'])
        ->name('orders.track');
     Route::post('/orders/{order}/review', [OrderController::class, 'review'])->name('orders.review');
});

// -------------------------
// Admin Panel
// -------------------------
Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // CRUD for foods & categories
    Route::resource('foods', AdminFoodController::class, ['as' => 'admin']);
    Route::resource('categories', AdminCategoryController::class, ['as' => 'admin']);

    // Orders management
    Route::get('orders', [AdminOrderController::class, 'index'])->name('admin.orders');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::post('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');
    Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('admin.orders.destroy');
    Route::get('orders/{order}/track', [AdminOrderController::class, 'track'])
        ->name('admin.orders.track');

});
