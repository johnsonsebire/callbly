<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SmsService
{
    protected Client $client;
    protected string $apiUrl;
    protected string $apiKey;

    /**
     * SmsService constructor.
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
     * Send a single SMS message.
     *
     * @param string $recipient
     * @param string $message
     * @param string $senderName
     * @param int $campaignId
     * @return array
     */
    public function sendSingle(string $recipient, string $message, string $senderName, int $campaignId): array
    {
        try {
            $reference = 'SMS_' . Str::uuid()->toString();
            
            $response = $this->client->post('/api/v1/sms/send', [
                'json' => [
                    'recipient' => $recipient,
                    'message' => $message,
                    'sender_name' => $senderName,
                    'reference' => $reference,
                    'callback_url' => config('app.url') . "/api/webhooks/sms/status/{$campaignId}",
                ]
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => true,
                'reference' => $reference,
                'message_id' => $result['message_id'] ?? null,
                'status' => $result['status'] ?? 'sent',
            ];
        } catch (GuzzleException $e) {
            Log::error('SMS sending error: ' . $e->getMessage(), [
                'recipient' => $recipient,
                'sender_name' => $senderName,
                'campaign_id' => $campaignId,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send bulk SMS messages.
     *
     * @param array $recipients
     * @param string $message
     * @param string $senderName
     * @param int $campaignId
     * @return array
     */
    public function sendBulk(array $recipients, string $message, string $senderName, int $campaignId): array
    {
        try {
            $reference = 'BULK_SMS_' . Str::uuid()->toString();
            
            $response = $this->client->post('/api/v1/sms/send-bulk', [
                'json' => [
                    'recipients' => $recipients,
                    'message' => $message,
                    'sender_name' => $senderName,
                    'reference' => $reference,
                    'callback_url' => config('app.url') . "/api/webhooks/sms/bulk-status/{$campaignId}",
                ]
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => true,
                'reference' => $reference,
                'batch_id' => $result['batch_id'] ?? null,
                'completed' => $result['completed'] ?? false,
                'total' => count($recipients),
                'delivered_count' => $result['delivered_count'] ?? 0,
                'failed_count' => $result['failed_count'] ?? 0,
            ];
        } catch (GuzzleException $e) {
            Log::error('Bulk SMS error: ' . $e->getMessage(), [
                'recipients_count' => count($recipients),
                'sender_name' => $senderName,
                'campaign_id' => $campaignId,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Register a sender name with the SMS provider.
     *
     * @param string $senderName
     * @param int $userId
     * @return array
     */
    public function registerSenderName(string $senderName, int $userId): array
    {
        try {
            $reference = 'SENDER_' . Str::uuid()->toString();
            
            $response = $this->client->post('/api/v1/senders/register', [
                'json' => [
                    'sender_name' => $senderName,
                    'reference' => $reference,
                    'user_id' => $userId,
                    'callback_url' => config('app.url') . "/api/webhooks/sender-name/status/{$senderName}",
                ]
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => true,
                'reference' => $reference,
                'request_id' => $result['request_id'] ?? null,
            ];
        } catch (GuzzleException $e) {
            Log::error('Sender name registration error: ' . $e->getMessage(), [
                'sender_name' => $senderName,
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Check the status of a sender name.
     *
     * @param string $senderName
     * @return array
     */
    public function checkSenderNameStatus(string $senderName): array
    {
        try {
            $response = $this->client->get('/api/v1/senders/status', [
                'query' => [
                    'sender_name' => $senderName,
                ]
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => true,
                'status' => $result['status'] ?? 'unknown',
                'reason' => $result['reason'] ?? null,
            ];
        } catch (GuzzleException $e) {
            Log::error('Sender name status check error: ' . $e->getMessage(), [
                'sender_name' => $senderName,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Process a delivery report callback for SMS.
     *
     * @param array $data
     * @return bool
     */
    public function processDeliveryReport(array $data): bool
    {
        // Log the delivery report
        Log::info('SMS Delivery Report', $data);
        
        // Process the delivery report
        // This would update the SMS campaign status, etc.
        
        return true;
    }
}