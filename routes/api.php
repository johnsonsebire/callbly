<?php

use App\Http\Controllers\Api\ContactCenterController;
use App\Http\Controllers\Api\PaymentController;
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

Route::middleware('auth:sanctum')->group(function () {
    // User profile routes
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // SMS routes
    Route::prefix('sms')->group(function () {
        Route::post('/send', [SmsController::class, 'sendSingle']);
        Route::post('/send-bulk', [SmsController::class, 'sendBulk']);
        Route::post('/sender-names', [SmsController::class, 'registerSenderName']);
        Route::get('/sender-names', [SmsController::class, 'getSenderNames']);
        Route::get('/campaigns', [SmsController::class, 'getCampaigns']);
        Route::get('/campaigns/{id}', [SmsController::class, 'getCampaignDetails']);
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