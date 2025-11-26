<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function (): void {
    // Public routes
    Route::controller(AuthController::class)->group(function (): void {
        Route::post('register', 'register');
        Route::post('login', 'login');
    });

    // Protected routes
    Route::controller(AuthController::class)->middleware('auth:api')->group(function (): void {
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
        Route::get('me', 'me');
    });
});

// Profile routes (protected)
Route::group(['prefix' => 'profile', 'middleware' => 'auth:api'], function (): void {
    Route::controller(ProfileController::class)->group(function (): void {
        Route::put('update', 'updateProfile');
        Route::put('password', 'updatePassword');
        Route::put('photo', 'updateProfilePhoto');
    });
});

// Admin routes (protected and role-based)
Route::group(['prefix' => 'admin', 'middleware' => ['auth:api', 'role:admin']], function (): void {
    Route::get('dashboard', [AdminController::class, 'dashboard']);
    Route::get('users', [AdminController::class, 'getUsers']);
    Route::post('users', [AdminController::class, 'createUser']);
    Route::put('users/{user}', [AdminController::class, 'updateUser']);
    Route::delete('users/{user}', [AdminController::class, 'deleteUser']);
    Route::get('companies', [AdminController::class, 'getCompanies']);
});

// Customer routes (protected - accessible by both admin and manager)
Route::group(['prefix' => 'customers', 'middleware' => ['auth:api', 'roles:admin,manager']], function (): void {
    Route::post('send-verification', [CustomerController::class, 'sendVerificationCode']);
    Route::post('verify-code', [CustomerController::class, 'verifyCode']);
    Route::get('notifications', [CustomerController::class, 'getNotifications']);
    Route::get('notifications/{customerNotification}', [CustomerController::class, 'getNotification']);
    Route::put('notifications/{customerNotification}/mark-used', [CustomerController::class, 'markAsUsed']);
    Route::get('statistics', [CustomerController::class, 'getStatistics']);
});
