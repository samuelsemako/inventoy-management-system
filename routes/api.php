<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\admin\StaffPassport;
use App\Http\Controllers\v1\admin\RoleController;
use App\Http\Controllers\v1\Admin\AdminController;
use App\Http\Controllers\v1\admin\Sale\SaleController;
use App\Http\Controllers\v1\admin\User\UserController;
use App\Http\Controllers\v1\Admin\Auth\AdminAuthController;
use App\Http\Controllers\v1\admin\StaffPermissionController;
use App\Http\Controllers\v1\Admin\Inventory\ProductController;
use App\Http\Controllers\v1\Admin\Inventory\CategoryController;
use App\Http\Controllers\v1\Admin\Inventory\SupplierController;
use App\Http\Controllers\v1\Admin\User\Auth\UserAuthController;



Route::prefix('v1/')->group(function () {
    Route::prefix('admin/')->group(function () {
        Route::post('auth/login', [AdminAuthController::class, 'login']);
        Route::post('auth/reset-password', [AdminAuthController::class, 'resetPassword']);
        Route::post('auth/finish-reset-password', [AdminAuthController::class, 'finishPasswordReset']);
        Route::post('auth/resend-otp', [AdminAuthController::class, 'resendOtp']);

        Route::middleware('auth:admin')->group(function () {
            Route::get('auth/fetch-admin-profile', [AdminAuthController::class, 'fetchProfile']);
            Route::apiResource('products', ProductController::class);
            Route::apiResource('categories', CategoryController::class);
            Route::apiResource('suppliers', SupplierController::class);
            Route::apiResource('staffs', AdminController::class);
            Route::apiResource('sales', SaleController::class);
            Route::apiResource('users', UserController::class);
            Route::apiResource('roles', RoleController::class);
            Route::post('passport/{id}', [StaffPassport::class, 'uploadPassport']);

            Route::post('direct-permissions/{staffId}', [StaffPermissionController::class, 'assignDirectPermissions'])->middleware('permission:manage sales');
            Route::delete('revoke-permissions/{staffId}', [StaffPermissionController::class, 'revokeDirectPermissions'])->middleware('permission:manage sales');
        });
    });

    Route::prefix('user/')->group(function () {
        Route::post('auth/login', [UserAuthController::class, 'login']);
    
    });
});
