<?php

namespace App\Services\Sms;

use App\Models\User;
use App\Services\Currency\CurrencyService;
use App\Services\SmsService;
use Illuminate\Support\Facades\Log;

class SmsWithCurrencyService
{
    protected SmsService $smsService;
    protected CurrencyService $currencyService;

    public function __construct(SmsService $smsService, CurrencyService $currencyService)
    {
        $this->smsService = $smsService;
        $this->currencyService = $currencyService;
    }

    /**
     * Calculate the cost of sending SMS for a specific user
     * 
     * @param User $user
     * @param int $smsCount Number of messages
     * @param int $recipientCount Number of recipients
     * @return array [
     *     'base_cost' => float (cost in GHS),
     *     'user_cost' => float (cost in user's currency),
     *     'formatted_cost' => string (formatted cost with currency symbol),
     *     'sms_rate' => float (price per SMS in user's currency),
     *     'total_credits' => int (total SMS credits needed)
     * ]
     */
    public function calculateSmsCost(User $user, int $smsCount = 1, int $recipientCount = 1): array
    {
        // Get SMS rate in base currency (GHS)
        $baseSmsRate = $user->billingTier->price_per_sms;
        
        // Calculate total credits needed
        $totalCredits = $smsCount * $recipientCount;
        
        // Calculate cost in base currency
        $baseCost = $baseSmsRate * $totalCredits;
        
        // Convert to user's currency
        $userCurrency = $user->currency;
        $userCost = $baseCost * $userCurrency->exchange_rate;
        
        // Get SMS rate in user's currency
        $userSmsRate = $baseSmsRate * $userCurrency->exchange_rate;
        
        return [
            'base_cost' => $baseCost,
            'user_cost' => $userCost,
            'formatted_cost' => $userCurrency->format($userCost),
            'sms_rate' => $userSmsRate,
            'total_credits' => $totalCredits,
            'billing_tier' => $user->billingTier->name
        ];
    }

    /**
     * Send SMS with cost calculation and billing tier management
     * 
     * @param User $user
     * @param string|array $recipients
     * @param string $message
     * @param string $senderName
     * @param int|null $campaignId
     * @return array Response including original SMS service response and cost details
     */
    public function sendSms(User $user, $recipients, string $message, string $senderName, ?int $campaignId = null): array
    {
        $recipientCount = is_array($recipients) ? count($recipients) : 1;
        $smsCount = $this->calculateSmsPartsCount($message);
        
        // Calculate the cost
        $costDetails = $this->calculateSmsCost($user, $smsCount, $recipientCount);
        $baseCost = $costDetails['base_cost'];
        $totalCreditsNeeded = $costDetails['total_credits'];
        
        // Check if user has sufficient SMS credits instead of checking account_balance
        if (($user->sms_credits ?? 0) < $totalCreditsNeeded) {
            return [
                'success' => false,
                'message' => 'Insufficient SMS credits',
                'cost_details' => $costDetails,
                'credits_needed' => $totalCreditsNeeded,
                'credits_available' => $user->sms_credits ?? 0
            ];
        }

        try {
            // Send the SMS (using either sendSingle or sendBulk based on recipient type)
            if (is_array($recipients) && count($recipients) > 1) {
                $result = $this->smsService->sendBulk($recipients, $message, $senderName, $campaignId ?: 0);
            } else {
                $recipient = is_array($recipients) ? $recipients[0] : $recipients;
                $result = $this->smsService->sendSingle($recipient, $message, $senderName, $campaignId ?: 0);
            }

            // If sending was successful, deduct the SMS credits
            if ($result['success']) {
                // Deduct the SMS credits
                $user->sms_credits = ($user->sms_credits ?? 0) - $totalCreditsNeeded;
                $user->save();
                
                // Check if this purchase qualifies the user for a tier upgrade
                if ($baseCost >= 1500) {
                    $this->currencyService->updateUserBillingTier($user, $baseCost);
                }
                
                return [
                    'success' => true,
                    'message' => 'SMS sent successfully',
                    'result' => $result,
                    'cost_details' => $costDetails,
                    'credits_remaining' => $user->sms_credits
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to send SMS: ' . ($result['message'] ?? 'Unknown error'),
                'result' => $result,
                'cost_details' => $costDetails
            ];
            
        } catch (\Exception $e) {
            Log::error('SMS sending error with currency service: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'recipients' => $recipients,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Error processing SMS: ' . $e->getMessage(),
                'cost_details' => $costDetails
            ];
        }
    }
    
    /**
     * Calculate how many SMS parts would be needed for the message
     *
     * @param string $message
     * @return int
     */
    public function calculateSmsPartsCount(string $message): int
    {
        $length = mb_strlen($message);
        
        // SMS messages have a maximum of 160 characters for single SMS
        $maxSingleSmsLength = 160;
        $maxMultipartSmsLength = 153; // Each part of a multipart SMS can hold 153 chars
        
        // Calculate number of parts needed
        if ($length <= $maxSingleSmsLength) {
            return 1;
        }
        
        return ceil($length / $maxMultipartSmsLength);
    }
    
    /**
     * Calculate the total number of SMS credits needed
     *
     * @param string $message
     * @param int $recipientCount
     * @return int
     */
    public function calculateCreditsNeeded(string $message, int $recipientCount = 1): int
    {
        $smsPartsCount = $this->calculateSmsPartsCount($message);
        return $smsPartsCount * $recipientCount;
    }
    
    /**
     * Send personalized SMS messages with different content to multiple recipients
     * 
     * @param User $user
     * @param array $personalizedMessages Array of [recipient => string, message => string, contact_id => int|null]
     * @param string $senderName
     * @param int|null $campaignId
     * @return array Response including original SMS service response and cost details
     */
    public function sendPersonalizedSms(User $user, array $personalizedMessages, string $senderName, ?int $campaignId = null): array
    {
        $recipientCount = count($personalizedMessages);
        
        if ($recipientCount === 0) {
            return [
                'success' => false,
                'message' => 'No recipients provided'
            ];
        }
        
        // Calculate total SMS parts across all messages
        $totalSmsParts = 0;
        foreach ($personalizedMessages as $message) {
            $totalSmsParts += $this->calculateSmsPartsCount($message['message']);
        }
        
        // Calculate the cost
        $costDetails = $this->calculateSmsCost($user, $totalSmsParts, 1); // Count each message separately
        $baseCost = $costDetails['base_cost'];
        $totalCreditsNeeded = $costDetails['total_credits'];
        
        // Check if user has sufficient SMS credits
        if (($user->sms_credits ?? 0) < $totalCreditsNeeded) {
            return [
                'success' => false,
                'message' => 'Insufficient SMS credits',
                'cost_details' => $costDetails,
                'credits_needed' => $totalCreditsNeeded,
                'credits_available' => $user->sms_credits ?? 0
            ];
        }

        try {
            $successCount = 0;
            $failedCount = 0;
            $results = [];
            
            // Send each personalized message individually
            foreach ($personalizedMessages as $messageData) {
                $recipient = $messageData['recipient'];
                $message = $messageData['message'];
                $contactId = $messageData['contact_id'] ?? null;
                
                $result = $this->smsService->sendSingle($recipient, $message, $senderName, $campaignId ?: 0, $contactId);
                $results[] = $result;
                
                if ($result['success']) {
                    $successCount++;
                } else {
                    $failedCount++;
                }
            }

            // If sending was at least partially successful, deduct the SMS credits
            if ($successCount > 0) {
                // Deduct the SMS credits
                $user->sms_credits = ($user->sms_credits ?? 0) - $totalCreditsNeeded;
                $user->save();
                
                // Check if this purchase qualifies the user for a tier upgrade
                if ($baseCost >= 1500) {
                    $this->currencyService->updateUserBillingTier($user, $baseCost);
                }
                
                return [
                    'success' => true,
                    'message' => "SMS sent successfully to {$successCount} recipients" . ($failedCount > 0 ? ", {$failedCount} failed" : ""),
                    'result' => [
                        'success_count' => $successCount,
                        'failed_count' => $failedCount,
                        'results' => $results
                    ],
                    'cost_details' => $costDetails,
                    'credits_remaining' => $user->sms_credits
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to send all SMS messages',
                'result' => [
                    'success_count' => 0,
                    'failed_count' => $failedCount,
                    'results' => $results
                ],
                'cost_details' => $costDetails
            ];
            
        } catch (\Exception $e) {
            Log::error('Personalized SMS sending error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'recipients_count' => $recipientCount,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Error processing SMS: ' . $e->getMessage(),
                'cost_details' => $costDetails
            ];
        }
    }
    
    /**
     * Delegate other methods to the underlying SMS service
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->smsService, $method], $args);
    }
}