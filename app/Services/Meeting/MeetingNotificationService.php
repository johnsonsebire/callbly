<?php

namespace App\Services\Meeting;

use App\Models\MeetingBooking;
use App\Models\MeetingNotification;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MeetingNotificationService
{
    protected SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Create all notifications for a booking
     */
    public function createBookingNotifications(MeetingBooking $booking): void
    {
        // Create confirmation notifications
        $this->createNotification($booking, 'confirmation', 'both', 'host');
        $this->createNotification($booking, 'confirmation', 'both', 'booker');

        // Create reminder notifications
        $reminders = MeetingNotification::getDefaultReminders();
        foreach ($reminders as $reminder) {
            $scheduledAt = $booking->scheduled_at->subMinutes($reminder['minutes_before']);
            
            // Only create reminders for future times
            if ($scheduledAt->isFuture()) {
                $this->createNotification(
                    $booking, 
                    $reminder['type'], 
                    'both', 
                    'host', 
                    $reminder['minutes_before'],
                    $scheduledAt
                );
                
                $this->createNotification(
                    $booking, 
                    $reminder['type'], 
                    'both', 
                    'booker', 
                    $reminder['minutes_before'],
                    $scheduledAt
                );
            }
        }
    }

    /**
     * Create a single notification record
     */
    protected function createNotification(
        MeetingBooking $booking,
        string $type,
        string $channel,
        string $recipientType,
        ?int $minutesBefore = null,
        ?Carbon $scheduledAt = null
    ): MeetingNotification {
        $recipientEmail = $recipientType === 'host' ? $booking->user->email : $booking->booker_email;
        $recipientPhone = $recipientType === 'host' ? $booking->user->phone : $booking->booker_phone;

        return MeetingNotification::create([
            'meeting_booking_id' => $booking->id,
            'user_id' => $booking->user_id,
            'type' => $type,
            'channel' => $channel,
            'recipient_type' => $recipientType,
            'recipient_email' => $recipientEmail,
            'recipient_phone' => $recipientPhone,
            'minutes_before' => $minutesBefore,
            'scheduled_at' => $scheduledAt ?? now(),
            'status' => $type === 'confirmation' ? 'pending' : 'pending',
        ]);
    }

    /**
     * Send confirmation notifications immediately
     */
    public function sendConfirmationNotifications(MeetingBooking $booking): void
    {
        $confirmations = $booking->notifications()
            ->where('type', 'confirmation')
            ->where('status', 'pending')
            ->get();

        foreach ($confirmations as $notification) {
            $this->sendNotification($notification);
        }
    }

    /**
     * Process due notifications
     */
    public function processDueNotifications(): void
    {
        $dueNotifications = MeetingNotification::due()->get();

        foreach ($dueNotifications as $notification) {
            $this->sendNotification($notification);
        }
    }

    /**
     * Send a single notification
     */
    protected function sendNotification(MeetingNotification $notification): void
    {
        try {
            $booking = $notification->meetingBooking;
            $channels = $notification->channel === 'both' ? ['email', 'sms'] : [$notification->channel];

            foreach ($channels as $channel) {
                if ($channel === 'email') {
                    $this->sendEmailNotification($notification, $booking);
                } elseif ($channel === 'sms' && $notification->recipient_phone) {
                    $this->sendSmsNotification($notification, $booking);
                }
            }

            $notification->markAsSent();

        } catch (\Exception $e) {
            Log::error('Failed to send meeting notification', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage()
            ]);
            $notification->markAsFailed($e->getMessage());
        }
    }

    /**
     * Send email notification
     */
    protected function sendEmailNotification(MeetingNotification $notification, MeetingBooking $booking): void
    {
        $mailClass = $this->getEmailClass($notification->type);
        
        Mail::to($notification->recipient_email)->send(
            new $mailClass($booking, $notification)
        );
    }

    /**
     * Send SMS notification
     */
    protected function sendSmsNotification(MeetingNotification $notification, MeetingBooking $booking): void
    {
        $user = $notification->user;
        $message = $this->buildSmsMessage($notification, $booking);

        // Get user's approved sender name or default to "Callbly"
        $senderName = $user->getAvailableSenderNames()
            ->where('status', 'approved')
            ->first()?->name ?? 'Callbly';

        // Check if user has SMS credits
        $availableCredits = $user->getAvailableSmsCredits();
        if ($availableCredits < 1) {
            throw new \Exception('Insufficient SMS credits for meeting notification');
        }

        // Send SMS
        $result = $this->smsService->sendSingle(
            $notification->recipient_phone,
            $message,
            $senderName,
            $user
        );

        if ($result['success']) {
            // Deduct SMS credits
            $teamResourceService = app(\App\Services\TeamResourceService::class);
            $creditResult = $teamResourceService->deductSharedSmsCredits($user, 1);
            
            if ($creditResult['success']) {
                $notification->update(['sms_credits_used' => 1]);
            }
        } else {
            throw new \Exception('SMS sending failed: ' . ($result['message'] ?? 'Unknown error'));
        }
    }

    /**
     * Build SMS message content
     */
    protected function buildSmsMessage(MeetingNotification $notification, MeetingBooking $booking): string
    {
        $eventType = $booking->eventType;
        $scheduledTime = $booking->scheduled_at->setTimezone($booking->timezone);

        switch ($notification->type) {
            case 'confirmation':
                if ($notification->recipient_type === 'host') {
                    return "New meeting booked! {$eventType->name} with {$booking->booker_name} on {$scheduledTime->format('M j, Y \a\t g:i A T')}. Google Meet: {$booking->google_meet_link}";
                } else {
                    return "Meeting confirmed! {$eventType->name} with {$booking->user->name} on {$scheduledTime->format('M j, Y \a\t g:i A T')}. Google Meet: {$booking->google_meet_link} Ref: {$booking->booking_reference}";
                }

            case 'reminder':
                $timePrefix = $this->getTimePrefix($notification->minutes_before);
                if ($notification->recipient_type === 'host') {
                    return "{$timePrefix} reminder: {$eventType->name} with {$booking->booker_name}. Join: {$booking->google_meet_link}";
                } else {
                    return "{$timePrefix} reminder: {$eventType->name} with {$booking->user->name}. Join: {$booking->google_meet_link}";
                }

            case 'cancellation':
                return "Meeting cancelled: {$eventType->name} scheduled for {$scheduledTime->format('M j, Y \a\t g:i A T')} has been cancelled.";

            case 'reschedule':
                return "Meeting rescheduled: {$eventType->name} has been moved to {$scheduledTime->format('M j, Y \a\t g:i A T')}. Google Meet: {$booking->google_meet_link}";

            default:
                return "Meeting update for {$eventType->name} on {$scheduledTime->format('M j, Y \a\t g:i A T')}.";
        }
    }

    /**
     * Get time prefix for reminders
     */
    protected function getTimePrefix(int $minutesBefore): string
    {
        if ($minutesBefore === 0) {
            return "Starting now";
        } elseif ($minutesBefore < 60) {
            return "{$minutesBefore} minute" . ($minutesBefore > 1 ? 's' : '');
        } elseif ($minutesBefore < 1440) {
            $hours = intval($minutesBefore / 60);
            return "{$hours} hour" . ($hours > 1 ? 's' : '');
        } else {
            $days = intval($minutesBefore / 1440);
            return "{$days} day" . ($days > 1 ? 's' : '');
        }
    }

    /**
     * Get email class for notification type
     */
    protected function getEmailClass(string $type): string
    {
        return match($type) {
            'confirmation' => \App\Mail\Meeting\MeetingConfirmationMail::class,
            'reminder' => \App\Mail\Meeting\MeetingReminderMail::class,
            'cancellation' => \App\Mail\Meeting\MeetingCancellationMail::class,
            'reschedule' => \App\Mail\Meeting\MeetingRescheduleMail::class,
            default => \App\Mail\Meeting\MeetingConfirmationMail::class,
        };
    }

    /**
     * Create cancellation notifications
     */
    public function createCancellationNotifications(MeetingBooking $booking): void
    {
        $this->createNotification($booking, 'cancellation', 'both', 'host');
        $this->createNotification($booking, 'cancellation', 'both', 'booker');

        // Send cancellation notifications immediately
        $cancellations = $booking->notifications()
            ->where('type', 'cancellation')
            ->where('status', 'pending')
            ->get();

        foreach ($cancellations as $notification) {
            $this->sendNotification($notification);
        }
    }

    /**
     * Create reschedule notifications
     */
    public function createRescheduleNotifications(MeetingBooking $booking): void
    {
        $this->createNotification($booking, 'reschedule', 'both', 'host');
        $this->createNotification($booking, 'reschedule', 'both', 'booker');

        // Send reschedule notifications immediately
        $reschedules = $booking->notifications()
            ->where('type', 'reschedule')
            ->where('status', 'pending')
            ->get();

        foreach ($reschedules as $notification) {
            $this->sendNotification($notification);
        }
    }
}