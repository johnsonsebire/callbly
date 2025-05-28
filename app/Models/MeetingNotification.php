<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_booking_id',
        'user_id',
        'type',
        'channel',
        'recipient_type',
        'recipient_email',
        'recipient_phone',
        'minutes_before',
        'custom_message',
        'status',
        'scheduled_at',
        'sent_at',
        'error_message',
        'sms_credits_used',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    /**
     * Get the meeting booking for this notification.
     */
    public function meetingBooking(): BelongsTo
    {
        return $this->belongsTo(MeetingBooking::class);
    }

    /**
     * Get the user associated with this notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark the notification as sent.
     */
    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Mark the notification as failed.
     */
    public function markAsFailed(string $error): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $error,
        ]);
    }

    /**
     * Scope for pending notifications.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for scheduled notifications that are due.
     */
    public function scopeDue($query)
    {
        return $query->where('status', 'pending')
                    ->where('scheduled_at', '<=', now());
    }

    /**
     * Get default notification types and their timings.
     */
    public static function getDefaultReminders(): array
    {
        return [
            ['type' => 'reminder', 'minutes_before' => 1440], // 1 day
            ['type' => 'reminder', 'minutes_before' => 60],   // 1 hour
            ['type' => 'reminder', 'minutes_before' => 15],   // 15 minutes
            ['type' => 'reminder', 'minutes_before' => 5],    // 5 minutes
            ['type' => 'reminder', 'minutes_before' => 0],    // Start time
        ];
    }
}