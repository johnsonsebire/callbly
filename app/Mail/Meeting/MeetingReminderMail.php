<?php

namespace App\Mail\Meeting;

use App\Models\MeetingBooking;
use App\Models\MeetingNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MeetingReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public MeetingBooking $booking;
    public MeetingNotification $notification;

    public function __construct(MeetingBooking $booking, MeetingNotification $notification)
    {
        $this->booking = $booking;
        $this->notification = $notification;
    }

    public function envelope(): Envelope
    {
        $timePrefix = $this->getTimePrefix($this->notification->minutes_before);
        $subject = "Reminder: {$this->booking->eventType->name} - {$timePrefix}";

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.meeting.reminder',
            with: [
                'booking' => $this->booking,
                'notification' => $this->notification,
                'isHost' => $this->notification->recipient_type === 'host',
                'companyProfile' => $this->booking->user->companyProfile,
                'eventType' => $this->booking->eventType,
                'timePrefix' => $this->getTimePrefix($this->notification->minutes_before),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }

    private function getTimePrefix(int $minutesBefore): string
    {
        if ($minutesBefore === 0) {
            return "Starting now";
        } elseif ($minutesBefore < 60) {
            return "Starting in {$minutesBefore} minute" . ($minutesBefore > 1 ? 's' : '');
        } elseif ($minutesBefore < 1440) {
            $hours = intval($minutesBefore / 60);
            return "Starting in {$hours} hour" . ($hours > 1 ? 's' : '');
        } else {
            $days = intval($minutesBefore / 1440);
            return "Starting in {$days} day" . ($days > 1 ? 's' : '');
        }
    }
}