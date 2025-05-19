<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    
    // SMS Routes
    Route::middleware(['verified'])->group(function () {
        // SMS Dashboard
        Route::get('/sms', [SmsController::class, 'dashboard'])->name('sms.dashboard');
        
        // SMS Compose and Send
        Route::get('/sms/compose', [SmsController::class, 'compose'])->name('sms.compose');
        Route::post('/sms/send', [SmsController::class, 'send'])->name('sms.send');
        Route::post('/sms/calculate-credits', [SmsController::class, 'calculateCredits'])->name('sms.calculate-credits');
        
        // SMS Campaigns
        Route::get('/sms/campaigns', [SmsController::class, 'campaigns'])->name('sms.campaigns');
        Route::get('/sms/campaigns/{id}', [SmsController::class, 'campaignDetails'])->name('sms.campaign-details');
        
        // SMS Sender Names
        Route::get('/sms/sender-names', [SmsController::class, 'senderNames'])->name('sms.sender-names');
        Route::post('/sms/sender-names', [SmsController::class, 'storeSenderName'])->name('sms.sender-names.store');
        
        // SMS Credits
        Route::get('/sms/credits', [SmsController::class, 'credits'])->name('sms.credits');
        
        // SMS Billing Tiers
        Route::get('/sms/billing-tier', [SmsController::class, 'showBillingTier'])->name('sms.billing-tier');
    });
    
    // Profile & Settings Routes
    Route::middleware(['verified'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/settings/currency', [ProfileController::class, 'showCurrencySettings'])->name('settings.currency');
        Route::post('/settings/currency', [ProfileController::class, 'updateCurrency'])->name('settings.currency.update');
    });
    
    // Payment Routes
    Route::middleware(['verified'])->group(function () {
        Route::post('/payment/initiate', [PaymentController::class, 'initiate'])->name('payment.initiate');
        Route::get('/payment/verify', [PaymentController::class, 'verify'])->name('payment.verify');
    });
});
