<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\CustomResetPasswordNotification;
use App\Services\TeamResourceService;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'company_name',
        'role',
        'account_balance',
        'call_credits',
        'sms_credits',
        'ussd_credits',
        'currency_id',
        'billing_tier_id',
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
            'call_credits' => 'integer',
            'sms_credits' => 'integer',
            'ussd_credits' => 'integer',
        ];
    }

    /**
     * Check if user is admin (staff or super admin)
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        $this->setTeamContextIfNeeded();
        return $this->hasAnyRole(['staff', 'super admin']);
    }

    /**
     * Check if user is super admin
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        $this->setTeamContextIfNeeded();
        return $this->hasRole('super admin');
    }

    /**
     * Check if user is staff
     *
     * @return bool
     */
    public function isStaff(): bool
    {
        $this->setTeamContextIfNeeded();
        return $this->hasRole('staff');
    }

    /**
     * Check if user is customer
     *
     * @return bool
     */
    public function isCustomer(): bool
    {
        $this->setTeamContextIfNeeded();
        return $this->hasRole('customer');
    }

    /**
     * Set team context for permission checks if user has a current team
     */
    private function setTeamContextIfNeeded(): void
    {
        if ($this->current_team_id && !app(\Spatie\Permission\PermissionRegistrar::class)->getPermissionsTeamId()) {
            app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($this->current_team_id);
        }
    }

    /**
     * Get the SMS campaigns for the user
     */
    public function smsCampaigns(): HasMany
    {
        return $this->hasMany(SmsCampaign::class);
    }

    /**
     * Get the SMS templates for the user.
     */
    public function smsTemplates()
    {
        return $this->hasMany(SmsTemplate::class);
    }

    /**
     * Get the sender names for the user
     */
    public function senderNames(): HasMany
    {
        return $this->hasMany(SenderName::class);
    }
    
    /**
     * Get all available sender names for the user (including shared from teams)
     */
    public function getAvailableSenderNames()
    {
        return app(TeamResourceService::class)->getAvailableSenderNames($this);
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

    /**
     * Get the currency associated with the user
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class)->withDefault(function ($currency) {
            $defaultCurrency = Currency::getDefaultCurrency();
            return $defaultCurrency ?: new Currency([
                'name' => 'Ghana Cedi',
                'code' => 'GHS',
                'symbol' => '₵',
                'is_default' => true,
            ]);
        });
    }

    /**
     * Get the billing tier associated with the user
     */
    public function billingTier(): BelongsTo
    {
        return $this->belongsTo(BillingTier::class)->withDefault(function ($billingTier) {
            $defaultTier = BillingTier::getDefaultTier();
            return $defaultTier ?: new BillingTier([
                'name' => 'Basic',
                'price_per_sms' => 0.035,
                'is_default' => true,
            ]);
        });
    }
    
    /**
     * Format an amount in the user's currency
     * 
     * @param float $amount Amount in the base currency (GHS)
     * @param bool $includeSymbol Whether to include the currency symbol
     * @return string
     */
    public function formatAmount(float $amount, bool $includeSymbol = true): string
    {
        $userCurrency = $this->currency;
        // Convert amount to user's currency
        $convertedAmount = $amount * $userCurrency->exchange_rate;
        return $userCurrency->format($convertedAmount, $includeSymbol);
    }
    
    /**
     * Convert an amount from the user's currency to base currency (GHS)
     * 
     * @param float $amount Amount in user's currency
     * @return float Amount in base currency (GHS)
     */
    public function convertToBaseCurrency(float $amount): float
    {
        $userCurrency = $this->currency;
        if ($userCurrency->is_default || $userCurrency->code === 'GHS') {
            return $amount;
        }
        
        return $amount / $userCurrency->exchange_rate;
    }
    
    /**
     * Get the SMS rate for this user based on their billing tier
     * 
     * @return float Price per SMS in user's currency
     */
    public function getSmsRate(): float
    {
        $basePricePerSms = $this->billingTier->price_per_sms;
        $userCurrency = $this->currency;
        
        // Convert base price to user's currency
        return $basePricePerSms * $userCurrency->exchange_rate;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    /**
     * Get the contacts for the user
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }
    
    /**
     * Get all available contacts for the user (including shared from teams)
     */
    public function getAvailableContacts()
    {
        return app(TeamResourceService::class)->getAvailableContacts($this);
    }

    /**
     * Get the contact groups for the user
     */
    public function contactGroups(): HasMany
    {
        return $this->hasMany(ContactGroup::class);
    }

    /**
     * Get all available contact groups for the user (including shared from teams)
     */
    public function getAvailableContactGroups()
    {
        return app(TeamResourceService::class)->getAvailableContactGroups($this);
    }

    /**
     * Get the custom fields for the user
     */
    public function customFields(): HasMany
    {
        return $this->hasMany(CustomField::class);
    }

    /**
     * Get the wallet transactions for the user
     */
    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Get SMS credit purchase transactions for the user
     */
    public function creditPurchases(): HasMany
    {
        return $this->walletTransactions()
            ->where(function($query) {
                $query->where(function($q) {
                    $q->where('type', 'debit')
                      ->where('description', 'like', '%SMS credits%');
                })->orWhere(function($q) {
                    $q->where('metadata->product_type', 'sms_credits')
                      ->where('status', 'completed');
                });
            })
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the wallet for the user
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class)->withDefault(function ($wallet, $user) {
            // Get default currency if user doesn't have one
            $currencyId = $user->currency_id;
            if (!$currencyId) {
                $defaultCurrency = Currency::getDefaultCurrency();
                $currencyId = $defaultCurrency->id;
            }
            
            // Create a default wallet if none exists
            return Wallet::create([
                'user_id' => $user->id,
                'currency_id' => $currencyId,
                'balance' => 0.00,
            ]);
        });
    }

    /**
     * Get all of the teams that the user owns.
     */
    public function ownedTeams(): HasMany
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    /**
     * Get all of the teams that the user belongs to.
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)
            ->using(TeamUser::class)
            ->withPivot('role', 'permissions')
            ->withTimestamps();
    }
    
    /**
     * Get all of the teams where the user is a member (not owner).
     */
    public function memberOfTeams()
    {
        return $this->teams()->whereNot('owner_id', $this->id);
    }

    /**
     * Get the user's primary team.
     */
    public function currentTeam(): ?Team
    {
        if ($this->current_team_id) {
            return $this->belongsTo(Team::class, 'current_team_id')->first();
        }
        
        // If no current team is set, return the first team
        return $this->teams()->first() ?: $this->ownedTeams()->first();
    }

    /**
     * Get all teams a user has access to (owned + joined).
     */
    public function allTeams()
    {
        return $this->ownedTeams->merge($this->teams);
    }

    /**
     * Determine if the user belongs to the given team.
     */
    public function belongsToTeam(Team $team): bool
    {
        return $this->teams->contains($team) || $this->ownsTeam($team);
    }

    /**
     * Determine if the user owns the given team.
     */
    public function ownsTeam(Team $team): bool
    {
        return $this->id === $team->owner_id;
    }

    /**
     * Get the role that the user has on the team.
     */
    public function teamRole(Team $team): ?string
    {
        if ($this->ownsTeam($team)) {
            return 'owner';
        }

        if (!$this->belongsToTeam($team)) {
            return null;
        }

        $membership = $this->teams->find($team->id)->pivot;

        return $membership->role;
    }

    /**
     * Create a new personal team for the user.
     */
    public function createPersonalTeam(string $name): Team
    {
        return $this->ownedTeams()->create([
            'name' => $name,
            'personal_team' => true,
        ]);
    }
    
    /**
     * Get the total available SMS credits for the user (personal + shared from teams)
     */
    public function getAvailableSmsCredits(): int
    {
        return app(TeamResourceService::class)->getAvailableSmsCredits($this);
    }
    
    /**
     * Get the total available USSD credits for the user (personal + shared from teams)
     */
    public function getAvailableUssdCredits(): int
    {
        return app(TeamResourceService::class)->getAvailableUssdCredits($this);
    }
    
    /**
     * Check if the user can use a specific team resource type
     */
    public function canUseTeamResource(string $resourceType): bool
    {
        return app(TeamResourceService::class)->canUseTeamResource($this, $resourceType);
    }
    
    /**
     * Check if the user has access to a team with shared resources
     */
    public function hasTeamWithSharedResources(): bool
    {
        foreach ($this->teams as $team) {
            if ($team->share_sms_credits || $team->share_contacts || $team->share_sender_names) {
                return true;
            }
        }
        
        return false;
    }
}
