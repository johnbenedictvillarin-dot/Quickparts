<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\OtpController;

// Public routes
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // OTP Registration Routes
    Route::get('/register', [OtpController::class, 'showVerifyForm'])->name('register');
    Route::post('/send-otp', [OtpController::class, 'sendOtp'])->name('send.otp');
    Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('verify.otp');
    Route::get('/complete-registration', [OtpController::class, 'showRegistrationForm'])->name('register.complete');
    Route::post('/complete-registration', [OtpController::class, 'completeRegistration'])->name('register.complete.submit');
    Route::post('/resend-otp', [OtpController::class, 'resendOtp'])->name('resend.otp');
});

// Auth routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/account/settings', [AccountController::class, 'settings']);
    Route::put('/account/profile', [AccountController::class, 'updateProfile']);
    Route::put('/account/password', [AccountController::class, 'updatePassword']);
    
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('products.delete');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{orderId}/details', [AdminController::class, 'orderDetails'])->name('orders.details');
    Route::put('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');
    Route::put('/orders/{id}/delivery-status', [AdminController::class, 'updateDeliveryStatus'])->name('orders.delivery-status');
    Route::put('/orders/{id}/estimated-delivery', [AdminController::class, 'updateEstimatedDelivery'])->name('orders.estimated-delivery');
    Route::get('/customers', [AdminController::class, 'allCustomers'])->name('customers.all');
    Route::get('/customers/{userId}/orders', [AdminController::class, 'customerOrders'])->name('customers.orders');
    Route::get('/sales-report', [AdminController::class, 'salesReport'])->name('sales.report');
});