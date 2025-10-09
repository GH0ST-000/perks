<?php

use App\Http\Controllers\Api\AuthController;
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
