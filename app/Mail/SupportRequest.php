<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupportRequest extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The support request data.
     *
     * @var array
     */
    public $supportData;

    /**
     * Create a new message instance.
     */
    public function __construct(array $supportData)
    {
        $this->supportData = $supportData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[' . $this->supportData['priority'] . '] ' . $this->supportData['subject'],
            tags: ['support-request', 'priority-' . strtolower($this->supportData['priority'])],
            metadata: [
                'user_id' => $this->supportData['user_id'],
                'priority' => $this->supportData['priority'],
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.support-request',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}