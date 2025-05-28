<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchedulingPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_profile_id',
        'slug',
        'title',
        'description',
        'event_type_ids',
        'custom_branding',
        'is_active',
        'advance_booking_days',
        'minimum_notice_hours',
    ];

    protected $casts = [
        'event_type_ids' => 'json',
        'custom_branding' => 'json',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the scheduling page.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company profile for this scheduling page.
     */
    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class);
    }

    /**
     * Get the bookings made through this scheduling page.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(MeetingBooking::class);
    }

    /**
     * Get the event types available on this page.
     */
    public function eventTypes()
    {
        return EventType::whereIn('id', $this->event_type_ids ?? [])
                        ->where('is_active', true)
                        ->get();
    }

    /**
     * Get the full URL for the scheduling page.
     */
    public function getSchedulingUrlAttribute(): string
    {
        return url('/' . $this->companyProfile->brand_name . '/' . $this->slug);
    }

    /**
     * Scope for active scheduling pages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get default custom branding.
     */
    public static function getDefaultCustomBranding(): array
    {
        return [
            'primary_color' => '#007bff',
            'secondary_color' => '#6c757d',
            'font_family' => 'system-ui',
            'button_style' => 'rounded',
            'layout' => 'modern'
        ];
    }

    /**
     * Check if booking is allowed based on advance notice.
     */
    public function isBookingAllowed(\DateTime $requestedTime): bool
    {
        $now = new \DateTime();
        $minimumTime = $now->add(new \DateInterval('PT' . $this->minimum_notice_hours . 'H'));
        
        return $requestedTime > $minimumTime;
    }

    /**
     * Get the maximum date for bookings.
     */
    public function getMaxBookingDate(): \DateTime
    {
        $now = new \DateTime();
        return $now->add(new \DateInterval('P' . $this->advance_booking_days . 'D'));
    }
}