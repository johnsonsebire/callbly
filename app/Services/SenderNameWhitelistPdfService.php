<?php

namespace App\Services;

use App\Models\SenderName;
use App\Models\SystemSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SenderNameWhitelistPdfService
{
    /**
     * Sample messages to use in the whitelist request
     */
    private array $sampleMessages = [
        'Your OTP code is: 123456. Valid for 10 minutes. Do not share this code with anyone.',
        'Dear customer, your account balance is $250.00. Thank you for banking with us.',
        'Reminder: Your appointment is scheduled for tomorrow at 2:00 PM. Reply CONFIRM to confirm.',
        'Welcome to our service! Your registration has been successful. Login with your credentials.',
        'Alert: Suspicious activity detected on your account. Please contact us immediately.',
        'Your order #12345 has been shipped and will arrive within 2-3 business days.',
        'Thank you for your payment of $100.00. Your transaction ID is TXN123456789.',
        'Your subscription expires in 3 days. Renew now to continue enjoying our services.',
    ];

    /**
     * Generate PDF for sender name whitelist request
     */
    public function generateWhitelistRequestPdf(SenderName $senderName): string
    {
        $data = $this->preparePdfData($senderName);
        
        $pdf = Pdf::loadView('pdfs.sender-name-whitelist-request', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'dpi' => 150,
                'defaultFont' => 'Arial',
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        $filename = "sender_name_whitelist_request_{$senderName->sender_name}_" . now()->format('Y_m_d_His') . '.pdf';
        $path = "sender-name-requests/{$filename}";
        
        Storage::disk('local')->put($path, $pdf->output());
        
        return $path;
    }

    /**
     * Download PDF for sender name whitelist request
     */
    public function downloadWhitelistRequestPdf(SenderName $senderName): \Symfony\Component\HttpFoundation\Response
    {
        $data = $this->preparePdfData($senderName);
        
        $pdf = Pdf::loadView('pdfs.sender-name-whitelist-request', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'dpi' => 150,
                'defaultFont' => 'Arial',
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        $filename = "Sender_Name_Whitelist_Request_{$senderName->sender_name}_" . now()->format('Y_m_d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Prepare data for PDF generation
     */
    public function preparePdfData(SenderName $senderName): array
    {
        $user = $senderName->user;
        $randomMessage = $this->sampleMessages[array_rand($this->sampleMessages)];
        
        return [
            'sender_name' => $senderName->sender_name,
            'user_name' => $user->first_name . ' ' . $user->last_name,
            'user_email' => $user->email,
            'company_name' => $user->company_name ?? 'Individual User',
            'phone_number' => $user->phone_number ?? 'Not Provided',
            'sample_message' => $randomMessage,
            'request_date' => now()->format('F j, Y'),
            'request_reference' => 'SNR-' . strtoupper($senderName->sender_name) . '-' . now()->format('Ymd'),
            'created_at' => $senderName->created_at->format('F j, Y'),
        ];
    }

    /**
     * Get random sample message
     */
    public function getRandomSampleMessage(): string
    {
        return $this->sampleMessages[array_rand($this->sampleMessages)];
    }

    /**
     * Send whitelist request email if auto-send is enabled
     */
    public function sendWhitelistRequestIfEnabled(SenderName $senderName): bool
    {
        $autoSendEnabled = SystemSetting::where('key', 'sender_name_auto_send_enabled')
            ->first()?->value === 'true';

        if (!$autoSendEnabled) {
            return false;
        }

        $emailAddresses = SystemSetting::where('key', 'sender_name_notification_emails')
            ->first()?->value;

        if (!$emailAddresses) {
            return false;
        }

        $emails = array_filter(array_map('trim', explode(',', $emailAddresses)));
        
        if (empty($emails)) {
            return false;
        }

        try {
            // Generate PDF
            $pdfPath = $this->generateWhitelistRequestPdf($senderName);
            $pdfContent = Storage::disk('local')->get($pdfPath);
            
            // Send email with PDF attachment
            foreach ($emails as $email) {
                Mail::send('emails.sender-name-whitelist-request', [
                    'sender_name' => $senderName->sender_name,
                    'user_name' => $senderName->user->first_name . ' ' . $senderName->user->last_name,
                    'user_email' => $senderName->user->email,
                    'company_name' => $senderName->user->company_name ?? 'Individual User',
                    'request_date' => now()->format('F j, Y'),
                ], function ($message) use ($email, $senderName, $pdfContent) {
                    $message->to($email)
                        ->subject('Sender Name Whitelist Request - ' . $senderName->sender_name)
                        ->attachData($pdfContent, "Sender_Name_Whitelist_Request_{$senderName->sender_name}.pdf", [
                            'mime' => 'application/pdf',
                        ]);
                });
            }

            // Clean up generated file
            Storage::disk('local')->delete($pdfPath);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send sender name whitelist request email: ' . $e->getMessage());
            return false;
        }
    }
}
