<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'has_whatsapp',
        'whatsapp_checked_at',
        'email',
        'alternative_email',
        'website',
        'linkedin_profile',
        'twitter_handle',
        'facebook_profile',
        'instagram_handle',
        'date_of_birth',
        'gender',
        'region',
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
        'preferred_contact_method',
        'lead_status',
        'priority',
        'lead_source',
        'potential_value',
        'last_contact_date',
        'next_follow_up_date',
        'tags',
        'notes',
        'internal_notes',
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
        'whatsapp_checked_at' => 'datetime',
        'has_whatsapp' => 'boolean',
        'annual_revenue' => 'decimal:2',
        'potential_value' => 'decimal:2',
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
        // Remove any non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $value);
        
        // If it starts with a plus sign in the original value, add it back
        if (str_starts_with($value, '+')) {
            $this->attributes['phone_number'] = '+' . $cleaned;
            return;
        }
        
        // If it already starts with a country code (e.g. 233), keep it as is
        if (strlen($cleaned) >= 11 && preg_match('/^(233|234|235|1|44)/', $cleaned)) {
            $this->attributes['phone_number'] = $cleaned;
            return;
        }
        
        // Otherwise assume Ghana and add the country code
        // Remove leading zeros if they exist
        $cleaned = ltrim($cleaned, '0');
        $this->attributes['phone_number'] = '233' . $cleaned;
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