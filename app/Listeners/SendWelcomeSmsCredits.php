<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Models\SystemSetting;
use App\Models\User;
use App\Notifications\WelcomeSmsCreditsNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeSmsCreditsEmail;

class SendWelcomeSmsCredits
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;
        
        // Prevent duplicate execution - check if user already received welcome credits
        // We check if user already has credits or if they were created more than 5 minutes ago
        if ($user->sms_credits > 0 || $user->created_at->diffInMinutes(now()) > 5) {
            Log::info('Welcome SMS credits already processed or user too old', [
                'user_id' => $user->id,
                'current_credits' => $user->sms_credits,
                'created_minutes_ago' => $user->created_at->diffInMinutes(now())
            ]);
            return;
        }
        
        // Check if free SMS credits for new users is enabled
        $isEnabled = SystemSetting::get('new_user_free_sms_credits_enabled', true);
        
        if (!$isEnabled) {
            Log::info('Free SMS credits for new users is disabled', ['user_id' => $user->id]);
            return;
        }
        
        // Get the amount of free credits to give
        $creditsAmount = SystemSetting::get('new_user_free_sms_credits_amount', 5);
        
        if ($creditsAmount <= 0) {
            Log::info('Free SMS credits amount is 0 or negative', [
                'user_id' => $user->id,
                'credits_amount' => $creditsAmount
            ]);
            return;
        }
        
        // Add SMS credits to the user
        $user->sms_credits = ($user->sms_credits ?? 0) + $creditsAmount;
        $user->save();
        
        Log::info('Free SMS credits added to new user', [
            'user_id' => $user->id,
            'credits_added' => $creditsAmount,
            'total_credits' => $user->sms_credits
        ]);
        
        // Send email notification if enabled
        if (SystemSetting::get('welcome_email_enabled', true)) {
            try {
                Mail::to($user->email)->send(new WelcomeSmsCreditsEmail($user, $creditsAmount));
                Log::info('Welcome SMS credits email sent', ['user_id' => $user->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send welcome SMS credits email', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            Log::info('Welcome email is disabled - skipping email notification', ['user_id' => $user->id]);
        }
        
        // Send SMS notification (if they have phone number and approved sender name)
        try {
            $this->sendWelcomeSms($user, $creditsAmount);
        } catch (\Exception $e) {
            Log::error('Failed to send welcome SMS', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Send welcome SMS to the user
     */
    protected function sendWelcomeSms(User $user, int $creditsAmount): void
    {
        if (empty($user->phone)) {
            Log::info('User has no phone number for welcome SMS', ['user_id' => $user->id]);
            return;
        }
        
        // Get system sender name from settings
        $systemSenderName = SystemSetting::get('system_sender_name', 'callbly');
        
        $message = "Welcome to Callbly! You've received {$creditsAmount} free SMS credits to get started. Start sending SMS campaigns now!";
        
        // Use the SMS service to send the welcome message
        $smsService = app(\App\Services\SmsService::class);
        
        try {
            $result = $smsService->sendSingle(
                $user->phone,
                $message,
                $systemSenderName,
                0 // No campaign ID for system messages
            );
            
            if ($result['success']) {
                Log::info('Welcome SMS sent successfully', [
                    'user_id' => $user->id,
                    'phone' => $user->phone
                ]);
            } else {
                Log::warning('Welcome SMS failed to send', [
                    'user_id' => $user->id,
                    'phone' => $user->phone,
                    'error' => $result['message'] ?? 'Unknown error'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception sending welcome SMS', [
                'user_id' => $user->id,
                'phone' => $user->phone,
                'error' => $e->getMessage()
            ]);
        }
    }
}
