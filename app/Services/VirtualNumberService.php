<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VirtualNumberService
{
    protected string $baseUrl;
    protected string $apiKey;
    
    /**
     * Constructor for VirtualNumberService.
     */
    public function __construct()
    {
        $this->baseUrl = config('services.telephony.base_url') ?? 'https://api.default-telephony.com';
        $this->apiKey = config('services.telephony.api_key') ?? '';
        
        if (empty($this->baseUrl) || empty($this->apiKey)) {
            \Log::warning('Telephony service not properly configured. Please check TELEPHONY_API_URL and TELEPHONY_API_KEY in .env');
        }
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
     * Get available virtual numbers.
     *
     * @param string|null $country Country code (e.g., NG, KE, GH)
     * @param string|null $type Number type (e.g., local, toll-free)
     * @param int $page Page number for pagination
     * @param int $perPage Items per page
     * @return array Response with success status and data
     */
    public function getAvailableNumbers(?string $country = null, ?string $type = null, int $page = 1, int $perPage = 20): array
    {
        try {
            $queryParams = [
                'page' => $page,
                'per_page' => $perPage
            ];
            
            if ($country) {
                $queryParams['country'] = $country;
            }
            
            if ($type) {
                $queryParams['type'] = $type;
            }
            
            $response = $this->client()->get('/numbers/available', $queryParams);
            
            if ($response->failed()) {
                Log::error('Failed to get available numbers', [
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
            Log::error('Exception while getting available numbers', [
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to connect to telephony provider: ' . $e->getMessage(),
            ];
        }
    }
    
    /**
     * Purchase a virtual number.
     *
     * @param string $number The number to purchase
     * @param array $options Additional options
     * @return array Response with success status and data
     */
    public function purchaseNumber(string $number, array $options = []): array
    {
        try {
            $response = $this->client()->post('/numbers/purchase', [
                'number' => $number,
                'forward_to' => $options['forward_to'] ?? null,
                'forward_sms' => $options['forward_sms'] ?? false,
                'forward_voice' => $options['forward_voice'] ?? false,
                'callback_url' => $options['callback_url'] ?? null,
            ]);
            
            if ($response->failed()) {
                Log::error('Failed to purchase number', [
                    'number' => $number,
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
            Log::error('Exception while purchasing number', [
                'number' => $number,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to connect to telephony provider: ' . $e->getMessage(),
            ];
        }
    }
    
    /**
     * Configure a virtual number.
     *
     * @param string $numberId The number ID
     * @param array $config Configuration options
     * @return array Response with success status
     */
    public function configureNumber(string $numberId, array $config): array
    {
        try {
            $response = $this->client()->put("/numbers/{$numberId}/configure", $config);
            
            if ($response->failed()) {
                Log::error('Failed to configure number', [
                    'number_id' => $numberId,
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
                'message' => 'Number configured successfully',
                'data' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('Exception while configuring number', [
                'number_id' => $numberId,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to connect to telephony provider: ' . $e->getMessage(),
            ];
        }
    }
    
    /**
     * Release a virtual number.
     *
     * @param string $numberId The number ID to release
     * @return array Response with success status
     */
    public function releaseNumber(string $numberId): array
    {
        try {
            $response = $this->client()->delete("/numbers/{$numberId}");
            
            if ($response->failed()) {
                Log::error('Failed to release number', [
                    'number_id' => $numberId,
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
                'message' => 'Number released successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Exception while releasing number', [
                'number_id' => $numberId,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to connect to telephony provider: ' . $e->getMessage(),
            ];
        }
    }
    
    /**
     * Get usage logs for a virtual number.
     *
     * @param string $numberId The number ID
     * @param string|null $startDate Start date (YYYY-MM-DD)
     * @param string|null $endDate End date (YYYY-MM-DD)
     * @param int $page Page number for pagination
     * @param int $perPage Items per page
     * @return array Response with success status and data
     */
    public function getNumberUsageLogs(string $numberId, ?string $startDate = null, ?string $endDate = null, int $page = 1, int $perPage = 20): array
    {
        try {
            $queryParams = [
                'page' => $page,
                'per_page' => $perPage
            ];
            
            if ($startDate) {
                $queryParams['start_date'] = $startDate;
            }
            
            if ($endDate) {
                $queryParams['end_date'] = $endDate;
            }
            
            $response = $this->client()->get("/numbers/{$numberId}/logs", $queryParams);
            
            if ($response->failed()) {
                Log::error('Failed to get number usage logs', [
                    'number_id' => $numberId,
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
            Log::error('Exception while getting number usage logs', [
                'number_id' => $numberId,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to connect to telephony provider: ' . $e->getMessage(),
            ];
        }
    }
}