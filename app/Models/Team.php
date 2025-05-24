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
        'share_contact_groups',
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
        'share_contact_groups' => 'boolean',
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

        static::created(function ($team) {
            if ($team->personal_team) {
                $team->users()->attach($team->owner_id, ['role' => 'owner']);
            }
        });

        static::deleting(function ($team) {
            $team->teamResources()->delete();
            $team->invitations()->delete();
            $team->users()->detach();
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
     * Get all of the team's resources.
     */
    public function teamResources(): HasMany
    {
        return $this->hasMany(TeamResource::class);
    }

    /**
     * Get all of the sender names for the team.
     */
    public function senderNames(): BelongsToMany
    {
        return $this->belongsToMany(SenderName::class, 'team_resources')
            ->where('resource_type', 'sender_name')
            ->where('is_shared', true)
            ->withTimestamps();
    }

    /**
     * Get all of the contacts for the team.
     */
    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class, 'team_resources')
            ->where('resource_type', 'contact')
            ->where('is_shared', true)
            ->withTimestamps();
    }

    /**
     * Determine if the given user belongs to the team.
     */
    public function hasUser(User $user): bool
    {
        return $this->users->contains($user) || $this->owner_id === $user->id;
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

    /**
     * Get all shared resources of the team.
     */
    public function sharedResources(string $type = null)
    {
        $query = $this->teamResources()->where('is_shared', true);

        if ($type) {
            $query->where('resource_type', $type);
        }

        return $query;
    }

    /**
     * Share a resource with the team.
     */
    public function shareResource($resource, string $type): void
    {
        $this->teamResources()->updateOrCreate(
            [
                'resource_type' => $type,
                'resource_id' => $resource->id,
            ],
            ['is_shared' => true]
        );
    }

    /**
     * Unshare a resource from the team.
     */
    public function unshareResource($resource, string $type): void
    {
        $this->teamResources()
            ->where('resource_type', $type)
            ->where('resource_id', $resource->id)
            ->delete();
    }

    /**
     * Sync resources of a specific type with the team.
     */
    public function syncResources($resources, string $type): void
    {
        // First remove all resources of this type
        $this->teamResources()
            ->where('resource_type', $type)
            ->delete();

        // Then add the new resources
        $resources->each(fn($resource) => $this->shareResource($resource, $type));
    }
}
