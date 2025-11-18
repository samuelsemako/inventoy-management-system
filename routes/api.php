<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\Admin\AdminController;
use App\Http\Controllers\v1\Admin\Auth\AdminAuthController;
use App\Http\Controllers\v1\Admin\Inventory\ProductController;
use App\Http\Controllers\v1\Admin\Inventory\CategoryController;
use App\Http\Controllers\v1\Admin\Inventory\SupplierController;


// Route::apiResource('v1/admin', AdminController::class);
Route::middleware(['auth:admin', 'permission:manage admin'])->apiResource('v1/admin', AdminController::class);
Route::post('v1/admin/auth/login', [AdminAuthController::class, 'login']);
Route::post('v1/admin/auth/reset-password', [AdminAuthController::class, 'resetPassword']);
Route::post('v1/admin/auth/finish-reset-password', [AdminAuthController::class, 'finishPasswordReset']);
Route::post('v1/admin/auth/resend-otp', [AdminAuthController::class, 'resendOtp']);
Route::middleware('auth:admin')->get('v1/admin/auth/fetch-admin-profile', [AdminAuthController::class, 'fetchProfile']);

// Admin(Inventory Category) Routes
Route::middleware(['auth:admin', 'permission:manage products'])->apiResource('v1/admin/inventory/category', CategoryController::class);

// Admin(Inventory Supplier) Routes
Route::apiResource('v1/admin/inventory/supplier', SupplierController::class);

//Admin(Inventory Product) Routes
Route::apiResource('v1/admin/inventory/product', ProductController::class);