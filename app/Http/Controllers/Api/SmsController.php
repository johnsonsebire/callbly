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
            
            // Check if sender name is available to the user (either owned or shared through teams)
            $senderNameAvailable = false;
            
            // First, check if this is the user's own sender name
            $senderName = SenderName::where('name', $request->sender_name)
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->first();
                
            if ($senderName) {
                $senderNameAvailable = true;
            } else {
                // If not, check if it's a sender name shared through a team
                $sharedSenderNames = $user->getAvailableSenderNames();
                $senderName = $sharedSenderNames->where('name', $request->sender_name)
                    ->where('status', 'approved')
                    ->first();
                    
                if ($senderName) {
                    $senderNameAvailable = true;
                    // We found a valid shared sender name
                    Log::info('Using shared sender name from team', [
                        'sender_name' => $senderName->name, 
                        'owner_id' => $senderName->user_id,
                        'user_id' => $user->id
                    ]);
                }
            }
            
            if (!$senderNameAvailable) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sender name not found or not approved'
                ], 400);
            }
            
            // Calculate SMS parts and required credits
            $smsCount = $this->calculateSmsPartsCount($request->message);
            $creditsNeeded = $smsCount; // 1 credit per SMS part
            
            // Get the user's available SMS credits (including shared ones from teams)
            $availableSmsCredits = $user->getAvailableSmsCredits();
            
            // Check if user has sufficient credits
            if ($availableSmsCredits < $creditsNeeded) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient SMS credits. You need ' . $creditsNeeded . ' credits but have ' . $availableSmsCredits
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

                // Don't deduct credits here as this is done in the web controller
                // This is to prevent double deduction when called from SmsController
                
                // Only deduct if directly called via API, not from web controller
                if (!request()->has('_internal_call')) {
                    // Use our credit deduction service to handle both personal and team credits
                    $teamResourceService = app(\App\Services\TeamResourceService::class);
                    $creditResult = $teamResourceService->deductSharedSmsCredits($user, $creditsNeeded);
                    
                    if (!$creditResult['success']) {
                        Log::error('Failed to deduct SMS credits', [
                            'user_id' => $user->id,
                            'campaign_id' => $campaign->id,
                            'credits_needed' => $creditsNeeded,
                            'error' => $creditResult['message']
                        ]);
                    }
                    
                    // Update campaign with team credit info if applicable
                    if ($creditResult['team_id']) {
                        $campaign->update([
                            'team_id' => $creditResult['team_id'],
                            'team_credits_used' => $creditResult['team_credits_used']
                        ]);
                    }
                }

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
            'campaign_id' => 'nullable|integer|exists:sms_campaigns,id', // Add campaign_id parameter
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
            
            // Check if sender name is available to the user (either owned or shared through teams)
            $senderNameAvailable = false;
            
            // First, check if this is the user's own sender name
            $senderName = SenderName::where('name', $request->sender_name)
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->first();
                
            if ($senderName) {
                $senderNameAvailable = true;
            } else {
                // If not, check if it's a sender name shared through a team
                $sharedSenderNames = $user->getAvailableSenderNames();
                $senderName = $sharedSenderNames->where('name', $request->sender_name)
                    ->where('status', 'approved')
                    ->first();
                    
                if ($senderName) {
                    $senderNameAvailable = true;
                    // We found a valid shared sender name, use it
                    Log::info('Using shared sender name from team', [
                        'sender_name' => $senderName->name, 
                        'owner_id' => $senderName->user_id,
                        'user_id' => $user->id
                    ]);
                }
            }
            
            if (!$senderNameAvailable) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sender name not found or not approved'
                ], 400);
            }
            
            // Calculate SMS parts and required credits
            $smsCount = $this->calculateSmsPartsCount($request->message);
            $creditsNeeded = $smsCount * $recipientsCount; // 1 credit per SMS part per recipient
            
            // Get the user's available SMS credits (including shared ones from teams)
            $availableSmsCredits = $user->getAvailableSmsCredits();
            
            // Check if user has sufficient credits
            if ($availableSmsCredits < $creditsNeeded) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient SMS credits. You need ' . $creditsNeeded . ' credits but have ' . $availableSmsCredits
                ], 400);
            }

            // Check if we should use an existing campaign or create a new one
            if ($request->has('campaign_id')) {
                $campaign = SmsCampaign::where('id', $request->campaign_id)
                    ->where('user_id', $user->id)
                    ->first();
                
                if (!$campaign) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Campaign not found or does not belong to user'
                    ], 400);
                }
                
                // Update campaign with new details if needed
                $campaign->update([
                    'status' => 'processing',
                    'recipients_count' => $recipientsCount,
                    'scheduled_at' => $request->scheduled_at ? date('Y-m-d H:i:s', strtotime($request->scheduled_at)) : $campaign->scheduled_at,
                ]);
            } else {
                // Create new SMS campaign
                $campaign = SmsCampaign::create([
                    'user_id' => $user->id,
                    'name' => $request->name,
                    'message' => $request->message,
                    'sender_name' => $request->sender_name,
                    'status' => 'processing',
                    'recipients_count' => $recipientsCount,
                    'scheduled_at' => $request->scheduled_at,
                ]);
            }

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

                // Explicitly check for the internal call flag
                // Using both has() and input() methods to be thorough
                $isInternalCall = $request->has('_internal_call') || $request->input('_internal_call') === true;
                
                Log::info('Credit deduction check', [
                    'is_internal_call' => $isInternalCall,
                    'request_has_flag' => $request->has('_internal_call'),
                    'request_input_flag' => $request->input('_internal_call'),
                    'request_all' => $request->all()
                ]);

                // Only deduct credits if this is a direct API call, not from the web controller
                if (!$isInternalCall) {
                    // Use our credit deduction service to handle both personal and team credits
                    $teamResourceService = app(\App\Services\TeamResourceService::class);
                    $creditResult = $teamResourceService->deductSharedSmsCredits($user, $creditsNeeded);
                    
                    if (!$creditResult['success']) {
                        Log::error('Failed to deduct SMS credits for bulk message', [
                            'user_id' => $user->id,
                            'campaign_id' => $campaign->id,
                            'credits_needed' => $creditsNeeded,
                            'error' => $creditResult['message']
                        ]);
                    }
                    
                    // Update campaign with team credit info if applicable
                    if ($creditResult['team_id']) {
                        $campaign->update([
                            'team_id' => $creditResult['team_id'],
                            'team_credits_used' => $creditResult['team_credits_used']
                        ]);
                    }
                    
                    // Include credit usage in the response
                    $personalCreditsUsed = $creditResult['personal_credits_used'] ?? 0;
                    $teamCreditsUsed = $creditResult['team_credits_used'] ?? 0;
                } else {
                    // For internal calls, don't deduct here (it's handled in the web controller)
                    Log::info('Skipping credit deduction in API as this is an internal call', [
                        'campaign_id' => $campaign->id
                    ]);
                    $personalCreditsUsed = 0;
                    $teamCreditsUsed = 0;
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Bulk SMS processing started successfully',
                    'data' => [
                        'campaign_id' => $campaign->id,
                        'reference' => $result['reference'],
                        'recipients_count' => $recipientsCount,
                        'estimated_cost' => $creditsNeeded,
                        'personal_credits_used' => $personalCreditsUsed,
                        'team_credits_used' => $teamCreditsUsed
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

    /**
     * Calculate the number of SMS parts required for a given message.
     *
     * @param string $message
     * @return int
     */
    private function calculateSmsPartsCount(string $message): int
    {
        $messageLength = strlen($message);
        $singlePartLimit = 160;
        $multiPartLimit = 153;

        if ($messageLength <= $singlePartLimit) {
            return 1;
        }

        return (int) ceil($messageLength / $multiPartLimit);
    }
}
