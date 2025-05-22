<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Team extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'owner_id',
        'description',
        'logo_path',
        'personal_team',
        'share_sms_credits',
        'share_contacts',
        'share_sender_names',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'personal_team' => 'boolean',
        'share_sms_credits' => 'boolean',
        'share_contacts' => 'boolean',
        'share_sender_names' => 'boolean',
    ];

    /**
     * Generate a slug for the team name when creating or updating.
     */
    protected static function booted(): void
    {
        static::creating(function ($team) {
            $team->slug = $team->slug ?? Str::slug($team->name);
        });

        static::updating(function ($team) {
            if ($team->isDirty('name') && !$team->isDirty('slug')) {
                $team->slug = Str::slug($team->name);
            }
        });
    }

    /**
     * Get the owner of the team.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the users that belong to the team.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(TeamUser::class)
            ->withPivot('role', 'permissions')
            ->withTimestamps();
    }

    /**
     * Get all of the team's invitations.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(TeamInvitation::class);
    }

    /**
     * Get all of the sender names for the team.
     */
    public function senderNames(): HasMany
    {
        return $this->hasMany(SenderName::class);
    }

    /**
     * Determine if the given user belongs to the team.
     */
    public function hasUser(User $user): bool
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine if the given user is the owner of the team.
     */
    public function isOwner(User $user): bool
    {
        return $this->owner_id === $user->id;
    }

    /**
     * Get the user's role in the team.
     */
    public function getUserRole(User $user): ?string
    {
        if ($this->isOwner($user)) {
            return 'owner';
        }

        $teamUser = $this->users()->where('user_id', $user->id)->first();
        
        return $teamUser ? $teamUser->pivot->role : null;
    }
}
