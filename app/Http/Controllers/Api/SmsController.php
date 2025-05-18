<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SenderName;
use App\Models\SmsCampaign;
use App\Services\SmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SmsController extends Controller
{
    protected SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send a single SMS message.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendSingle(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'recipient' => 'required|string',
            'message' => 'required|string|max:160',
            'sender_name' => 'required|string|exists:sender_names,name',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            
            // Check if sender name belongs to the user and is approved
            $senderName = SenderName::where('name', $request->sender_name)
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->first();
            
            if (!$senderName) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sender name not found or not approved'
                ], 400);
            }
            
            // Check if user has sufficient balance
            $smsRate = 0.01; // Example rate per SMS, can be dynamic based on user's plan
            if ($user->account_balance < $smsRate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient account balance'
                ], 400);
            }

            // Create SMS campaign for tracking
            $campaign = SmsCampaign::create([
                'user_id' => $user->id,
                'name' => 'Single SMS to ' . $request->recipient,
                'message' => $request->message,
                'sender_name' => $request->sender_name,
                'status' => 'pending',
                'recipients_count' => 1,
                'scheduled_at' => $request->scheduled_at,
            ]);

            // Send SMS via service
            $result = $this->smsService->sendSingle(
                $request->recipient,
                $request->message,
                $request->sender_name,
                $campaign->id
            );

            if ($result['success']) {
                // Update campaign status
                $campaign->update([
                    'status' => 'sent',
                    'delivered_count' => 1
                ]);

                // Deduct user balance
                $user->update([
                    'account_balance' => $user->account_balance - $smsRate
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'SMS sent successfully',
                    'data' => [
                        'campaign_id' => $campaign->id,
                        'reference' => $result['reference'],
                    ]
                ]);
            } else {
                // Update campaign status to failed
                $campaign->update([
                    'status' => 'failed',
                    'failed_count' => 1
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send SMS: ' . $result['message']
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('SMS sending error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending SMS'
            ], 500);
        }
    }

    /**
     * Send bulk SMS messages.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendBulk(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'required|string',
            'message' => 'required|string|max:160',
            'sender_name' => 'required|string|exists:sender_names,name',
            'name' => 'required|string|max:255',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $recipientsCount = count($request->recipients);
            
            // Check if sender name belongs to the user and is approved
            $senderName = SenderName::where('name', $request->sender_name)
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->first();
            
            if (!$senderName) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sender name not found or not approved'
                ], 400);
            }
            
            // Check if user has sufficient balance
            $smsRate = 0.01; // Example rate per SMS
            $totalCost = $smsRate * $recipientsCount;
            
            if ($user->account_balance < $totalCost) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient account balance for bulk SMS'
                ], 400);
            }

            // Create SMS campaign
            $campaign = SmsCampaign::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'message' => $request->message,
                'sender_name' => $request->sender_name,
                'status' => 'processing',
                'recipients_count' => $recipientsCount,
                'scheduled_at' => $request->scheduled_at,
            ]);

            // Send SMS via service (this might be processed as a queue job for large batches)
            $result = $this->smsService->sendBulk(
                $request->recipients,
                $request->message,
                $request->sender_name,
                $campaign->id
            );

            if ($result['success']) {
                // Update campaign status
                $campaign->update([
                    'status' => $result['completed'] ? 'sent' : 'processing',
                    'delivered_count' => $result['delivered_count'] ?? 0,
                    'failed_count' => $result['failed_count'] ?? 0,
                ]);

                // Deduct user balance
                $user->update([
                    'account_balance' => $user->account_balance - $totalCost
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Bulk SMS processing started successfully',
                    'data' => [
                        'campaign_id' => $campaign->id,
                        'reference' => $result['reference'],
                        'recipients_count' => $recipientsCount,
                        'estimated_cost' => $totalCost,
                    ]
                ]);
            } else {
                // Update campaign status to failed
                $campaign->update([
                    'status' => 'failed'
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to process bulk SMS: ' . $result['message']
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Bulk SMS error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing bulk SMS'
            ], 500);
        }
    }

    /**
     * Register a new sender name.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function registerSenderName(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:11|min:3|unique:sender_names,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();

            // Create sender name record
            $senderName = SenderName::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'status' => 'pending',
            ]);

            // Initiate sender name registration with service provider
            $result = $this->smsService->registerSenderName($senderName->name, $user->id);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sender name registration submitted successfully and pending approval',
                    'data' => [
                        'sender_name_id' => $senderName->id,
                        'reference' => $result['reference'],
                        'status' => $senderName->status,
                    ]
                ]);
            } else {
                // Delete the sender name record if registration failed
                $senderName->delete();

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to register sender name: ' . $result['message']
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Sender name registration error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while registering sender name'
            ], 500);
        }
    }

    /**
     * Get sender names for the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getSenderNames(Request $request): JsonResponse
    {
        $user = $request->user();
        $senderNames = SenderName::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $senderNames
        ]);
    }

    /**
     * Get SMS campaigns for the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getCampaigns(Request $request): JsonResponse
    {
        $user = $request->user();
        $campaigns = SmsCampaign::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $campaigns
        ]);
    }

    /**
     * Get details of a specific SMS campaign.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function getCampaignDetails(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $campaign = SmsCampaign::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Campaign not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $campaign
        ]);
    }
}
