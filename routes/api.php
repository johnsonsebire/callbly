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
    });
    
    // Contacts routes
    Route::prefix('contacts')->group(function () {
        Route::get('/{id}', function ($id) {
            $contact = \App\Models\Contact::where('id', $id)
                ->where('user_id', auth()->id())
                ->first();
                
            if (!$contact) {
                return response()->json(['error' => 'Contact not found'], 404);
            }
            
            return response()->json($contact);
        });
        
        // WhatsApp related endpoints
        Route::post('/{id}/check-whatsapp', function ($id) {
            $contact = \App\Models\Contact::where('id', $id)
                ->where('user_id', auth()->id())
                ->first();
                
            if (!$contact) {
                return response()->json(['error' => 'Contact not found'], 404);
            }
            
            $whatsAppService = app(\App\Services\Contact\WhatsAppDetectionService::class);
            $hasWhatsApp = $whatsAppService->checkAndUpdateContact($contact);
            
            return response()->json([
                'has_whatsapp' => $hasWhatsApp,
                'whatsapp_url' => $hasWhatsApp ? $whatsAppService->generateWhatsAppUrl($contact->whatsapp_number ?: $contact->phone_number) : null,
                'checked_at' => $contact->fresh()->whatsapp_checked_at
            ]);
        });
        
        Route::get('/{id}/whatsapp-url', function ($id) {
            $contact = \App\Models\Contact::where('id', $id)
                ->where('user_id', auth()->id())
                ->first();
                
            if (!$contact) {
                return response()->json(['error' => 'Contact not found'], 404);
            }
            
            $phoneNumber = $contact->whatsapp_number ?: $contact->phone_number;
            if (!$phoneNumber) {
                return response()->json(['error' => 'No phone number available'], 400);
            }
            
            $whatsAppService = app(\App\Services\Contact\WhatsAppDetectionService::class);
            $message = request('message', '');
            
            return response()->json([
                'whatsapp_url' => $whatsAppService->generateWhatsAppUrl($phoneNumber, $message),
                'whatsapp_web_url' => $whatsAppService->generateWhatsAppWebUrl($phoneNumber, $message)
            ]);
        });
        
        // Bulk WhatsApp check
        Route::post('/bulk-check-whatsapp', function () {
            $contactIds = request('contact_ids', []);
            
            if (empty($contactIds)) {
                return response()->json(['error' => 'No contact IDs provided'], 400);
            }
            
            $contacts = \App\Models\Contact::whereIn('id', $contactIds)
                ->where('user_id', auth()->id())
                ->get();
                
            $whatsAppService = app(\App\Services\Contact\WhatsAppDetectionService::class);
            $results = $whatsAppService->bulkCheckContacts($contacts);
            
            return response()->json([
                'results' => $results,
                'total_checked' => count($results)
            ]);
        });
        
        // Get contacts needing follow-up
        Route::get('/follow-up/pending', function () {
            $contacts = \App\Models\Contact::where('user_id', auth()->id())
                ->needsFollowUp()
                ->with('groups')
                ->get();
                
            return response()->json($contacts);
        });
        
        // CRM statistics
        Route::get('/stats/crm', function () {
            $userId = auth()->id();
            
            $stats = [
                'total_contacts' => \App\Models\Contact::where('user_id', $userId)->count(),
                'new_leads' => \App\Models\Contact::where('user_id', $userId)->byLeadStatus('new')->count(),
                'qualified_leads' => \App\Models\Contact::where('user_id', $userId)->byLeadStatus('qualified')->count(),
                'active_leads' => \App\Models\Contact::where('user_id', $userId)->activeLeads()->count(),
                'high_priority' => \App\Models\Contact::where('user_id', $userId)->highPriority()->count(),
                'follow_ups_due' => \App\Models\Contact::where('user_id', $userId)->needsFollowUp()->count(),
                'whatsapp_contacts' => \App\Models\Contact::where('user_id', $userId)->where('has_whatsapp', true)->count(),
            ];
            
            return response()->json($stats);
        });
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