<?php

namespace App\Services\Contact;

use App\Models\Contact;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class WhatsAppDetectionService
{
    /**
     * Check if a phone number is available on WhatsApp.
     * 
     * This uses a mock implementation. In production, you would integrate
     * with a WhatsApp Business API or a third-party service.
     */
    public function checkWhatsAppAvailability(string $phoneNumber): bool
    {
        try {
            // Format the phone number
            $formattedNumber = $this->formatPhoneNumber($phoneNumber);
            
            // Mock implementation - in production, integrate with WhatsApp API
            // For now, we'll simulate based on Ghana mobile prefixes
            $isLikelyWhatsApp = $this->isLikelyWhatsAppNumber($formattedNumber);
            
            // Log the check for debugging
            Log::info('WhatsApp availability check', [
                'phone' => $formattedNumber,
                'result' => $isLikelyWhatsApp
            ]);
            
            return $isLikelyWhatsApp;
            
        } catch (Exception $e) {
            Log::error('WhatsApp check failed', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Check WhatsApp availability for a contact and update the record.
     */
    public function checkAndUpdateContact(Contact $contact): bool
    {
        $phoneToCheck = $contact->whatsapp_number ?: $contact->phone_number;
        
        if (!$phoneToCheck) {
            return false;
        }
        
        $hasWhatsApp = $this->checkWhatsAppAvailability($phoneToCheck);
        
        $contact->update([
            'has_whatsapp' => $hasWhatsApp,
            'whatsapp_checked_at' => now(),
            'whatsapp_number' => $hasWhatsApp ? $phoneToCheck : $contact->whatsapp_number
        ]);
        
        return $hasWhatsApp;
    }

    /**
     * Bulk check WhatsApp availability for multiple contacts.
     */
    public function bulkCheckContacts($contacts): array
    {
        $results = [];
        
        foreach ($contacts as $contact) {
            $results[$contact->id] = $this->checkAndUpdateContact($contact);
            
            // Add a small delay to avoid rate limiting
            usleep(500000); // 0.5 seconds
        }
        
        return $results;
    }

    /**
     * Check contacts that need WhatsApp verification (stale or never checked).
     */
    public function checkStaleContacts(): int
    {
        $staleContacts = Contact::where(function($query) {
            $query->whereNull('whatsapp_checked_at')
                  ->orWhere('whatsapp_checked_at', '<', now()->subDays(7));
        })
        ->whereNotNull('phone_number')
        ->limit(100) // Process in batches
        ->get();
        
        $this->bulkCheckContacts($staleContacts);
        
        return $staleContacts->count();
    }

    /**
     * Format phone number for WhatsApp checking.
     */
    private function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove any non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Add country code if missing (assuming Ghana)
        if (!str_starts_with($cleaned, '233') && strlen($cleaned) <= 10) {
            $cleaned = '233' . ltrim($cleaned, '0');
        }
        
        return $cleaned;
    }

    /**
     * Simple heuristic to determine if a number is likely to have WhatsApp.
     * This is a mock implementation - replace with actual API integration.
     */
    private function isLikelyWhatsAppNumber(string $phoneNumber): bool
    {
        // Ghana mobile prefixes that commonly use WhatsApp
        $whatsappPrefixes = [
            '23320', '23324', '23325', '23326', '23327', '23328', // MTN
            '23323', '23354', '23355', '23359',                   // AirtelTigo
            '23350', '23356', '23357'                             // Telecel
        ];
        
        foreach ($whatsappPrefixes as $prefix) {
            if (str_starts_with($phoneNumber, $prefix)) {
                // Simulate 80% chance of having WhatsApp for mobile numbers
                return rand(1, 100) <= 80;
            }
        }
        
        // Lower chance for other numbers
        return rand(1, 100) <= 30;
    }

    /**
     * Generate WhatsApp URL for sending messages.
     */
    public function generateWhatsAppUrl(string $phoneNumber, string $message = ''): string
    {
        $formattedNumber = $this->formatPhoneNumber($phoneNumber);
        $encodedMessage = urlencode($message);
        
        return "https://wa.me/{$formattedNumber}?text={$encodedMessage}";
    }

    /**
     * Generate WhatsApp Web URL.
     */
    public function generateWhatsAppWebUrl(string $phoneNumber, string $message = ''): string
    {
        $formattedNumber = $this->formatPhoneNumber($phoneNumber);
        $encodedMessage = urlencode($message);
        
        return "https://web.whatsapp.com/send?phone={$formattedNumber}&text={$encodedMessage}";
    }
}