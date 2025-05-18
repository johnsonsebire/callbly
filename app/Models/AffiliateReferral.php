<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AffiliateReferral extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'referrer_id',
        'referred_id',
        'referral_code',
        'referral_link',
        'clicks',
        'signups',
        'conversions',
        'earnings',
        'paid_amount',
        'pending_amount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'clicks' => 'integer',
        'signups' => 'integer',
        'conversions' => 'integer',
        'earnings' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'pending_amount' => 'decimal:2',
    ];

    /**
     * Get the referrer user.
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * Get the referred user.
     */
    public function referred(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    /**
     * Get the orders associated with this affiliate referral.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Generate a unique referral code.
     *
     * @return string
     */
    public static function generateReferralCode(): string
    {
        $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        
        // Check if code already exists
        while (self::where('referral_code', $code)->exists()) {
            $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        }
        
        return $code;
    }

    /**
     * Calculate commission for a given amount.
     *
     * @param float $amount
     * @return float
     */
    public function calculateCommission(float $amount): float
    {
        // Default commission is 10%
        $commissionRate = 0.10;
        
        // Apply tiered commission rates based on performance
        if ($this->conversions >= 100) {
            $commissionRate = 0.20; // 20% for high performers
        } elseif ($this->conversions >= 50) {
            $commissionRate = 0.15; // 15% for medium performers
        }
        
        return $amount * $commissionRate;
    }
}
