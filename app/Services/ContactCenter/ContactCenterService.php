<?php

namespace App\Services\ContactCenter;

use App\Models\ContactCenterCall;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ContactCenterService
{
    protected string $baseUrl;
    protected string $apiKey;
    
    /**
     * Constructor for ContactCenterService.
     */
    public function __construct()
    {
        $this->baseUrl = config('services.telephony.base_url');
        $this->apiKey = config('services.telephony.api_key');
    }
    
    /**
     * Create an HTTP client with auth headers.
     *
     * @return PendingRequest
     */
    protected function client(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->baseUrl($this->baseUrl);
    }
    
    /**
     * Initiate a new call between two numbers.
     *
     * @param string $fromNumber Sender phone number
     * @param string $toNumber Recipient phone number
     * @param array $options Additional call options
     * @return array Response with success status and data
     */
    public function initiateCall(string $fromNumber, string $toNumber, array $options = []): array
    {
        try {
            $response = $this->client()->post('/calls', [
                'from' => $fromNumber,
                'to' => $toNumber,
                'record' => $options['recording'] ?? false,
                'timeout' => $options['timeout'] ?? 60,
                'callback_url' => route('api.calls.webhook'),
                'reference_id' => $options['referenceId'] ?? null,
            ]);
            
            if ($response->failed()) {
                Log::error('Failed to initiate call', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Telephony provider returned error: ' . ($response->json()['message'] ?? 'Unknown error'),
                ];
            }
            
            return [
                'success' => true,
                'data' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('Exception while initiating call', [
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to connect to telephony provider: ' . $e->getMessage(),
            ];
        }
    }
    
    /**
     * End an ongoing call.
     *
     * @param string $callId The provider call ID
     * @return array Response with success status
     */
    public function endCall(string $callId): array
    {
        try {
            $response = $this->client()->post("/calls/{$callId}/terminate");
            
            if ($response->failed()) {
                Log::error('Failed to end call', [
                    'call_id' => $callId,
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Telephony provider returned error: ' . ($response->json()['message'] ?? 'Unknown error'),
                ];
            }
            
            return [
                'success' => true,
                'message' => 'Call terminated successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Exception while ending call', [
                'call_id' => $callId,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to connect to telephony provider: ' . $e->getMessage(),
            ];
        }
    }
    
    /**
     * Get recording for a specific call.
     *
     * @param string $recordingId The recording ID
     * @return array Response with success status and recording data
     */
    public function getRecording(string $recordingId): array
    {
        try {
            $response = $this->client()->get("/recordings/{$recordingId}");
            
            if ($response->failed()) {
                Log::error('Failed to get recording', [
                    'recording_id' => $recordingId,
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Telephony provider returned error: ' . ($response->json()['message'] ?? 'Unknown error'),
                ];
            }
            
            return [
                'success' => true,
                'data' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('Exception while getting recording', [
                'recording_id' => $recordingId,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to connect to telephony provider: ' . $e->getMessage(),
            ];
        }
    }
    
    /**
     * Process call event webhook from telephony provider.
     *
     * @param array $data The webhook payload
     * @return array Response with success status
     */
    public function processCallEvent(array $data): array
    {
        $callId = $data['call_id'] ?? null;
        $referenceId = $data['reference_id'] ?? null;
        $eventType = $data['event'] ?? null;
        
        if (!$referenceId && !$callId) {
            Log::error('Call event missing identifiers', [
                'data' => $data,
            ]);
            
            return [
                'success' => false,
                'message' => 'Missing call identifiers',
            ];
        }
        
        // Find the call record
        $query = ContactCenterCall::query();
        
        if ($referenceId) {
            $query->where('reference_id', $referenceId);
        } else {
            $query->whereJsonContains('metadata->provider_call_id', $callId);
        }
        
        $call = $query->first();
        
        if (!$call) {
            Log::error('Call not found for event', [
                'reference_id' => $referenceId,
                'call_id' => $callId,
                'event' => $eventType,
            ]);
            
            return [
                'success' => false,
                'message' => 'Call record not found',
            ];
        }
        
        // Update call status based on event
        switch ($eventType) {
            case 'call.queued':
                $status = 'queued';
                break;
            case 'call.ringing':
                $status = 'ringing';
                break;
            case 'call.in-progress':
                $status = 'in-progress';
                break;
            case 'call.completed':
                $status = 'completed';
                break;
            case 'call.failed':
                $status = 'failed';
                break;
            case 'call.busy':
                $status = 'busy';
                break;
            case 'call.no-answer':
                $status = 'no-answer';
                break;
            case 'recording.available':
                // Don't change status, just update recording info
                $call->update([
                    'metadata' => array_merge($call->metadata ?? [], [
                        'recording_url' => $data['recording_url'] ?? null,
                        'recording_id' => $data['recording_id'] ?? null,
                        'recording_duration' => $data['duration'] ?? null,
                    ])
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Recording information updated',
                ];
            default:
                Log::warning('Unknown call event type', [
                    'event' => $eventType,
                    'data' => $data,
                ]);
                return [
                    'success' => false,
                    'message' => 'Unknown event type',
                ];
        }
        
        // Update call
        $call->update([
            'status' => $status,
            'metadata' => array_merge($call->metadata ?? [], [
                'last_event' => $eventType,
                'last_event_time' => now()->toIso8601String(),
                'duration' => $data['duration'] ?? null,
                // If provider call ID is not set but is in the webhook, update it
                'provider_call_id' => $call->metadata['provider_call_id'] ?? $callId,
            ])
        ]);
        
        // If there's a callback URL for this call, forward the event
        if ($call->callback_url) {
            try {
                Http::post($call->callback_url, [
                    'call_id' => $call->id,
                    'reference_id' => $call->reference_id,
                    'status' => $status,
                    'event' => $eventType,
                    'timestamp' => now()->toIso8601String(),
                    'duration' => $data['duration'] ?? null,
                    'recording_url' => $call->metadata['recording_url'] ?? null,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send callback', [
                    'call_id' => $call->id,
                    'url' => $call->callback_url,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        return [
            'success' => true,
            'message' => 'Call event processed',
        ];
    }
}