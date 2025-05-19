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
 * This class provides implementation for the Nalo SMS API.
 * It allows sending SMS messages, checking message status,
 * retrieving account balance and calculating SMS credits.
 * 
 * Configuration is done through the .env file with the following variables:
 * - NALO_API_KEY: Your Nalo API key
 * - NALO_API_URL: The Nalo API endpoint (default: https://api.nalosms.com)
 * - NALO_SENDER_ID: Your default sender ID (default: CALLBLY)
 * 
 * @package App\Services\Sms
 */
class NaloSmsProvider implements SmsProviderInterface
{
    protected Client $client;
    protected string $apiUrl;
    protected string $apiKey;
    protected string $defaultSenderId;

    /**
     * NaloSmsProvider constructor.
     */
    public function __construct()
    {
        $this->apiUrl = config('sms.providers.nalo.api_url');
        $this->apiKey = config('sms.providers.nalo.api_key');
        $this->defaultSenderId = config('sms.providers.nalo.sender_id');
        
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
        
        try {
            $reference = 'SMS_' . Str::uuid()->toString();
            $sender = $senderId ?: $this->defaultSenderId;
            
            $response = $this->client->post('/api/v1/sms/send', [
                'json' => [
                    'recipient' => $recipient,
                    'message' => $message,
                    'sender_id' => $sender,
                    'reference' => $reference,
                ]
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => $result['status'] === 'success' || $result['status'] === 'queued',
                'message_id' => $result['message_id'] ?? $reference,
                'status' => $result['status'] ?? 'unknown',
                'cost' => $result['cost'] ?? null,
                'credits_used' => $result['credits_used'] ?? $this->calculateCreditsNeeded($message),
                'raw_response' => $result
            ];
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
            $batchId = 'BATCH_' . Str::uuid()->toString();
            $sender = $senderId ?: $this->defaultSenderId;
            
            $response = $this->client->post('/api/v1/sms/bulk', [
                'json' => [
                    'recipients' => $recipients,
                    'message' => $message,
                    'sender_id' => $sender,
                    'batch_id' => $batchId,
                ]
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => $result['status'] === 'success' || $result['status'] === 'queued',
                'batch_id' => $result['batch_id'] ?? $batchId,
                'status' => $result['status'] ?? 'unknown',
                'total_sent' => $result['total_sent'] ?? count($recipients),
                'total_cost' => $result['total_cost'] ?? null,
                'credits_used' => $result['credits_used'] ?? $this->calculateCreditsNeeded($message, count($recipients)),
                'raw_response' => $result
            ];
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
     *
     * @return array Balance information
     */
    public function getBalance(): array
    {
        try {
            $response = $this->client->get('/api/v1/account/balance');
            $result = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => true,
                'balance' => $result['balance'] ?? 0,
                'currency' => $result['currency'] ?? 'USD',
                'credits' => $result['credits'] ?? 0,
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
     *
     * @param string $messageId The ID of the message
     * @return array Status information
     */
    public function getMessageStatus(string $messageId): array
    {
        try {
            $response = $this->client->get('/api/v1/sms/status', [
                'query' => [
                    'message_id' => $messageId
                ]
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => true,
                'status' => $result['status'] ?? 'unknown',
                'sent_at' => $result['sent_at'] ?? null,
                'delivered_at' => $result['delivered_at'] ?? null,
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
        $maxMultipartSmsLength = 153; // Each part of a multipart SMS can hold 153 chars
        
        $messageLength = mb_strlen($message);
        
        // Calculate number of parts needed
        if ($messageLength <= $maxSingleSmsLength) {
            $parts = 1;
        } else {
            $parts = ceil($messageLength / $maxMultipartSmsLength);
        }
        
        // Calculate credits based on parts and recipient count
        $creditsPerPart = config('sms.pricing.default.credits_per_sms', 1);
        
        return $parts * $creditsPerPart * $recipientCount;
    }
}