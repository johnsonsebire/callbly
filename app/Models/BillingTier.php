<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BillingTier extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price_per_sms',
        'min_purchase',
        'max_purchase',
        'is_default',
        'is_active',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price_per_sms' => 'decimal:6',
        'min_purchase' => 'decimal:2',
        'max_purchase' => 'decimal:2',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];
    
    /**
     * Get users who are on this billing tier.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    
    /**
     * Get the default billing tier.
     *
     * @return self
     */
    public static function getDefaultTier(): self
    {
        $tier = self::where('is_default', true)->first() ?? self::where('name', 'Basic')->first();
        
        // If no tier is found in the database, create a default one
        if (!$tier) {
            $tier = new self([
                'name' => 'Basic',
                'description' => 'Default basic tier',
                'price_per_sms' => 0.035,
                'min_purchase' => 0.00,
                'max_purchase' => 1499.99,
                'is_default' => true,
                'is_active' => true,
            ]);
            
            // This is a fallback only - we don't save it to the database here
            // as this might be called before migrations have run
        }
        
        return $tier;
    }
    
    /**
     * Get the appropriate tier for a purchase amount
     *
     * @param float $purchaseAmount
     * @return self
     */
    public static function getTierForPurchase(float $purchaseAmount): self
    {
        return self::where('is_active', true)
            ->where('min_purchase', '<=', $purchaseAmount)
            ->where(function ($query) use ($purchaseAmount) {
                $query->where('max_purchase', '>=', $purchaseAmount)
                    ->orWhereNull('max_purchase');
            })
            ->orderByDesc('price_per_sms') // Get the most expensive tier that matches the criteria
            ->first() ?? self::getDefaultTier();
    }
    
    /**
     * Calculate the number of SMS credits for an amount in the base currency
     *
     * @param float $amount
     * @return int
     */
    public function calculateSmsCredits(float $amount): int
    {
        return intval(floor($amount / $this->price_per_sms));
    }
}
