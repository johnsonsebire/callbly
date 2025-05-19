<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactCenterCall;
use App\Models\User;
use App\Services\ContactCenter\ContactCenterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ContactCenterController extends Controller
{
    protected ContactCenterService $contactCenterService;

    /**
     * Create a new controller instance.
     * 
     * @param ContactCenterService $contactCenterService
     */
    public function __construct(ContactCenterService $contactCenterService)
    {
        $this->contactCenterService = $contactCenterService;
    }

    /**
     * Initiate a new outbound call.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function initiateCall(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'to_number' => 'required|string|max:20',
            'from_number' => 'required|string|max:20',
            'callback_url' => 'nullable|url',
            'recording_enabled' => 'nullable|boolean',
            'call_timeout' => 'nullable|integer|min:10|max:300',
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
            
            // Check if user has sufficient credits
            if ($user->call_credits < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient call credits'
                ], 400);
            }

            // Generate unique reference ID for this call
            $reference = 'CALL_' . Str::uuid()->toString();
            
            // Create call record
            $call = ContactCenterCall::create([
                'user_id' => $user->id,
                'to_number' => $request->to_number,
                'from_number' => $request->from_number,
                'direction' => 'outbound',
                'status' => 'queued',
                'reference_id' => $reference,
                'recording_enabled' => $request->recording_enabled ?? false,
                'callback_url' => $request->callback_url,
                'call_timeout' => $request->call_timeout ?? 60,
                'metadata' => [
                    'initiated_at' => now()->toIso8601String(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ],
            ]);

            // Use the service to initiate the call
            $callResult = $this->contactCenterService->initiateCall(
                $request->from_number,
                $request->to_number,
                [
                    'recording' => $request->recording_enabled ?? false,
                    'timeout' => $request->call_timeout ?? 60,
                    'referenceId' => $reference
                ]
            );

            if (!$callResult['success']) {
                // If call initiation failed, update the call status
                $call->update([
                    'status' => 'failed',
                    'metadata' => array_merge($call->metadata, [
                        'failure_reason' => $callResult['message']
                    ])
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to initiate call: ' . $callResult['message']
                ], 500);
            }

            // Deduct one credit
            $user->update([
                'call_credits' => $user->call_credits - 1,
            ]);
            
            // Update call with provider details
            $call->update([
                'metadata' => array_merge($call->metadata, [
                    'provider_call_id' => $callResult['data']['call_id'] ?? null,
                    'provider_status' => $callResult['data']['status'] ?? 'queued'
                ])
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Call successfully queued',
                'data' => [
                    'call_id' => $call->id,
                    'reference' => $reference,
                    'status' => $call->status,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Call initiation error: ' . $e->getMessage(), [
                'exception' => $e,
                'to_number' => $request->to_number,
                'from_number' => $request->from_number,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while initiating the call'
            ], 500);
        }
    }

    /**
     * Get all calls for the authenticated user.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getCalls(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $user = Auth::user();
        
        $calls = ContactCenterCall::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $calls
        ]);
    }

    /**
     * Get details of a specific call.
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function getCallDetails(string $id): JsonResponse
    {
        $user = Auth::user();
        
        $call = ContactCenterCall::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        
        if (!$call) {
            return response()->json([
                'success' => false,
                'message' => 'Call not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $call
        ]);
    }

    /**
     * Get call recording URL.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function getCallRecording(Request $request, int $id): JsonResponse
    {
        try {
            $user = $request->user();
            $call = ContactCenterCall::where('user_id', $user->id)
                ->where('id', $id)
                ->first();

            if (!$call) {
                return response()->json([
                    'success' => false,
                    'message' => 'Call not found'
                ], 404);
            }

            if (!$call->recording_enabled || empty($call->metadata['recording_id'] ?? null)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Recording not available for this call'
                ], 400);
            }

            // Use the service to fetch recording details
            $recordingResult = $this->contactCenterService->getRecording($call->metadata['recording_id']);
            
            if (!$recordingResult['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to retrieve recording: ' . $recordingResult['message']
                ], 500);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'recording_url' => $recordingResult['data']['url'] ?? null,
                    'duration' => $recordingResult['data']['duration'] ?? null,
                    'created_at' => $recordingResult['data']['created_at'] ?? null,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get call recording error: ' . $e->getMessage(), [
                'exception' => $e,
                'call_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the call recording'
            ], 500);
        }
    }

    /**
     * End an ongoing call.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function endCall(Request $request, int $id): JsonResponse
    {
        try {
            $user = $request->user();
            $call = ContactCenterCall::where('user_id', $user->id)
                ->where('id', $id)
                ->whereIn('status', ['queued', 'ringing', 'in-progress'])
                ->first();

            if (!$call) {
                return response()->json([
                    'success' => false,
                    'message' => 'Call not found or cannot be terminated'
                ], 404);
            }

            // Check if we have a provider call ID to terminate
            if (empty($call->metadata['provider_call_id'] ?? null)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Provider call ID not available for termination'
                ], 400);
            }

            // Use service to terminate the call
            $terminateResult = $this->contactCenterService->endCall($call->metadata['provider_call_id']);
            
            if (!$terminateResult['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to terminate call: ' . $terminateResult['message']
                ], 500);
            }

            // Update call status
            $call->update([
                'status' => 'completed',
                'metadata' => array_merge($call->metadata ?? [], [
                    'ended_at' => now()->toIso8601String(),
                    'ended_by' => 'user',
                ])
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Call terminated successfully',
                'data' => [
                    'call_id' => $call->id,
                    'status' => $call->status,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('End call error: ' . $e->getMessage(), [
                'exception' => $e,
                'call_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while ending the call'
            ], 500);
        }
    }

    /**
     * Process call status webhook from telephony provider.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function callStatusWebhook(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            
            // Log the webhook data
            Log::info('Call Status Webhook', $data);
            
            // Use the service to process the webhook event
            $result = $this->contactCenterService->processCallEvent($data);
            
            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Call status webhook error: ' . $e->getMessage(), [
                'exception' => $e,
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the webhook'
            ], 500);
        }
    }
}
