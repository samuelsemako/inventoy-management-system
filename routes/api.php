<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\Admin\Auth\AdminAuthController;


Route::post('v1/admin/auth/login', [AdminAuthController::class, 'login']);
Route::post('v1/admin/auth/reset-password', [AdminAuthController::class, 'resetPassword']);
Route::post('v1/admin/auth/finish-reset-password', [AdminAuthController::class, 'finishPasswordReset']);
Route::post('v1/admin/auth/resend-otp', [AdminAuthController::class, 'resendOtp']);
Route::middleware('auth:admin')->post('v1/admin/fetch-admin-profile', [AdminAuthController::class, 'fetchProfile']);
