<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

// Landing page routes
Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::get('/offers', [LandingPageController::class, 'allOffers'])->name('offers.index');
Route::get('/offers/{offer}', [LandingPageController::class, 'showOffer'])->name('offers.show');
Route::post('/offers/{offer}/claim', [LandingPageController::class, 'claimOffer'])->middleware('auth')->name('offers.claim');
Route::get('/companies', [LandingPageController::class, 'companies'])->name('companies');
Route::post('/companies/request', [LandingPageController::class, 'storeCompanyRequest'])->name('companies.request');
Route::get('/partners', [LandingPageController::class, 'partners'])->name('partners');
Route::post('/partners/request', [LandingPageController::class, 'storePartnerRequest'])->name('partners.request');
Route::get('/blog', [LandingPageController::class, 'blog'])->name('blog.index');
Route::get('/blog/{slug}', [LandingPageController::class, 'blogPost'])->name('blog.show');
Route::get('/vacancies', [App\Http\Controllers\VacancyController::class, 'index'])->name('vacancies.index');
Route::get('/vacancies/{slug}', [App\Http\Controllers\VacancyController::class, 'show'])->name('vacancies.show');
Route::post('/vacancies/{vacancyId}/apply', [App\Http\Controllers\VacancyController::class, 'apply'])->name('vacancies.apply');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Wallet routes
    Route::get('/wallet', [App\Http\Controllers\WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/payment-methods', [App\Http\Controllers\WalletController::class, 'storePaymentMethod'])->name('wallet.payment-methods.store');
    
    // Gifts routes
    Route::get('/gifts', [App\Http\Controllers\GiftController::class, 'index'])->name('gifts.index');
    Route::post('/gifts/{gift}/redeem', [App\Http\Controllers\GiftController::class, 'redeem'])->name('gifts.redeem');
    Route::get('/my-gifts', [App\Http\Controllers\GiftController::class, 'myGifts'])->name('gifts.my-gifts');
    
    // History routes
    Route::get('/history', [App\Http\Controllers\HistoryController::class, 'index'])->name('history.index');
    
    // Family Members routes
    Route::get('/family-members', [App\Http\Controllers\FamilyMemberController::class, 'index'])->name('family-members.index');
    Route::post('/family-members', [App\Http\Controllers\FamilyMemberController::class, 'store'])->name('family-members.store');
    Route::put('/family-members/{familyMember}', [App\Http\Controllers\FamilyMemberController::class, 'update'])->name('family-members.update');
    Route::delete('/family-members/{familyMember}', [App\Http\Controllers\FamilyMemberController::class, 'destroy'])->name('family-members.destroy');
    
    // Payment routes
    Route::get('/payments', [App\Http\Controllers\PaymentController::class, 'showPaymentPage'])->name('payments.index');
    Route::post('/payments/initiate', [App\Http\Controllers\PaymentController::class, 'initiateOneTimePayment'])->name('payments.initiate');
    Route::get('/payments/success/{payment}', [App\Http\Controllers\PaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payments/failed', [App\Http\Controllers\PaymentController::class, 'paymentFailed'])->name('payment.failed');
    
    // Subscription routes
    Route::get('/subscriptions', [App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions/subscribe', [App\Http\Controllers\SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');
    Route::post('/subscriptions/{subscription}/cancel', [App\Http\Controllers\SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::get('/subscriptions/success/{subscription}', [App\Http\Controllers\SubscriptionController::class, 'subscriptionSuccess'])->name('subscription.success');
    Route::delete('/payment-methods/{paymentMethod}', [App\Http\Controllers\SubscriptionController::class, 'deletePaymentMethod'])->name('payment-methods.delete');
});

// Public callback routes (no auth required)
Route::post('/payment/callback', [App\Http\Controllers\PaymentController::class, 'handleCallback'])->name('payment.callback');
Route::post('/subscription/callback', [App\Http\Controllers\SubscriptionController::class, 'handleCallback'])->name('subscription.callback');

require __DIR__.'/auth.php';
