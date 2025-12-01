<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

// Landing page routes
Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::get('/offers', [LandingPageController::class, 'allOffers'])->name('offers.index');
Route::get('/offers/{offer}', [LandingPageController::class, 'showOffer'])->name('offers.show');
Route::get('/companies', [LandingPageController::class, 'companies'])->name('companies');
Route::post('/companies/request', [LandingPageController::class, 'storeCompanyRequest'])->name('companies.request');
Route::get('/partners', [LandingPageController::class, 'partners'])->name('partners');
Route::post('/partners/request', [LandingPageController::class, 'storePartnerRequest'])->name('partners.request');
Route::get('/blog', [LandingPageController::class, 'blog'])->name('blog.index');
Route::get('/blog/{slug}', [LandingPageController::class, 'blogPost'])->name('blog.show');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
