<?php

namespace App\Http\Controllers;

use App\Models\ContactCenterCall;
use App\Models\User;
use App\Services\ContactCenter\ContactCenterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ContactCenterController extends Controller
{
    /**
     * Display the contact center dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Get today's calls
        $todaysCalls = ContactCenterCall::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->get();

        $todayCalls = $todaysCalls->count();
        $successfulCalls = $todaysCalls->where('status', 'completed')->count();
        $failedCalls = $todaysCalls->where('status', 'failed')->count();

        // Calculate success and failure rates
        $successRate = $todayCalls > 0 ? round(($successfulCalls / $todayCalls) * 100) : 0;
        $failureRate = $todayCalls > 0 ? round(($failedCalls / $todayCalls) * 100) : 0;

        // Get total call minutes used
        $totalMinutes = ContactCenterCall::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('duration') / 60; // Convert seconds to minutes

        // Get total allowed minutes from user's plan
        $totalAllowedMinutes = $user->service_plan ? $user->service_plan->monthly_minutes : 0;

        // Calculate credit usage percentage for today
        $creditsUsedToday = $todaysCalls->count();
        $totalCredits = $user->call_credits;
        $creditUsagePercent = $totalCredits > 0 ? round(($creditsUsedToday / $totalCredits) * 100) : 0;

        // Get recent calls
        $recentCalls = ContactCenterCall::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('contact-center.dashboard', compact(
            'todayCalls',
            'successRate',
            'failureRate',
            'totalCredits',
            'creditUsagePercent',
            'totalMinutes',
            'totalAllowedMinutes',
            'successfulCalls',
            'failedCalls',
            'recentCalls'
        ));
    }

    /**
     * Initiate a new call from the web interface.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function initiateCall(Request $request)
    {
        $request->validate([
            'from_number' => 'required|string|max:20',
            'to_number' => 'required|string|max:20',
            'recording_enabled' => 'nullable|boolean'
        ]);

        $user = Auth::user();
        
        // Check if user has sufficient credits
        if ($user->call_credits < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient call credits'
            ], 400);
        }

        try {
            // Generate unique reference ID for this call
            $reference = 'CALL_' . Str::uuid()->toString();
            
            // Create call record
            $call = ContactCenterCall::create([
                'user_id' => $user->id,
                'from_number' => $request->from_number,
                'to_number' => $request->to_number,
                'direction' => 'outbound',
                'status' => 'queued',
                'reference_id' => $reference,
                'recording_enabled' => $request->recording_enabled ?? false,
                'metadata' => [
                    'initiated_at' => now()->toIso8601String(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ],
            ]);

            // Deduct one credit
            $user->decrement('call_credits');
            
            return response()->json([
                'success' => true,
                'message' => 'Call initiated successfully',
                'data' => [
                    'call_id' => $call->id,
                    'reference' => $reference,
                    'status' => $call->status,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while initiating the call'
            ], 500);
        }
    }

    /**
     * Get the recording URL for a call.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCallRecording($id)
    {
        $user = Auth::user();
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

        try {
            // Get the recording URL using our service
            $service = app(ContactCenterService::class);
            $recordingResult = $service->getRecording($call->metadata['recording_id']);

            if (!$recordingResult['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to retrieve recording: ' . $recordingResult['message']
                ], 500);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'recording_url' => $recordingResult['data']['url'],
                    'duration' => $recordingResult['data']['duration'],
                    'created_at' => $recordingResult['data']['created_at'],
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the recording'
            ], 500);
        }
    }

    /**
     * End an ongoing call.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function endCall($id)
    {
        $user = Auth::user();
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

        try {
            // End the call using our service
            $service = app(ContactCenterService::class);
            $result = $service->endCall($call->metadata['provider_call_id'] ?? '');

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to terminate call: ' . $result['message']
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
                'message' => 'Call terminated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while terminating the call'
            ], 500);
        }
    }
}