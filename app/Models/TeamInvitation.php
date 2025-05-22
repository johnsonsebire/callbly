<?php

namespace App\Models;

use App\Notifications\TeamInvitationNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class TeamInvitation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'team_id',
        'email',
        'role',
        'token',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Generate a unique token for the invitation when creating.
     */
    protected static function booted(): void
    {
        static::creating(function ($invitation) {
            $invitation->token = $invitation->token ?? Str::random(64);
            $invitation->expires_at = $invitation->expires_at ?? now()->addDays(7);
        });
    }

    /**
     * Get the team that the invitation belongs to.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Determine if the invitation has expired.
     */
    public function hasExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Send the invitation email.
     */
    public function sendInvitation(): void
    {
        $existingUser = User::where('email', $this->email)->first();
        
        if ($existingUser) {
            // If user exists, send notification to them directly
            $existingUser->notify(new TeamInvitationNotification($this));
        } else {
            // If user doesn't exist, send notification to the email
            Notification::route('mail', $this->email)
                ->notify(new TeamInvitationNotification($this));
        }
    }
}
