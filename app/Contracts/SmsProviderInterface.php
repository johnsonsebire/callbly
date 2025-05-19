<?php

namespace App\Contracts;

interface SmsProviderInterface
{
    /**
     * Send a single SMS message
     *
     * @param string|array $recipients The recipient(s) phone number(s)
     * @param string $message The message to send
     * @param string|null $senderId Optional sender ID
     * @return array Response from the provider
     */
    public function sendSms($recipients, string $message, ?string $senderId = null): array;

    /**
     * Send a bulk SMS message to multiple recipients
     *
     * @param array $recipients Array of recipient phone numbers
     * @param string $message The message to send
     * @param string|null $senderId Optional sender ID
     * @return array Response from the provider
     */
    public function sendBulkSms(array $recipients, string $message, ?string $senderId = null): array;

    /**
     * Get the balance/credits available for sending SMS
     *
     * @return array Balance information
     */
    public function getBalance(): array;

    /**
     * Get the status of a sent message
     *
     * @param string $messageId The ID of the message
     * @return array Status information
     */
    public function getMessageStatus(string $messageId): array;
    
    /**
     * Calculate the number of credits needed for a message
     *
     * @param string $message The message text
     * @param int $recipientCount Number of recipients
     * @return int Number of credits required
     */
    public function calculateCreditsNeeded(string $message, int $recipientCount = 1): int;
}