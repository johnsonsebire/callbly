<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MeetingBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_type_id',
        'scheduling_page_id',
        'booker_name',
        'booker_email',
        'booker_phone',
        'custom_responses',
        'scheduled_at',
        'scheduled_end_at',
        'timezone',
        'status',
        'meeting_notes',
        'google_meet_link',
        'google_event_id',
        'google_calendar_data',
        'cancellation_reason',
        'cancelled_at',
        'reschedule_history',
        'booking_reference',
    ];

    protected $casts = [
        'custom_responses' => 'json',
        'scheduled_at' => 'datetime',
        'scheduled_end_at' => 'datetime',
        'google_calendar_data' => 'json',
        'cancelled_at' => 'datetime',
        'reschedule_history' => 'json',
    ];

    /**
     * Get the user (host) for this booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event type for this booking.
     */
    public function eventType(): BelongsTo
    {
        return $this->belongsTo(EventType::class);
    }

    /**
     * Get the scheduling page used for this booking.
     */
    public function schedulingPage(): BelongsTo
    {
        return $this->belongsTo(SchedulingPage::class);
    }

    /**
     * Get the notifications for this booking.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(MeetingNotification::class);
    }

    /**
     * Generate a unique booking reference.
     */
    public static function generateBookingReference(): string
    {
        do {
            $reference = 'MTG' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        } while (self::where('booking_reference', $reference)->exists());
        
        return $reference;
    }

    /**
     * Get the public URL for this booking (for cancellation/rescheduling).
     */
    public function getPublicUrlAttribute(): string
    {
        return url('/booking/' . $this->booking_reference);
    }

    /**
     * Check if the booking can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['confirmed']) && 
               $this->scheduled_at->isFuture();
    }

    /**
     * Check if the booking can be rescheduled.
     */
    public function canBeRescheduled(): bool
    {
        return in_array($this->status, ['confirmed']) && 
               $this->scheduled_at->isFuture();
    }

    /**
     * Get formatted scheduled time in the booker's timezone.
     */
    public function getFormattedScheduledTimeAttribute(): string
    {
        return $this->scheduled_at
                    ->setTimezone($this->timezone)
                    ->format('l, F j, Y \a\t g:i A T');
    }

    /**
     * Mark the booking as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update(['status' => 'completed']);
    }

    /**
     * Mark the booking as no-show.
     */
    public function markAsNoShow(): void
    {
        $this->update(['status' => 'no_show']);
    }

    /**
     * Cancel the booking.
     */
    public function cancel(string $reason = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_at' => now(),
        ]);
    }

    /**
     * Scope for upcoming bookings.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>', now())
                    ->where('status', '!=', 'cancelled');
    }

    /**
     * Scope for today's bookings.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_at', today())
                    ->where('status', '!=', 'cancelled');
    }

    /**
     * Scope for active bookings.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['confirmed', 'completed']);
    }
}