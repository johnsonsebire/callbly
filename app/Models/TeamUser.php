<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamUser extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role',
        'permissions',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'permissions' => 'json',
    ];

    /**
     * Check if the user has admin role in the team
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user has member role in the team
     */
    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    /**
     * Check if the user is owner of the team
     */
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    /**
     * Get all available team roles
     */
    public static function roles(): array
    {
        return ['owner', 'admin', 'member'];
    }

    /**
     * Get the team that the user belongs to.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the user that belongs to the team.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
