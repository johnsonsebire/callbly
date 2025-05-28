<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'brand_name',
        'company_name',
        'description',
        'logo_path',
        'website',
        'phone',
        'email',
        'address',
        'timezone',
        'business_hours',
        'is_active',
    ];

    protected $casts = [
        'business_hours' => 'json',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the company profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the scheduling pages for this company profile.
     */
    public function schedulingPages(): HasMany
    {
        return $this->hasMany(SchedulingPage::class);
    }

    /**
     * Get the full URL for the company profile.
     */
    public function getProfileUrlAttribute(): string
    {
        return url('/' . $this->brand_name);
    }

    /**
     * Get the logo URL.
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? asset('storage/' . $this->logo_path) : null;
    }

    /**
     * Scope for active profiles.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get default business hours.
     */
    public static function getDefaultBusinessHours(): array
    {
        return [
            'monday' => ['enabled' => true, 'start' => '09:00', 'end' => '17:00'],
            'tuesday' => ['enabled' => true, 'start' => '09:00', 'end' => '17:00'],
            'wednesday' => ['enabled' => true, 'start' => '09:00', 'end' => '17:00'],
            'thursday' => ['enabled' => true, 'start' => '09:00', 'end' => '17:00'],
            'friday' => ['enabled' => true, 'start' => '09:00', 'end' => '17:00'],
            'saturday' => ['enabled' => false, 'start' => '09:00', 'end' => '17:00'],
            'sunday' => ['enabled' => false, 'start' => '09:00', 'end' => '17:00'],
        ];
    }
}