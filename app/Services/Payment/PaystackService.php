<?php

namespace App\Services\Payment;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    protected Client $client;
    protected string $secretKey;
    protected string $publicKey;
    protected string $baseUrl;

    /**
     * PaystackService constructor.
     */
    public function __construct()
    {
        $this->secretKey = config('services.paystack.secret_key');
        $this->publicKey = config('services.paystack.public_key');
        $this->baseUrl = 'https://api.paystack.co';
        
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => "Bearer {$this->secretKey}",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'timeout' => 30,
        ]);
    }

    /**
     * Initialize a transaction.
     *
     * @param float $amount
     * @param string $email
     * @param array $metadata
     * @param string $callbackUrl
     * @return array
     */
    public function initializeTransaction(float $amount, string $email, array $metadata = [], string $callbackUrl = ''): array
    {
        try {
            // Convert amount to kobo (Paystack uses the smallest currency unit)
            $amountInKobo = $amount * 100;
            
            $response = $this->client->post('/transaction/initialize', [
                'json' => [
                    'amount' => $amountInKobo,
                    'email' => $email,
                    'callback_url' => $callbackUrl,
                    'metadata' => $metadata,
                ]
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            if (!isset($result['data']['reference'])) {
                Log::error('Paystack initialization missing reference', [
                    'response' => $result,
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Payment reference not found in response'
                ];
            }
            
            return [
                'success' => true,
                'reference' => $result['data']['reference'],
                'authorization_url' => $result['data']['authorization_url'] ?? null,
                'access_code' => $result['data']['access_code'] ?? null,
                'data' => $result['data'] ?? [],
                'message' => $result['message'] ?? 'Transaction initialized successfully'
            ];
        } catch (GuzzleException $e) {
            Log::error('Paystack transaction initialization error: ' . $e->getMessage(), [
                'amount' => $amount,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify a transaction.
     *
     * @param string $reference
     * @return array
     */
    public function verifyTransaction(string $reference): array
    {
        try {
            $response = $this->client->get("/transaction/verify/{$reference}");
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            // Check if the transaction was successful
            $isSuccess = isset($result['data']['status']) && $result['data']['status'] === 'success';
            
            return [
                'success' => $isSuccess,
                'data' => $result['data'] ?? [],
                'message' => $result['message'] ?? 'Transaction verification completed'
            ];
        } catch (GuzzleException $e) {
            Log::error('Paystack transaction verification error: ' . $e->getMessage(), [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * List transactions.
     *
     * @param array $filters
     * @return array
     */
    public function listTransactions(array $filters = []): array
    {
        try {
            $queryParams = [];
            
            foreach ($filters as $key => $value) {
                if (!empty($value)) {
                    $queryParams[$key] = $value;
                }
            }
            
            $response = $this->client->get('/transaction', [
                'query' => $queryParams
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => true,
                'data' => $result['data'] ?? [],
                'meta' => $result['meta'] ?? [],
                'message' => $result['message'] ?? 'Transactions retrieved successfully'
            ];
        } catch (GuzzleException $e) {
            Log::error('Paystack list transactions error: ' . $e->getMessage(), [
                'filters' => $filters,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Create a refund.
     *
     * @param string $transactionReference
     * @param float|null $amount
     * @param string|null $reason
     * @return array
     */
    public function createRefund(string $transactionReference, ?float $amount = null, ?string $reason = null): array
    {
        try {
            $data = ['transaction' => $transactionReference];
            
            if ($amount !== null) {
                // Convert amount to kobo (Paystack uses the smallest currency unit)
                $data['amount'] = $amount * 100;
            }
            
            if ($reason !== null) {
                $data['reason'] = $reason;
            }
            
            $response = $this->client->post('/refund', [
                'json' => $data
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => true,
                'data' => $result['data'] ?? [],
                'message' => $result['message'] ?? 'Refund initiated successfully'
            ];
        } catch (GuzzleException $e) {
            Log::error('Paystack create refund error: ' . $e->getMessage(), [
                'transaction' => $transactionReference,
                'amount' => $amount,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate a payment link.
     *
     * @param string $productName
     * @param float $amount
     * @param string $email
     * @param array $metadata
     * @return array
     */
    public function generatePaymentLink(string $productName, float $amount, string $email, array $metadata = []): array
    {
        try {
            // Convert amount to kobo (Paystack uses the smallest currency unit)
            $amountInKobo = $amount * 100;
            
            $response = $this->client->post('/page', [
                'json' => [
                    'name' => $productName,
                    'amount' => $amountInKobo,
                    'email' => $email,
                    'metadata' => $metadata,
                ]
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => true,
                'data' => $result['data'] ?? [],
                'message' => $result['message'] ?? 'Payment link generated successfully'
            ];
        } catch (GuzzleException $e) {
            Log::error('Paystack payment link generation error: ' . $e->getMessage(), [
                'product' => $productName,
                'amount' => $amount,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get transaction by ID.
     *
     * @param int $id
     * @return array
     */
    public function getTransaction(int $id): array
    {
        try {
            $response = $this->client->get("/transaction/{$id}");
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => true,
                'data' => $result['data'] ?? [],
                'message' => $result['message'] ?? 'Transaction retrieved successfully'
            ];
        } catch (GuzzleException $e) {
            Log::error('Paystack get transaction error: ' . $e->getMessage(), [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Process webhook payload.
     *
     * @param array $payload
     * @return array
     */
    public function processWebhook(array $payload): array
    {
        // Log the webhook payload
        Log::info('Paystack Webhook Payload', $payload);
        
        // Validate the webhook signature if needed
        
        // Process the webhook event
        $event = $payload['event'] ?? '';
        $data = $payload['data'] ?? [];
        
        switch ($event) {
            case 'charge.success':
                // Handle successful charge
                break;
                
            case 'transfer.success':
                // Handle successful transfer
                break;
                
            case 'transfer.failed':
                // Handle failed transfer
                break;
                
            case 'subscription.create':
                // Handle subscription creation
                break;
                
            case 'subscription.disable':
                // Handle subscription disabling
                break;
                
            default:
                // Handle unknown event
                Log::warning('Unknown Paystack webhook event', [
                    'event' => $event,
                ]);
                break;
        }
        
        return [
            'success' => true,
            'message' => 'Webhook processed successfully'
        ];
    }
}