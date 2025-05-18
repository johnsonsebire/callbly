<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_name',
        'role',
        'account_balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'account_balance' => 'decimal:2',
        ];
    }

    /**
     * Check if user is admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get the SMS campaigns for the user
     */
    public function smsCampaigns(): HasMany
    {
        return $this->hasMany(SmsCampaign::class);
    }

    /**
     * Get the sender names for the user
     */
    public function senderNames(): HasMany
    {
        return $this->hasMany(SenderName::class);
    }

    /**
     * Get the USSD services for the user
     */
    public function ussdServices(): HasMany
    {
        return $this->hasMany(UssdService::class);
    }

    /**
     * Get the contact center calls for the user
     */
    public function contactCenterCalls(): HasMany
    {
        return $this->hasMany(ContactCenterCall::class);
    }

    /**
     * Get the virtual numbers for the user
     */
    public function virtualNumbers(): HasMany
    {
        return $this->hasMany(VirtualNumber::class);
    }

    /**
     * Get the orders for the user
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the affiliate referrals created by the user
     */
    public function affiliateReferrals(): HasMany
    {
        return $this->hasMany(AffiliateReferral::class, 'referrer_id');
    }
}
