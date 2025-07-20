<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SenderName;
use App\Models\SmsCampaign;
use App\Models\SmsTemplate;
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
            'message' => 'required|string',
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
            
            // Check if sender name is available to the user
            $senderNameAvailable = false;
            
            $senderName = SenderName::where('name', $request->sender_name)
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->first();
                
            if ($senderName) {
                $senderNameAvailable = true;
            } else {
                $sharedSenderNames = $user->getAvailableSenderNames();
                $senderName = $sharedSenderNames->where('name', $request->sender_name)
                    ->where('status', 'approved')
                    ->first();
                    
                if ($senderName) {
                    $senderNameAvailable = true;
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
            $creditsNeeded = $smsCount;
            
            // Get the user's available SMS credits
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
                'scheduled_at' => $request->scheduled_at ? date('Y-m-d H:i:s', strtotime($request->scheduled_at)) : null,
            ]);

            // Deduct credits before queuing the job
            if (!request()->has('_internal_call')) {
                $teamResourceService = app(\App\Services\TeamResourceService::class);
                $creditResult = $teamResourceService->deductSharedSmsCredits($user, $creditsNeeded);
                
                if (!$creditResult['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to deduct SMS credits: ' . $creditResult['message']
                    ], 400);
                }
                
                $campaign->update([
                    'credits_used' => $creditsNeeded
                ]);
            }

            // Dispatch SMS job
            $job = new \App\Jobs\SendSingleSmsJob(
                $campaign->id,
                $request->recipient,
                $request->message,
                $request->sender_name
            );
            
            if ($request->scheduled_at && \Carbon\Carbon::parse($request->scheduled_at)->isAfter(now())) {
                $scheduledTime = \Carbon\Carbon::parse($request->scheduled_at);
                $delay = $scheduledTime->diffInSeconds(now());
                $job->delay($delay);
                dispatch($job);
                
                $message = 'SMS scheduled for ' . $scheduledTime->format('M d, Y H:i:s');
            } else {
                dispatch($job);
                $message = 'SMS is being sent';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'campaign_id' => $campaign->id,
                    'scheduled_at' => $request->scheduled_at,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('SMS sending error: ' . $e->getMessage());

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
            'recipients' => 'nullable|array',
            'recipients.*' => 'string',
            'contact_groups' => 'nullable|array',
            'contact_groups.*' => 'integer|exists:contact_groups,id',
            'message' => 'required|string',
            'sender_name' => 'required|string|exists:sender_names,name',
            'name' => 'required|string|max:255',
            'scheduled_at' => 'nullable|date|after:now',
            'campaign_id' => 'nullable|integer|exists:sms_campaigns,id',
        ]);

        // Custom validation to ensure either recipients or contact_groups is provided
        $validator->after(function ($validator) use ($request) {
            if (empty($request->recipients) && empty($request->contact_groups)) {
                $validator->errors()->add('recipients', 'Either recipients or contact_groups must be provided.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            
            // Resolve recipients from direct input and contact groups
            $allRecipients = $request->recipients ?? [];
            
            // Add recipients from contact groups
            if (!empty($request->contact_groups)) {
                $contactGroups = \App\Models\ContactGroup::whereIn('id', $request->contact_groups)
                    ->where('user_id', $user->id)
                    ->with('contacts')
                    ->get();
                
                foreach ($contactGroups as $group) {
                    foreach ($group->contacts as $contact) {
                        if (!empty($contact->phone_number)) {
                            $allRecipients[] = $contact->phone_number;
                        }
                    }
                }
            }
            
            // Remove duplicates and empty values
            $allRecipients = array_unique(array_filter($allRecipients));
            $recipientsCount = count($allRecipients);
            
            if ($recipientsCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid recipients found'
                ], 400);
            }
            
            // Check if sender name is available to the user
            $senderNameAvailable = false;
            
            $senderName = SenderName::where('name', $request->sender_name)
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->first();
                
            if ($senderName) {
                $senderNameAvailable = true;
            } else {
                $sharedSenderNames = $user->getAvailableSenderNames();
                $senderName = $sharedSenderNames->where('name', $request->sender_name)
                    ->where('status', 'approved')
                    ->first();
                    
                if ($senderName) {
                    $senderNameAvailable = true;
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
            $creditsNeeded = $smsCount * $recipientsCount;
            
            // Get the user's available SMS credits
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
                
                $campaign->update([
                    'status' => 'processing',
                    'recipients_count' => $recipientsCount,
                    'scheduled_at' => $request->scheduled_at ? date('Y-m-d H:i:s', strtotime($request->scheduled_at)) : $campaign->scheduled_at,
                ]);
            } else {
                $campaign = SmsCampaign::create([
                    'user_id' => $user->id,
                    'name' => $request->name,
                    'message' => $request->message,
                    'sender_name' => $request->sender_name,
                    'status' => 'pending',
                    'recipients_count' => $recipientsCount,
                    'scheduled_at' => $request->scheduled_at ? date('Y-m-d H:i:s', strtotime($request->scheduled_at)) : null,
                ]);
            }

            // Deduct credits before queuing the job
            if (!request()->has('_internal_call')) {
                $teamResourceService = app(\App\Services\TeamResourceService::class);
                $creditResult = $teamResourceService->deductSharedSmsCredits($user, $creditsNeeded);
                
                if (!$creditResult['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to deduct SMS credits: ' . $creditResult['message']
                    ], 400);
                }
                
                $campaign->update([
                    'credits_used' => $creditsNeeded
                ]);
            }

            // Dispatch bulk SMS job
            $job = new \App\Jobs\SendBulkSmsJob(
                $campaign->id,
                $allRecipients,
                $request->message,
                $request->sender_name
            );
            
            if ($request->scheduled_at && \Carbon\Carbon::parse($request->scheduled_at)->isAfter(now())) {
                $scheduledTime = \Carbon\Carbon::parse($request->scheduled_at);
                $delay = $scheduledTime->diffInSeconds(now());
                $job->delay($delay);
                dispatch($job);
                
                $message = 'Bulk SMS campaign scheduled for ' . $scheduledTime->format('M d, Y H:i:s');
            } else {
                dispatch($job);
                $message = 'Bulk SMS campaign is being processed';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'campaign_id' => $campaign->id,
                    'recipients_count' => $recipientsCount,
                    'estimated_cost' => $creditsNeeded,
                    'scheduled_at' => $request->scheduled_at,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Bulk SMS error: ' . $e->getMessage());

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

            $senderName = SenderName::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'status' => 'pending',
            ]);

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
                $senderName->delete();

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to register sender name: ' . $result['message']
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Sender name registration error: ' . $e->getMessage());

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
        
        $senderNames = $user->getAvailableSenderNames()
            ->sortByDesc('created_at')
            ->values();

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
     * Get user's SMS templates
     */
    public function getTemplates(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            $templates = SmsTemplate::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($template) {
                    return [
                        'id' => $template->id,
                        'name' => $template->name,
                        'content' => $template->content,
                        'description' => $template->description,
                        'created_at' => $template->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $template->updated_at->format('Y-m-d H:i:s'),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $templates
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching SMS templates: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch templates'
            ], 500);
        }
    }

    /**
     * Create a new SMS template
     */
    public function createTemplate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'content' => 'required|string|max:1000',
            'description' => 'nullable|string|max:255',
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
            
            $template = SmsTemplate::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'content' => $request->content,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Template created successfully',
                'data' => [
                    'id' => $template->id,
                    'name' => $template->name,
                    'content' => $template->content,
                    'description' => $template->description,
                    'created_at' => $template->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $template->updated_at->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating SMS template: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create template'
            ], 500);
        }
    }

    /**
     * Update an existing SMS template
     */
    public function updateTemplate(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'content' => 'required|string|max:1000',
            'description' => 'nullable|string|max:255',
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
            
            $template = SmsTemplate::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$template) {
                return response()->json([
                    'success' => false,
                    'message' => 'Template not found'
                ], 404);
            }

            $template->update([
                'name' => $request->name,
                'content' => $request->content,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Template updated successfully',
                'data' => [
                    'id' => $template->id,
                    'name' => $template->name,
                    'content' => $template->content,
                    'description' => $template->description,
                    'created_at' => $template->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $template->updated_at->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating SMS template: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update template'
            ], 500);
        }
    }

    /**
     * Delete an SMS template
     */
    public function deleteTemplate(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            
            $template = SmsTemplate::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$template) {
                return response()->json([
                    'success' => false,
                    'message' => 'Template not found'
                ], 404);
            }

            $template->delete();

            return response()->json([
                'success' => true,
                'message' => 'Template deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting SMS template: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete template'
            ], 500);
        }
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
