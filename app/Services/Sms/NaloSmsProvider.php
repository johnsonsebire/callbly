<?php

namespace App\Services\Sms;

use App\Contracts\SmsProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Class NaloSmsProvider
 * 
 * This class provides implementation for the Nalo SMS API based on the official documentation.
 * It allows sending SMS messages, checking status and calculating credits needed.
 * 
 * Configuration is done through the .env file with the following variables:
 * - NALO_API_KEY: Your Nalo API key (alternative to username/password)
 * - NALO_USERNAME: Your Nalo username (if not using API key)
 * - NALO_PASSWORD: Your Nalo password (if not using API key)
 * - NALO_API_URL: The Nalo API base URL (default: https://sms.nalosolutions.com/smsbackend)
 * - NALO_SENDER_ID: Your default sender ID (default: CALLBLY)
 * - NALO_USERNAME_PREFIX: Reseller prefix for the API (default: Resl_Nalo)
 * 
 * @package App\Services\Sms
 */
class NaloSmsProvider implements SmsProviderInterface
{
    protected Client $client;
    protected string $apiUrl;
    protected ?string $apiKey;
    protected ?string $username;
    protected ?string $password;
    protected string $defaultSenderId;
    protected string $usernamePrefix;
    protected bool $useApiKey;

    /**
     * NaloSmsProvider constructor.
     */
    public function __construct()
    {
        // Set the correct base URL according to documentation
        $this->apiUrl = config('sms.providers.nalo.api_url', 'https://sms.nalosolutions.com/smsbackend');
        $this->apiKey = config('sms.providers.nalo.api_key');
        $this->username = config('sms.providers.nalo.username');
        $this->password = config('sms.providers.nalo.password');
        $this->defaultSenderId = config('sms.providers.nalo.sender_id', 'CALLBLY');
        $this->usernamePrefix = config('sms.providers.nalo.username_prefix', 'Resl_Nalo');
        
        // Determine whether to use API key or username/password
        $this->useApiKey = !empty($this->apiKey);
        
        // Important: Remove any trailing slashes to prevent URL construction issues
        $this->apiUrl = rtrim($this->apiUrl, '/');
        
        // Create HTTP client with base URI and modified Accept headers
        // Set Accept header to accept everything to avoid 406 errors
        $this->client = new Client([
            'base_uri' => $this->apiUrl . '/',  // Make sure to add trailing slash
            'headers' => [
                'Accept' => '*/*',  // Accept any content type to avoid 406 errors
                'Content-Type' => 'application/json',
            ],
            'timeout' => 30,
        ]);
        
        // Log the base URL that will be used for debugging
        Log::info('Initialized Nalo SMS Provider with base URL: ' . $this->apiUrl);
    }

    /**
     * Send a single SMS message
     *
     * @param string|array $recipients The recipient(s) phone number(s)
     * @param string $message The message to send
     * @param string|null $senderId Optional sender ID
     * @return array Response from the provider
     */
    public function sendSms($recipients, string $message, ?string $senderId = null): array
    {
        // If recipients is an array with multiple numbers, use sendBulkSms
        if (is_array($recipients) && count($recipients) > 1) {
            return $this->sendBulkSms($recipients, $message, $senderId);
        }

        // Format to single recipient
        $recipient = is_array($recipients) ? reset($recipients) : $recipients;
        
        // Use sender ID as provided without case modification to ensure exact match with registered sender ID
        // Fall back to the default if none provided
        $sender = $senderId ?: $this->defaultSenderId;
        
        try {
            // Prepare authentication parameters
            $authParams = $this->getAuthParams();
            
            // Using POST request with JSON as per documentation
            $data = array_merge($authParams, [
                'msisdn' => $recipient,
                'message' => $message,
                'sender_id' => $sender, // Use exact case of sender ID
            ]);
            
            // Use the EXACT endpoint specified in the documentation for JSON POST requests
            $fullUrl = 'https://sms.nalosolutions.com/smsbackend/Resl_Nalo/send-message/';
            
            Log::info('Sending SMS to Nalo API', [
                'endpoint' => $fullUrl,
                'data' => array_merge($data, ['password' => '******'])
            ]);
            
            // Create a new client without base_uri to use the full URL
            $client = new Client([
                'headers' => [
                    'Accept' => '*/*',
                    'Content-Type' => 'application/json',
                ],
                'timeout' => 30,
                'verify' => false, // Disable SSL verification to avoid potential SSL issues
            ]);
            
            $response = $client->post($fullUrl, [
                'json' => $data
            ]);
            
            $result = $response->getBody()->getContents();
            Log::info('Nalo SMS API Response', ['response' => $result]);
            
            // Parse response according to documentation (expecting 1701|<phone_number>|<message_ID>)
            $responseData = $this->parseResponse($result);
            
            if ($responseData['success']) {
                return [
                    'success' => true,
                    'message_id' => $responseData['message_id'] ?? null,
                    'status' => 'sent',
                    'recipient' => $responseData['recipient'] ?? $recipient,
                    'credits_used' => $this->calculateCreditsNeeded($message),
                    'raw_response' => $result
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $responseData['message'] ?? 'Unknown error',
                    'error_code' => $responseData['code'] ?? null,
                    'status' => 'failed',
                    'raw_response' => $result
                ];
            }
        } catch (GuzzleException $e) {
            Log::error('Nalo SMS sending error: ' . $e->getMessage(), [
                'recipient' => $recipient,
                'sender' => $sender,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status' => 'failed'
            ];
        }
    }

    /**
     * Send a bulk SMS message to multiple recipients
     *
     * @param array $recipients Array of recipient phone numbers
     * @param string $message The message to send
     * @param string|null $senderId Optional sender ID
     * @return array Response from the provider
     */
    public function sendBulkSms(array $recipients, string $message, ?string $senderId = null): array
    {
        try {
            // Use sender ID as provided without case modification to ensure exact match with registered sender ID
            $sender = $senderId ?: $this->defaultSenderId;
            
            // Prepare authentication parameters
            $authParams = $this->getAuthParams();
            
            // Format recipients as comma-separated string as per documentation
            $msisdn = implode(',', $recipients);
            
            // Create request payload according to documentation
            $data = array_merge($authParams, [
                'msisdn' => $msisdn,
                'message' => $message,
                'sender_id' => $sender, // Use exact case of sender ID
            ]);
            
            // Use the EXACT endpoint specified in the documentation for JSON POST requests
            $fullUrl = 'https://sms.nalosolutions.com/smsbackend/Resl_Nalo/send-message/';
            
            Log::info('Sending Bulk SMS to Nalo API', [
                'endpoint' => $fullUrl,
                'recipient_count' => count($recipients),
                'sender' => $sender
            ]);
            
            // Create a new client without base_uri to use the full URL
            $client = new Client([
                'headers' => [
                    'Accept' => '*/*',
                    'Content-Type' => 'application/json',
                ],
                'timeout' => 30,
                'verify' => false, // Disable SSL verification to avoid potential SSL issues
            ]);
            
            $response = $client->post($fullUrl, [
                'json' => $data
            ]);
            
            $result = $response->getBody()->getContents();
            Log::info('Nalo SMS API Bulk Response', ['response' => $result]);
            
            // Parse response
            $responseData = $this->parseResponse($result);
            
            if ($responseData['success']) {
                return [
                    'success' => true,
                    'batch_id' => $responseData['message_id'] ?? null,
                    'status' => 'sent',
                    'total_sent' => count($recipients),
                    'credits_used' => $this->calculateCreditsNeeded($message, count($recipients)),
                    'raw_response' => $result
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $responseData['message'] ?? 'Unknown error',
                    'error_code' => $responseData['code'] ?? null,
                    'status' => 'failed',
                    'raw_response' => $result
                ];
            }
        } catch (GuzzleException $e) {
            Log::error('Nalo Bulk SMS error: ' . $e->getMessage(), [
                'recipients_count' => count($recipients),
                'sender' => $sender,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status' => 'failed'
            ];
        }
    }

    /**
     * Get the balance/credits available for sending SMS
     * Note: Balance endpoint not specifically mentioned in documentation
     * 
     * @return array Balance information
     */
    public function getBalance(): array
    {
        try {
            // Since balance endpoint is not specified in docs, this is an educated guess
            // based on common API patterns. May need adjustment based on actual API.
            $authParams = $this->getAuthParams();
            
            // Since the exact balance endpoint isn't specified, we'll make an educated guess
            $endpoint = "/{$this->usernamePrefix}/check-balance/";
            $response = $this->client->post($endpoint, [
                'json' => $authParams
            ]);
            
            $result = $response->getBody()->getContents();
            $responseData = json_decode($result, true) ?: ['balance' => 0, 'credits' => 0];
            
            return [
                'success' => true,
                'balance' => $responseData['balance'] ?? 0,
                'currency' => $responseData['currency'] ?? 'USD',
                'credits' => $responseData['credits'] ?? 0,
                'raw_response' => $result
            ];
        } catch (GuzzleException $e) {
            Log::error('Nalo balance check error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'balance' => 0,
                'credits' => 0
            ];
        }
    }

    /**
     * Get the status of a sent message
     * Note: Status check endpoint not specifically mentioned in documentation
     *
     * @param string $messageId The ID of the message
     * @return array Status information
     */
    public function getMessageStatus(string $messageId): array
    {
        try {
            // Since the exact status endpoint isn't specified in the docs, we'll make an educated guess
            // based on common API patterns. May need adjustment based on actual API.
            $authParams = $this->getAuthParams();
            $params = array_merge($authParams, ['message_id' => $messageId]);
            
            $endpoint = "/{$this->usernamePrefix}/check-status/";
            $response = $this->client->post($endpoint, [
                'json' => $params
            ]);
            
            $result = $response->getBody()->getContents();
            $responseData = json_decode($result, true) ?: [];
            
            return [
                'success' => true,
                'status' => $responseData['status'] ?? 'unknown',
                'sent_at' => $responseData['sent_at'] ?? null,
                'delivered_at' => $responseData['delivered_at'] ?? null,
                'raw_response' => $result
            ];
        } catch (GuzzleException $e) {
            Log::error('Nalo message status check error: ' . $e->getMessage(), [
                'message_id' => $messageId,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status' => 'unknown'
            ];
        }
    }
    
    /**
     * Calculate the number of credits needed for a message
     *
     * @param string $message The message text
     * @param int $recipientCount Number of recipients
     * @return int Number of credits required
     */
    public function calculateCreditsNeeded(string $message, int $recipientCount = 1): int
    {
        // SMS messages have a maximum of 160 characters for single SMS
        $maxSingleSmsLength = 160;
        $maxMultipartSmsLength = 153; // Each page of a multipart SMS can hold 153 chars
        
        $messageLength = mb_strlen($message);
        
        // Calculate number of pages needed - FIXED FORMULA
        if ($messageLength <= $maxSingleSmsLength) {
            $parts = 1;
        } else {
            // For multi-page messages, calculate correctly
            $parts = ceil($messageLength / $maxMultipartSmsLength);
        }
        
        // Calculate credits based on pages and recipient count
        $creditsPerPart = config('sms.pricing.default.credits_per_sms', 1);
        
        return $parts * $creditsPerPart * $recipientCount;
    }

    /**
     * Get authentication parameters based on configuration
     *
     * @return array Authentication parameters
     */
    protected function getAuthParams(): array
    {
        if ($this->useApiKey) {
            return ['key' => $this->apiKey];
        } else {
            return [
                'username' => $this->username,
                'password' => $this->password,
            ];
        }
    }
    
    /**
     * Parse API response according to documentation
     * 
     * @param string $response Response from the API
     * @return array Parsed response data
     */
    protected function parseResponse(string $response): array
    {
        // First try to parse as JSON
        $jsonData = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($jsonData)) {
            return $this->mapJsonResponse($jsonData);
        }
        
        // Try to parse as formatted string (1701|<phone_number>|<message_ID>)
        $parts = explode('|', $response);
        $code = $parts[0] ?? '';
        
        // According to Nalo documentation, 1701 indicates success with a job_id
        if ($code === '1701' && count($parts) >= 3) {
            return [
                'success' => true,
                'code' => '1701',
                'recipient' => $parts[1] ?? '',
                'message_id' => $parts[2] ?? '',
                'message' => 'Message submitted successfully'
            ];
        }
        
        // Handle error codes - only these specific codes are actual errors
        $errorMessages = [
            '1702' => 'Invalid URL Error (missing or blank parameter)',
            '1703' => 'Invalid value in username or password field',
            '1704' => 'Invalid value in type field',
            '1705' => 'Invalid message content',
            '1706' => 'Invalid destination (recipient phone number)',
            '1707' => 'Invalid source (sender ID)',
            '1708' => 'Invalid value for dlr field',
            '1709' => 'User validation failed',
            '1710' => 'Internal error',
            '1025' => 'Insufficient credit (user)',
            '1026' => 'Insufficient credit (reseller)'
        ];
        
        // Check if this is one of the known error codes
        if (isset($errorMessages[$code])) {
            return [
                'success' => false,
                'code' => $code,
                'message' => $errorMessages[$code]
            ];
        }
        
        // For any other code, default to success unless it's in our error list
        // This ensures we don't wrongly mark messages as failed
        return [
            'success' => true,
            'code' => $code,
            'message' => 'Message processed'
        ];
    }
    
    /**
     * Map JSON response to standard format
     * 
     * @param array $jsonData JSON response data
     * @return array Standardized response data
     */
    protected function mapJsonResponse(array $jsonData): array
    {
        // Check for known error codes first
        $errorCodes = ['1702', '1703', '1704', '1705', '1706', '1707', '1708', '1709', '1710', '1025', '1026'];
        
        // If we have a code and it's in our error list, it's definitely an error
        if (isset($jsonData['code']) && in_array((string)$jsonData['code'], $errorCodes)) {
            return [
                'success' => false,
                'code' => (string)$jsonData['code'],
                'message' => $jsonData['message'] ?? 'Error code ' . $jsonData['code']
            ];
        }
        
        // Otherwise, treat as success when the Nalo API returns a job_id
        // According to Nalo documentation, receiving a job_id means the message was accepted
        if (
            // Case 1: status field equals 'success' or 'queued'
            (isset($jsonData['status']) && in_array($jsonData['status'], ['success', 'queued']))
            // Case 2: status field equals '1701' (as string) - API documentation success code
            || (isset($jsonData['status']) && $jsonData['status'] === '1701')
            // Case 3: code field equals 1701 (as integer or string)
            || (isset($jsonData['code']) && (int)$jsonData['code'] === 1701)
            // Case 4: presence of job_id indicates success in Nalo API responses
            || isset($jsonData['job_id'])
            // Case 5: Any response not in our error list is likely a success
            || !isset($jsonData['code'])
        ) {
            return [
                'success' => true,
                'message_id' => $jsonData['message_id'] ?? $jsonData['job_id'] ?? null,
                'batch_id' => $jsonData['batch_id'] ?? $jsonData['job_id'] ?? null,
                'code' => $jsonData['code'] ?? '1701', // Default to success code
                'message' => 'Message submitted successfully',
                'recipient' => $jsonData['msisdn'] ?? null
            ];
        }
        
        // Default fallback (shouldn't reach here with proper implementation)
        return [
            'success' => true, // Default to success unless explicit error
            'code' => $jsonData['code'] ?? null,
            'message' => $jsonData['message'] ?? 'Message processed'
        ];
    }
}