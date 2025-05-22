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
     * @param int|null $contactId
     * @return array
     */
    public function sendSingle(string $recipient, string $message, string $senderName, int $campaignId, ?int $contactId = null): array
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
                'contact_id' => $contactId,
                'provider_response' => $result
            ]);
            
            // Create recipient record if campaign ID is provided
            if ($campaignId > 0) {
                $this->createSmsRecipient($campaignId, $formattedRecipient, $result, $contactId);
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
                'contact_id' => $contactId,
                'error' => $e->getMessage(),
            ]);
            
            // Record failed recipient if campaign ID is provided
            if ($campaignId > 0) {
                $this->createSmsRecipient($campaignId, $recipient, [
                    'success' => false,
                    'error' => $e->getMessage(),
                    'status' => 'failed'
                ], $contactId);
            }
            
            return [
                'success' => false,
                'message' => 'Failed to send SMS: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send bulk SMS messages with template variable replacement.
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
            $campaign = \App\Models\SmsCampaign::find($campaignId);
            $user = $campaign->user;
            
            // Track success metrics
            $totalSent = 0;
            $totalFailed = 0;
            
            // Get contacts associated with these phone numbers for personalization
            $phoneNumbers = array_map([$this, 'formatPhoneNumber'], $recipients);
            $contacts = $user->contacts()->whereIn('phone_number', $phoneNumbers)->get()->keyBy('phone_number');
            
            // Log personalization info
            Log::info('SMS personalization data', [
                'campaign_id' => $campaignId,
                'has_contacts_for_personalization' => $contacts->count() > 0,
                'contacts_found' => $contacts->count(),
                'recipients_total' => count($recipients)
            ]);
            
            // Send individually for personalization if needed
            foreach ($recipients as $recipient) {
                try {
                    $formattedNumber = $this->formatPhoneNumber($recipient);
                    $contact = $contacts->get($formattedNumber);
                    $personalizedMessage = $message;
                    
                    // Personalize message if contact exists
                    if ($contact) {
                        $personalizedMessage = $this->replaceTemplateVariables($message, $contact);
                    }
                    
                    // Send individual message
                    $result = $this->provider->sendSms($formattedNumber, $personalizedMessage, $senderName);
                    
                    // Create recipient record
                    $this->createSmsRecipient(
                        $campaignId, 
                        $formattedNumber, 
                        $result, 
                        $contact->id ?? null
                    );
                    
                    if ($result['success'] ?? false) {
                        $totalSent++;
                    } else {
                        $totalFailed++;
                    }
                    
                } catch (\Exception $e) {
                    // Log individual sending error but continue with others
                    Log::error('Error sending to individual recipient', [
                        'recipient' => $recipient,
                        'campaign_id' => $campaignId,
                        'error' => $e->getMessage()
                    ]);
                    
                    // Record failed recipient
                    $this->createSmsRecipient($campaignId, $recipient, [
                        'success' => false,
                        'error' => $e->getMessage(),
                        'status' => 'failed'
                    ]);
                    
                    $totalFailed++;
                }
            }
            
            // Log campaign results
            Log::info('Bulk SMS campaign completed', [
                'campaign_id' => $campaignId,
                'recipients_count' => count($recipients),
                'sent_count' => $totalSent,
                'failed_count' => $totalFailed,
                'sender' => $senderName,
                'reference' => $reference
            ]);
            
            // Update campaign statistics
            $this->updateCampaignStatistics($campaignId, [
                'success' => $totalSent > 0,
                'total_sent' => $totalSent,
                'total_failed' => $totalFailed
            ], count($recipients));
            
            return [
                'success' => $totalSent > 0,
                'reference' => $reference,
                'batch_id' => $reference,
                'completed' => true,
                'total' => count($recipients),
                'delivered_count' => $totalSent,
                'failed_count' => $totalFailed,
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
     * Replace template variables in a message with contact data.
     *
     * @param string $message The message template with variables
     * @param \App\Models\Contact $contact The contact with data to use for replacement
     * @return string The message with variables replaced
     */
    protected function replaceTemplateVariables(string $message, $contact): string
    {
        if (!$contact) {
            return $message;
        }
        
        $variables = [
            'name' => $contact->full_name ?? ($contact->first_name . ' ' . $contact->last_name),
            'first_name' => $contact->first_name ?? '',
            'last_name' => $contact->last_name ?? '',
            'dob' => $contact->date_of_birth ? date('d/m/Y', strtotime($contact->date_of_birth)) : '',
            'email' => $contact->email ?? '',
            'phone' => $contact->phone_number ?? '',
            'company' => $contact->company ?? '',
        ];
        
        Log::info('Replacing template variables for contact', [
            'contact_id' => $contact->id,
            'variables_available' => array_keys($variables)
        ]);
        
        // Replace variables in the format {variable_name}
        return preg_replace_callback(
            '/\{([a-z_]+)\}/i',
            function ($matches) use ($variables) {
                $key = strtolower($matches[1]);
                return $variables[$key] ?? $matches[0]; // Return original if variable not found
            },
            $message
        );
    }

    /**
     * Create a record for an SMS recipient.
     *
     * @param int $campaignId
     * @param string $recipient
     * @param array $result
     * @param int|null $contactId
     * @return void
     */
    protected function createSmsRecipient(int $campaignId, string $recipient, array $result, ?int $contactId = null): void
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
                'contact_id' => $contactId,
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
            
            // Calculate the actual metrics from the recipients
            $metrics = $campaign->recipients()
                ->selectRaw('
                    COUNT(*) as total_count,
                    SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as delivered_count,
                    SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed_count,
                    SUM(CASE WHEN status = "sent" THEN 1 ELSE 0 END) as sent_count,
                    SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_count
                ')
                ->first();
            
            // Update the campaign with the actual counts from the database
            $campaign->recipients_count = $metrics->total_count ?? $campaign->recipients_count;
            $campaign->delivered_count = $metrics->delivered_count ?? 0;
            $campaign->failed_count = $metrics->failed_count ?? 0;
            
            // Update credits used if available
            if (isset($result['credits_used'])) {
                $campaign->credits_used = $result['credits_used'];
            } else {
                // Calculate credits used based on message parts and recipient count
                $messageLength = mb_strlen($campaign->message);
                $hasUnicode = preg_match('/[\x{0080}-\x{FFFF}]/u', $campaign->message);
                
                if ($hasUnicode) {
                    $parts = $messageLength <= 70 ? 1 : ceil(($messageLength - 70) / 67) + 1;
                } else {
                    $parts = $messageLength <= 160 ? 1 : ceil(($messageLength - 160) / 153) + 1;
                }
                
                $campaign->credits_used = $parts * $campaign->recipients_count;
            }
            
            // Mark completion time if all messages are processed
            if (($metrics->delivered_count + $metrics->failed_count) >= $campaign->recipients_count) {
                $campaign->completed_at = now();
                $campaign->status = 'completed';
            }
            
            // Save the campaign
            $campaign->save();
            
            Log::info('Campaign statistics updated', [
                'campaign_id' => $campaignId,
                'total_recipients' => $campaign->recipients_count,
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