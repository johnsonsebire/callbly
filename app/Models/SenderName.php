<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SenderName extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'status',
        'rejection_reason',
        'approved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user that owns the sender name.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the team resources associated with the sender name.
     */
    public function teamResources(): HasMany
    {
        return $this->hasMany(TeamResource::class, 'resource_id')
                    ->where('resource_type', 'sender_name');
    }

    /**
     * Get the teams associated with the sender name.
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_resources')
                    ->where('resource_type', 'sender_name')
                    ->where('is_shared', true)
                    ->withTimestamps();
    }

    /**
     * Check if the sender name is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved' && $this->approved_at !== null;
    }

    /**
     * Check if the sender name is shared with a specific team.
     */
    public function isSharedWithTeam(Team $team): bool
    {
        return $this->teams->contains($team);
    }

    /**
     * Scope a query to only include approved sender names.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include pending sender names.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include rejected sender names.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope a query to include sender names available for a specific team.
     */
    public function scopeAvailableForTeam($query, Team $team)
    {
        return $query->where(function($q) use ($team) {
            $q->where('user_id', $team->owner_id)
              ->orWhereHas('teams', function($q) use ($team) {
                  $q->where('teams.id', $team->id);
              });
        })->approved();
    }

    /**
     * Get the name attribute.
     */
    public function getNameAttribute($value): string
    {
        // Ensure sender names are not capitalized
        return strtolower($value);
    }

    /**
     * Set the name attribute.
     */
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = strtolower($value);
    }
}
