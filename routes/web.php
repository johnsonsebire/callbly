<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
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
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamInvitationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/', function () {
    return view('home'); // Use Blade view for home
});

// Team Invitation Public Routes (must be before auth middleware)
Route::prefix('team-invitations')->name('team-invitations.')->group(function() {
    Route::get('/{token}', [TeamInvitationController::class, 'show'])->name('show');
    Route::post('/{token}/accept', [TeamInvitationController::class, 'accept'])->name('accept');
    Route::post('/{token}/decline', [TeamInvitationController::class, 'decline'])->name('decline');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    
    // Password Reset Routes
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
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
        Route::post('/sms/buy-credits', [SmsController::class, 'buyCredits'])->name('sms.buy-credits');
        
        // SMS Billing Tiers
        Route::get('/sms/billing-tier', [SmsController::class, 'showBillingTier'])->name('sms.billing-tier');
        
        // Consolidated Individual Contacts routes
        Route::get('contacts/manage', [ContactController::class, 'index'])->name('contacts.manage');
        Route::resource('contacts', ContactController::class);
        Route::get('contacts-import', [ContactController::class, 'import'])->name('contacts.import');
        Route::post('contacts-import', [ContactController::class, 'uploadImport'])->name('contacts.upload-import');
        Route::post('contacts-import-process', [ContactController::class, 'processImport'])->name('contacts.process-import');
        Route::get('contacts-export', [ContactController::class, 'export'])->name('contacts.export');
        Route::get('contacts-export-process', [ContactController::class, 'processExport'])->name('contacts.process-export');
        
        // Custom Fields Routes
        Route::prefix('custom-fields')->name('custom-fields.')->group(function () {
            Route::get('/', [CustomFieldController::class, 'index'])->name('index');
            Route::get('/create', [CustomFieldController::class, 'create'])->name('create');
            Route::post('/', [CustomFieldController::class, 'store'])->name('store');
            Route::get('/{customField}', [CustomFieldController::class, 'show'])->name('show');
            Route::get('/{customField}/edit', [CustomFieldController::class, 'edit'])->name('edit');
            Route::put('/{customField}', [CustomFieldController::class, 'update'])->name('update');
            Route::delete('/{customField}', [CustomFieldController::class, 'destroy'])->name('destroy');
            Route::patch('/{customField}/toggle-status', [CustomFieldController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/sort', [CustomFieldController::class, 'sort'])->name('sort');
        });
        
        // Contact Group Routes
        Route::prefix('contact-groups')->name('contact-groups.')->group(function () {
            Route::get('/', [App\Http\Controllers\ContactGroupController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\ContactGroupController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\ContactGroupController::class, 'store'])->name('store');
            Route::get('/{contactGroup}', [App\Http\Controllers\ContactGroupController::class, 'show'])->name('show');
            Route::get('/{contactGroup}/edit', [App\Http\Controllers\ContactGroupController::class, 'edit'])->name('edit');
            Route::put('/{contactGroup}', [App\Http\Controllers\ContactGroupController::class, 'update'])->name('update');
            Route::delete('/{contactGroup}', [App\Http\Controllers\ContactGroupController::class, 'destroy'])->name('destroy');
            
            // AJAX search for contacts within a group
            Route::get('/{contactGroup}/search-contacts', [App\Http\Controllers\ContactGroupController::class, 'searchContacts'])->name('search-contacts');
            
            // Adding/removing contacts from groups
            Route::get('/{contactGroup}/add-contacts', [App\Http\Controllers\ContactGroupController::class, 'addContacts'])->name('add-contacts');
            Route::post('/{contactGroup}/store-contacts', [App\Http\Controllers\ContactGroupController::class, 'storeContacts'])->name('store-contacts');
            Route::delete('/{contactGroup}/contacts/{contact}', [App\Http\Controllers\ContactGroupController::class, 'removeContact'])->name('remove-contact');
        });
    });

    // Contact routes
    Route::middleware(['auth'])->group(function () {
        // Special route to fetch contact phone for SMS compose
        Route::post('/fetch-contact-phone', function (Illuminate\Http\Request $request) {
            $contactId = $request->input('contact_id');
            $contact = App\Models\Contact::where('id', $contactId)
                ->where('user_id', auth()->id())
                ->first();
                
            if (!$contact) {
                return response()->json(['error' => 'Contact not found'], 404);
            }
            
            return response()->json([
                'id' => $contact->id,
                'phone_number' => $contact->phone_number,
                'first_name' => $contact->first_name,
                'last_name' => $contact->last_name
            ]);
        })->name('contacts.fetch-phone');
    });

    // USSD Routes
    Route::middleware(['verified'])->prefix('ussd')->name('ussd.')->group(function () {
        Route::get('/', [UssdController::class, 'dashboard'])->name('dashboard');
        Route::get('/services', [UssdController::class, 'services'])->name('services');
        Route::get('/create', [UssdController::class, 'create'])->name('create');
        Route::post('/store', [UssdController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [UssdController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UssdController::class, 'update'])->name('update');
        Route::delete('/{id}', [UssdController::class, 'destroy'])->name('destroy');
        Route::get('/analytics', [UssdController::class, 'analytics'])->name('analytics');
    });

    // Virtual Numbers Routes
    Route::middleware(['verified'])->prefix('virtual-numbers')->name('virtual-numbers.')->group(function () {
        Route::get('/', [VirtualNumberController::class, 'index'])->name('index');
        Route::get('/browse', [VirtualNumberController::class, 'browse'])->name('browse');
        Route::get('/my-numbers', [VirtualNumberController::class, 'myNumbers'])->name('my-numbers');
        Route::post('/purchase', [VirtualNumberController::class, 'purchaseNumber'])->name('purchase');
        Route::get('/{virtualNumber}/usage', [VirtualNumberController::class, 'showUsage'])->name('usage');
        Route::put('/{virtualNumber}/configure', [VirtualNumberController::class, 'configureNumber'])->name('configure');
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

    // Wallet Routes
    Route::middleware(['verified'])->prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::get('/topup', [WalletController::class, 'showTopupForm'])->name('topup');
        Route::post('/topup', [WalletController::class, 'processTopup'])->name('process-topup');
        Route::get('/purchase-sms', [WalletController::class, 'showPurchaseSmsForm'])->name('purchase-sms');
        Route::post('/purchase-sms', [WalletController::class, 'processPurchaseSms'])->name('process-purchase-sms');
    });

    // Teams Routes
    Route::middleware(['auth', 'verified'])->prefix('teams')->name('teams.')->group(function () {
        Route::get('/', [TeamController::class, 'index'])->name('index');
        Route::get('/create', [TeamController::class, 'create'])->name('create');
        Route::post('/', [TeamController::class, 'store'])->name('store');
        Route::get('/{team}', [TeamController::class, 'show'])->name('show');
        Route::get('/{team}/edit', [TeamController::class, 'edit'])->name('edit');
        Route::put('/{team}', [TeamController::class, 'update'])->name('update');
        Route::delete('/{team}', [TeamController::class, 'destroy'])->name('destroy');
        Route::post('/{team}/members/{user}', [TeamController::class, 'updateMember'])->name('members.update');
        Route::delete('/{team}/members/{user}', [TeamController::class, 'removeMember'])->name('members.destroy');
        Route::post('/{team}/leave', [TeamController::class, 'leave'])->name('leave');
        Route::post('/switch/{team}', [TeamController::class, 'switchTeam'])->name('switch');
        
        // Team Invitations (for authenticated users)
        Route::middleware('auth')->group(function() {
            Route::get('/{team}/invitations/create', [TeamInvitationController::class, 'create'])->name('invitations.create');
            Route::post('/{team}/invitations', [TeamInvitationController::class, 'store'])->name('invitations.store');
            Route::delete('/{team}/invitations/{invitation}', [TeamInvitationController::class, 'destroy'])->name('invitations.destroy');
        });
    });
    
    // Super Admin Sender Name Approval Routes
    Route::middleware(['auth','role:super-admin|super admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('sender-names', [\App\Http\Controllers\Admin\SenderNameApprovalController::class, 'index'])
            ->name('sender-names.index');
        Route::put('sender-names/{sender_name}', [\App\Http\Controllers\Admin\SenderNameApprovalController::class, 'update'])
            ->name('sender-names.update');
        Route::delete('sender-names/{sender_name}', [\App\Http\Controllers\Admin\SenderNameApprovalController::class, 'destroy'])
            ->name('sender-names.destroy');
        // Add new route for creating sender names for users
        Route::post('sender-names/create-for-user', [\App\Http\Controllers\Admin\SenderNameApprovalController::class, 'createForUser'])
            ->name('sender-names.create-for-user');
        
        // User Management for Super Admin
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        
        // New routes for role assignment and SMS credit additions
        Route::put('users/{user}/update-role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])
            ->name('users.update-role');
        Route::post('users/{user}/add-credits', [\App\Http\Controllers\Admin\UserController::class, 'addCredits'])
            ->name('users.add-credits');
            
        // User Impersonation routes
        Route::get('impersonate/{user}', [\App\Http\Controllers\Admin\ImpersonationController::class, 'impersonate'])
            ->name('impersonate');
    });
    
    // Route accessible to all users for stopping impersonation
    Route::get('stop-impersonating', [\App\Http\Controllers\Admin\ImpersonationController::class, 'stopImpersonating'])
        ->name('stop-impersonating')
        ->middleware('auth');

    // Support Request Route
    Route::post('/support/send', [App\Http\Controllers\SupportController::class, 'send'])->name('support.send');
});

// Contact form submission route (public, no auth required)
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');
