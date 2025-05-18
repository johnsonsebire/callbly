<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UssdService
{
    protected Client $client;
    protected string $apiUrl;
    protected string $apiKey;

    /**
     * UssdService constructor.
     */
    public function __construct()
    {
        $this->apiUrl = config('services.connect_reseller.api_url');
        $this->apiKey = config('services.connect_reseller.api_key');
        
        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'headers' => [
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'timeout' => 30,
        ]);
    }

    /**
     * Register a new USSD service with the provider.
     *
     * @param int $serviceId
     * @param string $shortcode
     * @param array $menuStructure
     * @param string|null $callbackUrl
     * @return array
     */
    public function registerService(int $serviceId, string $shortcode, array $menuStructure, ?string $callbackUrl): array
    {
        try {
            $reference = 'USSD_' . Str::uuid()->toString();
            
            $response = $this->client->post('/api/v1/ussd/register', [
                'json' => [
                    'service_id' => $serviceId,
                    'shortcode' => $shortcode,
                    'menu_structure' => $menuStructure,
                    'callback_url' => $callbackUrl,
                    'reference' => $reference,
                ]
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => true,
                'reference' => $reference,
                'request_id' => $result['request_id'] ?? null,
            ];
        } catch (GuzzleException $e) {
            Log::error('USSD registration error: ' . $e->getMessage(), [
                'service_id' => $serviceId,
                'shortcode' => $shortcode,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Update an existing USSD service.
     *
     * @param int $serviceId
     * @param string $shortcode
     * @param array $menuStructure
     * @param string|null $callbackUrl
     * @return array
     */
    public function updateService(int $serviceId, string $shortcode, array $menuStructure, ?string $callbackUrl): array
    {
        try {
            $reference = 'USSD_UPDATE_' . Str::uuid()->toString();
            
            $response = $this->client->put('/api/v1/ussd/update', [
                'json' => [
                    'service_id' => $serviceId,
                    'shortcode' => $shortcode,
                    'menu_structure' => $menuStructure,
                    'callback_url' => $callbackUrl,
                    'reference' => $reference,
                ]
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => true,
                'reference' => $reference,
                'request_id' => $result['request_id'] ?? null,
            ];
        } catch (GuzzleException $e) {
            Log::error('USSD update error: ' . $e->getMessage(), [
                'service_id' => $serviceId,
                'shortcode' => $shortcode,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete a USSD service.
     *
     * @param int $serviceId
     * @param string $shortcode
     * @return array
     */
    public function deleteService(int $serviceId, string $shortcode): array
    {
        try {
            $reference = 'USSD_DELETE_' . Str::uuid()->toString();
            
            $response = $this->client->delete('/api/v1/ussd/delete', [
                'json' => [
                    'service_id' => $serviceId,
                    'shortcode' => $shortcode,
                    'reference' => $reference,
                ]
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => true,
                'reference' => $reference,
                'request_id' => $result['request_id'] ?? null,
            ];
        } catch (GuzzleException $e) {
            Log::error('USSD deletion error: ' . $e->getMessage(), [
                'service_id' => $serviceId,
                'shortcode' => $shortcode,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get analytics for a USSD service.
     *
     * @param int $serviceId
     * @param string $shortcode
     * @return array
     */
    public function getServiceAnalytics(int $serviceId, string $shortcode): array
    {
        try {
            $response = $this->client->get('/api/v1/ussd/analytics', [
                'query' => [
                    'service_id' => $serviceId,
                    'shortcode' => $shortcode,
                ]
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => true,
                'data' => $result['data'] ?? [],
            ];
        } catch (GuzzleException $e) {
            Log::error('USSD analytics error: ' . $e->getMessage(), [
                'service_id' => $serviceId,
                'shortcode' => $shortcode,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Process a USSD session callback.
     *
     * @param array $data
     * @return array
     */
    public function processUssdCallback(array $data): array
    {
        // Log the USSD callback
        Log::info('USSD Callback', $data);
        
        // Process the USSD session callback
        // This would handle menu navigation, data collection, etc.
        
        return [
            'success' => true,
            'response' => 'Your request has been processed',
        ];
    }
}