<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactCenterCall;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ContactCenterController extends Controller
{
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

            // Deduct one credit
            $user->update([
                'call_credits' => $user->call_credits - 1,
            ]);

            // Queue the call (this would typically connect to a telephony API)
            // This is a simplified example - in production, we would use a queue and a job
            // to handle the actual call initiation with a service like Twilio or Vonage
            
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
     * Get a list of calls for the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getCalls(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'direction' => 'nullable|in:inbound,outbound,all',
            'status' => 'nullable|in:queued,ringing,in-progress,completed,failed,no-answer,busy',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
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
            $query = ContactCenterCall::where('user_id', $user->id);
            
            // Apply filters
            if ($request->has('direction') && $request->direction !== 'all') {
                $query->where('direction', $request->direction);
            }
            
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->has('from_date')) {
                $query->where('created_at', '>=', $request->from_date);
            }
            
            if ($request->has('to_date')) {
                $query->where('created_at', '<=', $request->to_date);
            }
            
            // Order by most recent first
            $query->orderBy('created_at', 'desc');
            
            // Paginate results
            $limit = $request->limit ?? 15;
            $calls = $query->paginate($limit);
            
            return response()->json([
                'success' => true,
                'data' => $calls
            ]);
        } catch (\Exception $e) {
            Log::error('Get calls error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving calls'
            ], 500);
        }
    }

    /**
     * Get details of a specific call.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function getCallDetails(Request $request, int $id): JsonResponse
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

            return response()->json([
                'success' => true,
                'data' => $call
            ]);
        } catch (\Exception $e) {
            Log::error('Get call details error: ' . $e->getMessage(), [
                'exception' => $e,
                'call_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving call details'
            ], 500);
        }
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

            if (!$call->recording_enabled || !isset($call->metadata['recording_url'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Recording not available for this call'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'recording_url' => $call->metadata['recording_url'],
                    'duration' => $call->metadata['duration'] ?? null,
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

            // Update call status
            $call->update([
                'status' => 'completed',
                'metadata' => array_merge($call->metadata ?? [], [
                    'ended_at' => now()->toIso8601String(),
                    'ended_by' => 'user',
                ])
            ]);

            // Send termination request to telephony API
            // This is a placeholder for actual call termination logic
            
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
            
            // Extract call reference from the webhook data
            $reference = $data['reference_id'] ?? null;
            
            if (!$reference) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reference ID is required'
                ], 400);
            }
            
            // Find the call by reference ID
            $call = ContactCenterCall::where('reference_id', $reference)->first();
            
            if (!$call) {
                return response()->json([
                    'success' => false,
                    'message' => 'Call not found'
                ], 404);
            }
            
            // Update the call status and metadata
            $call->update([
                'status' => $data['status'] ?? $call->status,
                'duration' => $data['duration'] ?? $call->duration,
                'metadata' => array_merge($call->metadata ?? [], [
                    'provider_call_id' => $data['provider_call_id'] ?? null,
                    'recording_url' => $data['recording_url'] ?? null,
                    'call_events' => array_merge($call->metadata['call_events'] ?? [], [
                        [
                            'event' => $data['event'] ?? 'status_update',
                            'timestamp' => now()->toIso8601String(),
                            'data' => $data
                        ]
                    ])
                ])
            ]);
            
            // If callback URL is set, forward the webhook data
            if ($call->callback_url) {
                // In a production environment, this would be handled by a queue job
                // to ensure reliable delivery and retry logic
                // For now, we'll just log it
                Log::info('Forwarding webhook to callback URL', [
                    'callback_url' => $call->callback_url,
                    'data' => $data
                ]);
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
