<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\SellerOrderController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OtpController;

// --------------------------------------------------
// PUBLIC routes — no login required
// --------------------------------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::get('/products',      [ProductController::class, 'publicIndex']);
Route::get('/products/{id}', [ProductController::class, 'publicShow']);
Route::get('/products/{id}/reviews', [ReviewController::class, 'index']);
Route::post('/otp/send',   [OtpController::class, 'send']);
Route::post('/otp/verify', [OtpController::class, 'verify']);
// --------------------------------------------------
// PROTECTED routes — must be logged in
// --------------------------------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Addresses
    Route::get('/addresses',         [AddressController::class, 'index']);
    Route::post('/addresses',        [AddressController::class, 'store']);
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy']);

    // Seller - Shop
    Route::post('/seller/shop', [ShopController::class, 'store']);
    Route::get('/seller/shop',  [ShopController::class, 'show']);
    Route::put('/seller/shop',  [ShopController::class, 'update']);

    // Seller - Products
    Route::get('/seller/products',         [ProductController::class, 'index']);
    Route::post('/seller/products',        [ProductController::class, 'store']);
    Route::put('/seller/products/{id}',    [ProductController::class, 'update']);
    Route::delete('/seller/products/{id}', [ProductController::class, 'destroy']);

    // Seller - Orders
    Route::get('/seller/orders',              [SellerOrderController::class, 'index']);
    Route::put('/seller/orders/{id}/status',  [SellerOrderController::class, 'updateStatus']);

    // Buyer - Cart
    Route::get('/cart',         [CartController::class, 'index']);
    Route::post('/cart',        [CartController::class, 'store']);
    Route::put('/cart/{id}',    [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);

    // Buyer - Orders
    Route::post('/checkout',   [OrderController::class, 'checkout']);
    Route::get('/orders',      [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    // Admin - Assign driver
    Route::post('/admin/orders/{orderId}/assign-driver', [DeliveryController::class, 'assign']);

    // Driver - Deliveries
    Route::get('/driver/deliveries',            [DeliveryController::class, 'myDeliveries']);
    Route::put('/driver/deliveries/{id}/status', [DeliveryController::class, 'updateStatus']);


    // Buyer - Reviews
    Route::post('/reviews', [ReviewController::class, 'store']);

    // Admin
    Route::get('/admin/users',      [AdminController::class, 'users']);
    Route::get('/admin/orders',     [AdminController::class, 'orders']);
    Route::get('/admin/shops',      [AdminController::class, 'shops']);
    Route::get('/admin/stats',      [AdminController::class, 'stats']);
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser']);





});