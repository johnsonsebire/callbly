<?php

namespace App\Services;

use App\Contracts\SmsProviderInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SmsService
{
    protected SmsProviderInterface $provider;

    /**
     * SmsService constructor.
     * 
     * @param SmsProviderInterface $provider
     */
    public function __construct(SmsProviderInterface $provider)
    {
        $this->provider = $provider;
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
            
            // Clean and format the phone number to ensure it's in the correct format
            $formattedRecipient = $this->formatPhoneNumber($recipient);
            
            // Use the configured SMS provider interface
            $result = $this->provider->sendSms($formattedRecipient, $message, $senderName);
            
            // Log campaign info with the result for tracking
            Log::info('SMS sent via provider', [
                'campaign_id' => $campaignId,
                'recipient' => $formattedRecipient,
                'sender' => $senderName,
                'reference' => $reference,
                'provider_response' => $result
            ]);
            
            // Create recipient record if campaign ID is provided
            if ($campaignId > 0) {
                $this->createSmsRecipient($campaignId, $formattedRecipient, $result);
            }
            
            return [
                'success' => $result['success'] ?? false,
                'reference' => $reference,
                'message_id' => $result['message_id'] ?? null,
                'status' => $result['status'] ?? 'sent',
            ];
        } catch (\Exception $e) {
            Log::error('SMS sending error: ' . $e->getMessage(), [
                'recipient' => $recipient,
                'sender_name' => $senderName,
                'campaign_id' => $campaignId,
                'error' => $e->getMessage(),
            ]);
            
            // Record failed recipient if campaign ID is provided
            if ($campaignId > 0) {
                $this->createSmsRecipient($campaignId, $recipient, [
                    'success' => false,
                    'error' => $e->getMessage(),
                    'status' => 'failed'
                ]);
            }
            
            return [
                'success' => false,
                'message' => 'Failed to send SMS: ' . $e->getMessage()
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
            
            // Format all phone numbers correctly
            $formattedRecipients = array_map([$this, 'formatPhoneNumber'], $recipients);
            
            // Use the configured SMS provider interface
            $result = $this->provider->sendBulkSms($formattedRecipients, $message, $senderName);
            
            // Log campaign info with the result for tracking
            Log::info('Bulk SMS sent via provider', [
                'campaign_id' => $campaignId,
                'recipients_count' => count($formattedRecipients),
                'sender' => $senderName,
                'reference' => $reference,
                'provider_response' => $result
            ]);
            
            // Create recipient records if campaign ID is provided
            if ($campaignId > 0) {
                // Create recipient records for all recipients
                foreach ($formattedRecipients as $recipient) {
                    $this->createSmsRecipient($campaignId, $recipient, $result);
                }
                
                // Update campaign statistics
                $this->updateCampaignStatistics($campaignId, $result, count($formattedRecipients));
            }
            
            return [
                'success' => $result['success'] ?? false,
                'reference' => $reference,
                'batch_id' => $result['batch_id'] ?? null,
                'completed' => $result['status'] === 'completed',
                'total' => count($formattedRecipients),
                'delivered_count' => $result['total_sent'] ?? 0,
                'failed_count' => (count($formattedRecipients) - ($result['total_sent'] ?? 0)),
            ];
        } catch (\Exception $e) {
            Log::error('Bulk SMS error: ' . $e->getMessage(), [
                'recipients_count' => count($recipients),
                'sender_name' => $senderName,
                'campaign_id' => $campaignId,
                'error' => $e->getMessage(),
            ]);
            
            // Record all recipients as failed if campaign ID is provided
            if ($campaignId > 0) {
                foreach ($recipients as $recipient) {
                    $this->createSmsRecipient($campaignId, $recipient, [
                        'success' => false,
                        'error' => $e->getMessage(),
                        'status' => 'failed'
                    ]);
                }
            }
            
            return [
                'success' => false,
                'message' => 'Failed to send SMS: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Calculate the number of credits needed for a message.
     *
     * @param string $message The message text
     * @param int $recipientCount Number of recipients
     * @return int Number of credits required
     */
    public function calculateCreditsNeeded(string $message, int $recipientCount = 1): int
    {
        // Use the provider's calculation method if available
        if (method_exists($this->provider, 'calculateCreditsNeeded')) {
            return $this->provider->calculateCreditsNeeded($message, $recipientCount);
        }
        
        // Fallback calculation
        // SMS messages have a maximum of 160 characters for single SMS
        $maxSingleSmsLength = 160;
        $maxMultipartSmsLength = 153; // Each part of a multipart SMS can hold 153 chars
        
        $messageLength = mb_strlen($message);
        
        // Calculate number of parts needed
        if ($messageLength <= $maxSingleSmsLength) {
            $parts = 1;
        } else {
            $parts = ceil(($messageLength - $maxSingleSmsLength) / $maxMultipartSmsLength) + 1;
        }
        
        // Calculate credits based on parts and recipient count
        return $parts * $recipientCount;
    }

    /**
     * Get the balance/credits available for sending SMS.
     *
     * @return array Balance information
     */
    public function getBalance(): array
    {
        try {
            return $this->provider->getBalance();
        } catch (\Exception $e) {
            Log::error('Error checking SMS balance: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'balance' => 0,
                'credits' => 0
            ];
        }
    }

    /**
     * Get the status of a sent message.
     *
     * @param string $messageId The ID of the message
     * @return array Status information
     */
    public function getMessageStatus(string $messageId): array
    {
        try {
            return $this->provider->getMessageStatus($messageId);
        } catch (\Exception $e) {
            Log::error('Error checking message status: ' . $e->getMessage(), [
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

    /**
     * Format a phone number to ensure it's in the correct format.
     *
     * @param string $phoneNumber
     * @return string
     */
    protected function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove any non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // If it starts with a plus sign, add it back
        if (str_starts_with($phoneNumber, '+')) {
            return '+' . $cleaned;
        }
        
        // If it starts with a country code (e.g. 233), keep it as is
        if (strlen($cleaned) >= 11 && preg_match('/^(233|234|235|1|44)/', $cleaned)) {
            return $cleaned;
        }
        
        // Otherwise assume Ghana and add the country code
        // Remove leading zeros if they exist
        $cleaned = ltrim($cleaned, '0');
        return '233' . $cleaned;
    }

    /**
     * Create a record for an SMS recipient.
     *
     * @param int $campaignId
     * @param string $recipient
     * @param array $result
     * @return void
     */
    protected function createSmsRecipient(int $campaignId, string $recipient, array $result): void
    {
        try {
            // Determine recipient status based on the SMS result
            $status = 'pending';
            if ($result['success'] ?? false) {
                $status = 'sent';
            } elseif (isset($result['error'])) {
                $status = 'failed';
            }
            
            // Create the recipient record
            $recipientRecord = new \App\Models\SmsRecipient([
                'campaign_id' => $campaignId,
                'phone_number' => $recipient,
                'status' => $status,
                'provider_message_id' => $result['message_id'] ?? $result['batch_id'] ?? null,
                'error_message' => $result['error'] ?? null,
            ]);
            
            // Save the recipient record
            $recipientRecord->save();
            
            // If the message was sent successfully, update the campaign statistics
            if ($status === 'sent') {
                // Update campaign with single recipient
                $this->updateCampaignStatistics($campaignId, $result, 1);
            }
            
            Log::info('SMS recipient record created', [
                'recipient_id' => $recipientRecord->id,
                'campaign_id' => $campaignId,
                'status' => $status
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create SMS recipient record', [
                'campaign_id' => $campaignId,
                'recipient' => $recipient,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update campaign statistics based on SMS results.
     *
     * @param int $campaignId
     * @param array $result
     * @param int $totalRecipients
     * @return void
     */
    protected function updateCampaignStatistics(int $campaignId, array $result, int $totalRecipients): void
    {
        try {
            // Get the campaign
            $campaign = \App\Models\SmsCampaign::find($campaignId);
            
            if (!$campaign) {
                Log::error('Campaign not found for statistics update', ['campaign_id' => $campaignId]);
                return;
            }
            
            // Update delivered count based on the result
            $deliveredCount = 0;
            $failedCount = 0;
            
            if ($result['success'] ?? false) {
                $deliveredCount = $totalRecipients;
            } else {
                $failedCount = $totalRecipients;
            }
            
            // Update the campaign statistics
            $campaign->delivered_count += $deliveredCount;
            $campaign->failed_count += $failedCount;
            
            // Update credits used if available
            if (isset($result['credits_used'])) {
                $campaign->credits_used = $result['credits_used'];
            }
            
            // Mark completion time if all messages are processed
            if ($campaign->delivered_count + $campaign->failed_count >= $campaign->recipients_count) {
                $campaign->completed_at = now();
            }
            
            // Save the campaign
            $campaign->save();
            
            Log::info('Campaign statistics updated', [
                'campaign_id' => $campaignId,
                'delivered_count' => $campaign->delivered_count,
                'failed_count' => $campaign->failed_count,
                'credits_used' => $campaign->credits_used
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update campaign statistics', [
                'campaign_id' => $campaignId,
                'error' => $e->getMessage()
            ]);
        }
    }
}