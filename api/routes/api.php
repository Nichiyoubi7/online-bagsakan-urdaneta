<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NotificationController;

// Public routes
Route::post("/register", [AuthController::class, "register"]);
Route::post("/login",    [AuthController::class, "login"]);
Route::post("/otp/send",   [OtpController::class, "send"]);
Route::post("/otp/verify", [OtpController::class, "verify"]);

// Public category & product listing
Route::get("/categories",        [CategoryController::class, "index"]);
Route::get("/categories/{id}",   [CategoryController::class, "show"]);
Route::get("/products",          [ProductController::class, "index"]);
Route::get("/products/{id}",     [ProductController::class, "show"]);
Route::get("/reviews",           [ReviewController::class, "index"]);
Route::get("/users/{id}",        [UserController::class, "show"]);
// Seller — get own products


// Protected routes
Route::middleware("auth:sanctum")->group(function () {

    // Auth
    Route::post("/logout", [AuthController::class, "logout"]);
    Route::get("/me",      [AuthController::class, "me"]);

    // Self profile update (any authenticated user)
    Route::put("/profile", [UserController::class, "updateSelf"]);

    // Seller uploads their ID document
    Route::post("/profile/document", [UserController::class, "uploadDocument"]);

    Route::get('/seller/products', [ProductController::class, 'sellerProducts']);

    // Products
    Route::post("/products",          [ProductController::class, "store"]);
    Route::put("/products/{id}",      [ProductController::class, "update"]);
    Route::delete("/products/{id}",   [ProductController::class, "destroy"]);

    // Categories
    Route::post("/categories",        [CategoryController::class, "store"]);
    Route::put("/categories/{id}",    [CategoryController::class, "update"]);
    Route::delete("/categories/{id}", [CategoryController::class, "destroy"]);

    // Orders
    Route::get("/orders",             [OrderController::class, "index"]);
    Route::get("/orders/{id}",        [OrderController::class, "show"]);
    Route::post("/orders",            [OrderController::class, "store"]);
    Route::put("/orders/{id}/status", [OrderController::class, "updateStatus"]);

    // Reviews
    Route::post("/reviews",          [ReviewController::class, "store"]);
    Route::delete("/reviews/{id}",   [ReviewController::class, "destroy"]);

    // Notifications
    Route::get("/notifications",              [NotificationController::class, "index"]);
    Route::get("/notifications/unread-count", [NotificationController::class, "unreadCount"]);
    Route::put("/notifications/{id}/read",    [NotificationController::class, "markRead"]);
    Route::put("/notifications/read-all",     [NotificationController::class, "markAllRead"]);

    // Users (admin only — checked in controller)
    Route::get("/users",                  [UserController::class, "index"]);
    Route::get("/users/{id}",             [UserController::class, "show"]);
    Route::put("/users/{id}",             [UserController::class, "update"]);
    Route::delete("/users/{id}",          [UserController::class, "destroy"]);
    Route::put("/users/{id}/verify",      [UserController::class, "verify"]);

    // Seller shop setup
    Route::post('/seller/shop', function(\Illuminate\Http\Request $request) {
        $user = $request->user();
        $user->update([
            'store_name'        => $request->store_name ?? $request->name,
            'store_description' => $request->store_description ?? $request->description,
        ]);
        return response()->json([
            'message' => 'Shop created successfully!',
            'user'    => $user,
        ]);
    });

    Route::get('/seller/shop', function(\Illuminate\Http\Request $request) {
        $user = $request->user();
        if (!$user->store_name) {
            return response()->json(['message' => 'No shop found.'], 404);
        }
        return response()->json($user);
    });

});