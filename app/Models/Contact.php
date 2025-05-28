<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Contact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'team_id',
        'first_name',
        'last_name',
        'phone_number',
        'alternative_phone',
        'whatsapp_number',
        'email',
        'alternative_email',
        'website',
        'linkedin_profile',
        'twitter_handle',
        'facebook_profile',
        'instagram_handle',
        'date_of_birth',
        'company',
        'job_title',
        'department',
        'industry',
        'annual_revenue',
        'company_size',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'timezone',
        'lead_status',
        'priority',
        'lead_source',
        'potential_value',
        'last_contact_date',
        'next_follow_up_date',
        'preferred_contact_method',
        'tags',
        'notes',
        'internal_notes',
        'has_whatsapp',
        'whatsapp_checked_at',
        'custom_fields',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'custom_fields' => 'array',
        'tags' => 'array',
        'date_of_birth' => 'date',
        'last_contact_date' => 'date',
        'next_follow_up_date' => 'date',
        'annual_revenue' => 'decimal:2',
        'potential_value' => 'decimal:2',
        'has_whatsapp' => 'boolean',
        'whatsapp_checked_at' => 'datetime',
    ];

    /**
     * Get the user that owns the contact.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the team that owns the contact.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the contact groups this contact belongs to.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(ContactGroup::class, 'contact_group_members')
                    ->withTimestamps();
    }

    /**
     * Get the team resources associated with the contact.
     */
    public function teamResources(): HasMany
    {
        return $this->hasMany(TeamResource::class, 'resource_id')
                    ->where('resource_type', 'contact');
    }

    /**
     * Get the teams this contact is shared with.
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_resources')
                    ->where('resource_type', 'contact')
                    ->where('is_shared', true)
                    ->withTimestamps();
    }

    /**
     * Check if the contact is shared with a specific team.
     *
     * @param Team $team
     * @return bool
     */
    public function isSharedWithTeam(Team $team): bool
    {
        return $this->teams->contains($team);
    }

    /**
     * Format the phone number to ensure it's in the correct international format.
     *
     * @param string $value
     * @return void
     */
    public function setPhoneNumberAttribute($value)
    {
        $this->attributes['phone_number'] = $this->formatPhoneNumber($value);
    }

    /**
     * Format the alternative phone number.
     */
    public function setAlternativePhoneAttribute($value)
    {
        $this->attributes['alternative_phone'] = $value ? $this->formatPhoneNumber($value) : null;
    }

    /**
     * Format the WhatsApp number.
     */
    public function setWhatsappNumberAttribute($value)
    {
        $this->attributes['whatsapp_number'] = $value ? $this->formatPhoneNumber($value) : null;
    }

    /**
     * Format phone number to international format.
     */
    private function formatPhoneNumber($value)
    {
        if (!$value) return null;
        
        // Remove any non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $value);
        
        // If it starts with a plus sign in the original value, add it back
        if (str_starts_with($value, '+')) {
            return '+' . $cleaned;
        }
        
        // If it already starts with a country code (e.g. 233), keep it as is
        if (strlen($cleaned) >= 11 && preg_match('/^(233|234|235|1|44)/', $cleaned)) {
            return $cleaned;
        }
        
        // Otherwise assume Ghana and add the country code
        // Remove leading zeros if they exist
        $cleaned = ltrim($cleaned, '0');
        return '233' . $cleaned;
    }

    /**
     * Get the full name of the contact.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Get the full address as a string.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ]);
        
        return implode(', ', $parts);
    }

    /**
     * Get the primary contact method based on preference.
     */
    public function getPrimaryContactAttribute(): string
    {
        switch ($this->preferred_contact_method) {
            case 'whatsapp':
                return $this->whatsapp_number ?: $this->phone_number;
            case 'email':
                return $this->email;
            case 'sms':
            case 'phone':
            default:
                return $this->phone_number;
        }
    }

    /**
     * Check if WhatsApp check is stale (older than 7 days).
     */
    public function isWhatsappCheckStale(): bool
    {
        if (!$this->whatsapp_checked_at) {
            return true;
        }
        
        return $this->whatsapp_checked_at->lt(now()->subDays(7));
    }

    /**
     * Get the lead status badge color.
     */
    public function getLeadStatusColorAttribute(): string
    {
        return match($this->lead_status) {
            'new' => 'primary',
            'contacted' => 'info',
            'qualified' => 'warning',
            'proposal' => 'secondary',
            'negotiation' => 'dark',
            'closed_won' => 'success',
            'closed_lost' => 'danger',
            default => 'primary'
        };
    }

    /**
     * Get the priority badge color.
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'secondary',
            'medium' => 'primary',
            'high' => 'warning',
            'urgent' => 'danger',
            default => 'primary'
        };
    }

    /**
     * Check if contact needs follow-up.
     */
    public function needsFollowUp(): bool
    {
        return $this->next_follow_up_date && $this->next_follow_up_date->lte(today());
    }

    /**
     * Check if contact is overdue for follow-up.
     */
    public function isOverdueForFollowUp(): bool
    {
        return $this->next_follow_up_date && $this->next_follow_up_date->lt(today());
    }

    /**
     * Get social media links.
     */
    public function getSocialLinksAttribute(): array
    {
        return array_filter([
            'linkedin' => $this->linkedin_profile,
            'twitter' => $this->twitter_handle ? "https://twitter.com/{$this->twitter_handle}" : null,
            'facebook' => $this->facebook_profile,
            'instagram' => $this->instagram_handle ? "https://instagram.com/{$this->instagram_handle}" : null,
        ]);
    }

    /**
     * Scope for contacts needing follow-up.
     */
    public function scopeNeedsFollowUp($query)
    {
        return $query->whereNotNull('next_follow_up_date')
                    ->whereDate('next_follow_up_date', '<=', today());
    }

    /**
     * Scope for high priority contacts.
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }

    /**
     * Scope by lead status.
     */
    public function scopeByLeadStatus($query, $status)
    {
        return $query->where('lead_status', $status);
    }

    /**
     * Scope for active leads (not closed).
     */
    public function scopeActiveLeads($query)
    {
        return $query->whereNotIn('lead_status', ['closed_won', 'closed_lost']);
    }

    /**
     * Scope a query to only include contacts available for a specific team.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Team $team
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailableForTeam($query, Team $team)
    {
        return $query->where(function($q) use ($team) {
            $q->where('user_id', $team->owner_id)
              ->orWhere('team_id', $team->id)
              ->orWhereHas('teams', function($q) use ($team) {
                  $q->where('teams.id', $team->id);
              });
        });
    }
}