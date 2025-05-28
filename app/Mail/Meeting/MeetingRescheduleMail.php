<?php

namespace App\Mail\Meeting;

use App\Models\MeetingBooking;
use App\Models\MeetingNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MeetingRescheduleMail extends Mailable
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
        return new Envelope(
            subject: 'Meeting Rescheduled: ' . $this->booking->eventType->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.meeting.reschedule',
            with: [
                'booking' => $this->booking,
                'notification' => $this->notification,
                'isHost' => $this->notification->recipient_type === 'host',
                'companyProfile' => $this->booking->user->companyProfile,
                'eventType' => $this->booking->eventType,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}