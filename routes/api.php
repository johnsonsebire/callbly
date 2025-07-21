<?php

use App\Http\Controllers\Api\AffiliateController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactCenterController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ServicePlanController;
use App\Http\Controllers\Api\SmsController;
use App\Http\Controllers\Api\UssdController;
use App\Http\Controllers\Api\VirtualNumberController;
use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // User profile routes
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Dashboard overview data
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // SMS routes
    Route::prefix('sms')->group(function () {
        Route::post('/send', [SmsController::class, 'sendSingle']);
        Route::post('/send-bulk', [SmsController::class, 'sendBulk']);
        Route::post('/sender-names', [SmsController::class, 'registerSenderName']);
        Route::get('/sender-names', [SmsController::class, 'getSenderNames']);
        Route::get('/campaigns', [SmsController::class, 'getCampaigns']);
        Route::get('/campaigns/{id}', [SmsController::class, 'getCampaignDetails']);
        
        // SMS Templates routes
        Route::get('/templates', [SmsController::class, 'getTemplates']);
        Route::post('/templates', [SmsController::class, 'createTemplate']);
        Route::put('/templates/{id}', [SmsController::class, 'updateTemplate']);
        Route::delete('/templates/{id}', [SmsController::class, 'deleteTemplate']);
    });
    
    // Contacts routes
    Route::get('/contacts/{id}', function ($id) {
        $contact = \App\Models\Contact::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
            
        if (!$contact) {
            return response()->json(['error' => 'Contact not found'], 404);
        }
        
        return response()->json($contact);
    });
    
    // Contacts list for mobile app
    Route::get('/contacts', function () {
        $user = auth()->user();
        $contacts = $user->getAvailableContacts(); // No limit - show all contacts
        
        return response()->json([
            'success' => true,
            'data' => $contacts->map(function ($contact) {
                return [
                    'id' => $contact->id,
                    'name' => $contact->full_name,
                    'phone' => $contact->phone_number,
                    'phone_number' => $contact->phone_number, // Add phone_number for mobile app compatibility
                    'email' => $contact->email,
                    'first_name' => $contact->first_name,
                    'last_name' => $contact->last_name,
                    'created_at' => $contact->created_at,
                    'updated_at' => $contact->updated_at,
                ];
            })->values()
        ]);
    });
    
    // Contact groups for mobile app
    Route::get('/contact-groups', function () {
        $user = auth()->user();
        $contactGroups = $user->getAvailableContactGroups();
        
        return response()->json([
            'success' => true,
            'data' => $contactGroups->map(function ($group) {
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'description' => $group->description,
                    'contacts_count' => $group->contacts_count ?? $group->contacts()->count(),
                    'created_at' => $group->created_at,
                    'updated_at' => $group->updated_at,
                ];
            })->values()
        ]);
    });
    
    // Contact group contacts
    Route::get('/contact-groups/{id}/contacts', function ($id) {
        $user = auth()->user();
        $contactGroup = \App\Models\ContactGroup::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
            
        if (!$contactGroup) {
            return response()->json(['error' => 'Contact group not found'], 404);
        }
        
        $contacts = $contactGroup->contacts()->get();
        
        return response()->json([
            'success' => true,
            'data' => $contacts->map(function ($contact) {
                return [
                    'id' => $contact->id,
                    'name' => $contact->full_name,
                    'phone' => $contact->phone_number,
                    'email' => $contact->email,
                    'first_name' => $contact->first_name,
                    'last_name' => $contact->last_name,
                    'created_at' => $contact->created_at,
                    'updated_at' => $contact->updated_at,
                ];
            })
        ]);
    });
    
    // USSD routes
    Route::prefix('ussd')->group(function () {
        Route::post('/services', [UssdController::class, 'create']);
        Route::get('/services', [UssdController::class, 'getServices']);
        Route::get('/services/{id}', [UssdController::class, 'getServiceDetails']);
        Route::put('/services/{id}', [UssdController::class, 'update']);
        Route::get('/services/{id}/analytics', [UssdController::class, 'getAnalytics']);
        Route::delete('/services/{id}', [UssdController::class, 'delete']);
    });
    
    // Virtual numbers routes
    Route::prefix('virtual-numbers')->group(function () {
        Route::get('/', [VirtualNumberController::class, 'browse']);
        Route::post('/reserve', [VirtualNumberController::class, 'reserve']);
        Route::post('/purchase', [VirtualNumberController::class, 'purchase']);
        Route::get('/my-numbers', [VirtualNumberController::class, 'getUserNumbers']);
        Route::put('/{id}/forwarding', [VirtualNumberController::class, 'updateForwarding']);
        Route::post('/{id}/renew', [VirtualNumberController::class, 'renew']);
    });
    
    // Contact center routes
    Route::prefix('contact-center')->group(function () {
        Route::post('/calls', [ContactCenterController::class, 'initiateCall']);
        Route::get('/calls', [ContactCenterController::class, 'getCalls']);
        Route::get('/calls/{id}', [ContactCenterController::class, 'getCallDetails']);
        Route::get('/calls/{id}/recording', [ContactCenterController::class, 'getCallRecording']);
        Route::post('/calls/{id}/end', [ContactCenterController::class, 'endCall']);
    });
    
    // Service plans routes
    Route::prefix('service-plans')->group(function () {
        Route::get('/', [ServicePlanController::class, 'index']);
        Route::get('/{id}', [ServicePlanController::class, 'show']);
        Route::post('/{id}/purchase', [ServicePlanController::class, 'purchase']);
    });
    
    // Order history routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::get('/{id}/invoice', [OrderController::class, 'downloadInvoice']);
    });
    
    // Affiliate system routes
    Route::prefix('affiliate')->group(function () {
        Route::get('/dashboard', [AffiliateController::class, 'dashboard']);
        Route::post('/referral-link', [AffiliateController::class, 'generateReferralLink']);
        Route::get('/commissions', [AffiliateController::class, 'getCommissions']);
        Route::post('/payout-request', [AffiliateController::class, 'requestPayout']);
        Route::get('/payout-history', [AffiliateController::class, 'getPayoutHistory']);
    });
    
    // Wallet API routes
    Route::prefix('wallet')->group(function () {
        Route::get('/balance', [WalletController::class, 'getBalance']);
        Route::get('/transactions', [WalletController::class, 'getTransactions']);
        Route::post('/topup', [WalletController::class, 'initiateTopup']);
        Route::post('/purchase-sms', [WalletController::class, 'apiPurchaseSms']);
        Route::post('/purchase-ussd', [WalletController::class, 'apiPurchaseUssd']);
        Route::post('/purchase-call', [WalletController::class, 'apiPurchaseCall']);
    });
    
    // Push notification settings
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'getNotifications']);
        Route::post('/register-device', [NotificationController::class, 'registerDevice']);
        Route::put('/settings', [NotificationController::class, 'updateSettings']);
        Route::post('/mark-read', [NotificationController::class, 'markAsRead']);
    });
});

// Webhook routes (no auth required)
Route::prefix('webhooks')->group(function () {
    // SMS webhooks
    Route::post('/sms/status/{campaignId}', [SmsController::class, 'deliveryStatusWebhook']);
    Route::post('/sms/bulk-status/{campaignId}', [SmsController::class, 'bulkDeliveryStatusWebhook']);
    Route::post('/sender-name/status/{senderName}', [SmsController::class, 'senderNameStatusWebhook']);
    
    // USSD webhooks
    Route::post('/ussd/session/{serviceId}', [UssdController::class, 'sessionCallback']);
    
    // Contact center webhooks
    Route::post('/calls/status', [ContactCenterController::class, 'callStatusWebhook']);
    
    // Payment webhooks
    Route::post('/paystack', [PaymentController::class, 'paystackWebhook']);
});

// Payment callback routes
Route::get('/payment/callback/{reference}', [PaymentController::class, 'handleCallback'])->name('payment.callback');

// Device authentication status - for biometric auth check
Route::middleware('auth:sanctum')->post('/auth/verify-device', [AuthController::class, 'verifyDevice']);