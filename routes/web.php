<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ContactCenterController;
use App\Http\Controllers\UssdController;
use App\Http\Controllers\VirtualNumberController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactGroupController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/', function () {
    return view('home'); // Use Blade view for home
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        // Auto-verify all users (temporary solution)
        if (!auth()->user()->hasVerifiedEmail()) {
            auth()->user()->markEmailAsVerified();
            return redirect('/dashboard')->with('success', 'Your email has been verified.');
        }
        return view('auth.verify');
    })->name('verification.notice');
    
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard')->with('success', 'Email verified successfully!');
    })->middleware(['signed'])->name('verification.verify');
    
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // SMS Routes
    Route::middleware(['verified'])->group(function () {
        // SMS Dashboard
        Route::get('/sms', [SmsController::class, 'dashboard'])->name('sms.dashboard');
        
        // SMS Messages and Compose
        Route::get('/sms/messages', [SmsController::class, 'messages'])->name('sms.messages');
        Route::get('/sms/compose', [SmsController::class, 'compose'])->name('sms.compose');
        Route::post('/sms/send', [SmsController::class, 'send'])->name('sms.send');
        Route::post('/sms/calculate-credits', [SmsController::class, 'calculateCredits'])->name('sms.calculate-credits');
        
        // SMS Templates
        Route::get('/sms/templates', [SmsController::class, 'templates'])->name('sms.templates');
        Route::get('/sms/templates/create', [SmsController::class, 'createTemplate'])->name('sms.templates.create');
        Route::post('/sms/templates', [SmsController::class, 'storeTemplate'])->name('sms.templates.store');
        Route::get('/sms/templates/{id}/edit', [SmsController::class, 'editTemplate'])->name('sms.templates.edit');
        Route::put('/sms/templates/{id}', [SmsController::class, 'updateTemplate'])->name('sms.templates.update');
        Route::delete('/sms/templates/{id}', [SmsController::class, 'deleteTemplate'])->name('sms.templates.delete');
        Route::get('/sms/templates/{id}/content', [SmsController::class, 'getTemplateContent'])->name('sms.templates.content');
        
        // SMS Campaigns
        Route::get('/sms/campaigns', [SmsController::class, 'campaigns'])->name('sms.campaigns');
        Route::get('/sms/campaigns/{id}', [SmsController::class, 'campaignDetails'])->name('sms.campaign-details');
        Route::get('/sms/campaigns/{id}/download-report', [SmsController::class, 'downloadReport'])->name('sms.download-report');
        Route::get('/sms/campaigns/{id}/duplicate', [SmsController::class, 'duplicateCampaign'])->name('sms.duplicate-campaign');
        
        // SMS Sender Names
        Route::get('/sms/sender-names', [SmsController::class, 'senderNames'])->name('sms.sender-names');
        Route::post('/sms/sender-names', [SmsController::class, 'storeSenderName'])->name('sms.sender-names.store');
        
        // SMS Credits
        Route::get('/sms/credits', [SmsController::class, 'credits'])->name('sms.credits');
        
        // SMS Billing Tiers
        Route::get('/sms/billing-tier', [SmsController::class, 'showBillingTier'])->name('sms.billing-tier');
        
        // Consolidated Individual Contacts routes
        Route::get('contacts/manage', [ContactController::class, 'index'])->name('contacts.manage');
        Route::resource('contacts', ContactController::class);
        Route::get('contacts-import', [ContactController::class, 'import'])->name('contacts.import');
        Route::post('contacts-import', [ContactController::class, 'uploadImport'])->name('contacts.upload-import');
        Route::post('contacts-import-process', [ContactController::class, 'processImport'])->name('contacts.process-import');
        Route::get('contacts-export', [ContactController::class, 'export'])->name('contacts.export');
        
        // Contact Group Routes
        Route::prefix('contact-groups')->name('contact-groups.')->group(function () {
            Route::get('/', [App\Http\Controllers\ContactGroupController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\ContactGroupController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\ContactGroupController::class, 'store'])->name('store');
            Route::get('/{contactGroup}', [App\Http\Controllers\ContactGroupController::class, 'show'])->name('show');
            Route::get('/{contactGroup}/edit', [App\Http\Controllers\ContactGroupController::class, 'edit'])->name('edit');
            Route::put('/{contactGroup}', [App\Http\Controllers\ContactGroupController::class, 'update'])->name('update');
            Route::delete('/{contactGroup}', [App\Http\Controllers\ContactGroupController::class, 'destroy'])->name('destroy');
            
            // Adding/removing contacts from groups
            Route::get('/{contactGroup}/add-contacts', [App\Http\Controllers\ContactGroupController::class, 'addContacts'])->name('add-contacts');
            Route::post('/{contactGroup}/store-contacts', [App\Http\Controllers\ContactGroupController::class, 'storeContacts'])->name('store-contacts');
            Route::delete('/{contactGroup}/contacts/{contact}', [App\Http\Controllers\ContactGroupController::class, 'removeContact'])->name('remove-contact');
        });
    });

    // USSD Routes
    Route::middleware(['verified'])->prefix('ussd')->name('ussd.')->group(function () {
        Route::get('/', [UssdController::class, 'dashboard'])->name('dashboard');
        Route::get('/services', [UssdController::class, 'services'])->name('services');
        Route::get('/create', [UssdController::class, 'create'])->name('create');
        Route::post('/store', [UssdController::class, 'store'])->name('store');
        Route::get('/analytics', [UssdController::class, 'analytics'])->name('analytics');
    });

    // Virtual Numbers Routes
    Route::middleware(['verified'])->prefix('virtual-numbers')->name('virtual-numbers.')->group(function () {
        Route::get('/', [VirtualNumberController::class, 'index'])->name('index');
        Route::get('/browse', [VirtualNumberController::class, 'browse'])->name('browse');
        Route::get('/my-numbers', [VirtualNumberController::class, 'myNumbers'])->name('my-numbers');
    });

    // Contact Center Routes
    Route::middleware(['auth', 'verified'])->prefix('contact-center')->name('contact-center.')->group(function () {
        Route::get('/', [ContactCenterController::class, 'dashboard'])->name('dashboard');
        Route::post('/call', [ContactCenterController::class, 'initiateCall'])->name('initiate-call');
        Route::get('/calls/{id}/recording', [ContactCenterController::class, 'getCallRecording'])->name('call.recording');
        Route::post('/calls/{id}/end', [ContactCenterController::class, 'endCall'])->name('call.end');
    });

    // Profile & Settings Routes
    Route::middleware(['verified'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/settings/currency', [ProfileController::class, 'showCurrencySettings'])->name('settings.currency');
        Route::post('/settings/currency', [ProfileController::class, 'updateCurrency'])->name('settings.currency.update');
    });
    
    // Payment Routes
    Route::middleware(['verified'])->group(function () {
        Route::post('/payment/initiate', [PaymentController::class, 'initiate'])->name('payment.initiate');
        Route::get('/payment/verify', [PaymentController::class, 'verify'])->name('payment.verify');
    });

    // Super Admin Sender Name Approval Routes
    Route::middleware(['auth','role:super-admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('sender-names', [\App\Http\Controllers\Admin\SenderNameApprovalController::class, 'index'])
            ->name('sender-names.index');
        Route::put('sender-names/{sender_name}', [\App\Http\Controllers\Admin\SenderNameApprovalController::class, 'update'])
            ->name('sender-names.update');
        Route::delete('sender-names/{sender_name}', [\App\Http\Controllers\Admin\SenderNameApprovalController::class, 'destroy'])
            ->name('sender-names.destroy');
        
        // User Management for Super Admin
        Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])
            ->name('users.index');
    });
});
