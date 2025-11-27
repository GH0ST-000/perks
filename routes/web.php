<?php

use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::get('/offers', [LandingPageController::class, 'allOffers'])->name('offers.index');
Route::get('/offers/{offer}', [LandingPageController::class, 'showOffer'])->name('offers.show');
