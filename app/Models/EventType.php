<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventType extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'duration_minutes',
        'buffer_before_minutes',
        'buffer_after_minutes',
        'color',
        'price',
        'max_bookings_per_day',
        'max_bookings_per_week',
        'custom_questions',
        'availability_schedule',
        'requires_confirmation',
        'is_active',
        'meeting_location',
        'location_details',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'custom_questions' => 'json',
        'availability_schedule' => 'json',
        'requires_confirmation' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the event type.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the bookings for this event type.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(MeetingBooking::class);
    }

    /**
     * Get the total duration including buffers.
     */
    public function getTotalDurationAttribute(): int
    {
        return $this->duration_minutes + $this->buffer_before_minutes + $this->buffer_after_minutes;
    }

    /**
     * Get formatted duration for display.
     */
    public function getFormattedDurationAttribute(): string
    {
        $hours = intval($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;
        
        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$minutes}m";
        }
    }

    /**
     * Scope for active event types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get default custom questions structure.
     */
    public static function getDefaultCustomQuestions(): array
    {
        return [
            [
                'id' => 'meeting_purpose',
                'type' => 'textarea',
                'label' => 'What would you like to discuss in this meeting?',
                'required' => false,
                'placeholder' => 'Please provide a brief description of the meeting purpose...'
            ]
        ];
    }

    /**
     * Check if this event type has bookings today.
     */
    public function getTodayBookingsCount(): int
    {
        return $this->bookings()
            ->whereDate('scheduled_at', today())
            ->where('status', '!=', 'cancelled')
            ->count();
    }

    /**
     * Check if this event type has reached daily booking limit.
     */
    public function hasReachedDailyLimit(): bool
    {
        if (!$this->max_bookings_per_day) {
            return false;
        }
        
        return $this->getTodayBookingsCount() >= $this->max_bookings_per_day;
    }
}