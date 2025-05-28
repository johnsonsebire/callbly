<?php

namespace App\Mail\Meeting;

use App\Models\MeetingBooking;
use App\Models\MeetingNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MeetingConfirmationMail extends Mailable
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
        $isHost = $this->notification->recipient_type === 'host';
        $subject = $isHost 
            ? 'New Meeting Booking: ' . $this->booking->eventType->name
            : 'Meeting Confirmed: ' . $this->booking->eventType->name;

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.meeting.confirmation',
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