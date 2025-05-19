<?php

namespace App\Services\Currency;

use App\Models\BillingTier;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class CurrencyService
{
    /**
     * Default cache time for currency rates (12 hours)
     */
    private const CACHE_TTL = 43200;

    /**
     * Get all active currencies
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveCurrencies()
    {
        return Cache::remember('active_currencies', self::CACHE_TTL, function () {
            return Currency::where('is_active', true)->get();
        });
    }

    /**
     * Get the default currency
     *
     * @return Currency
     */
    public function getDefaultCurrency(): Currency
    {
        return Cache::remember('default_currency', self::CACHE_TTL, function () {
            return Currency::getDefaultCurrency();
        });
    }

    /**
     * Convert an amount from one currency to another
     *
     * @param float $amount
     * @param Currency|string $fromCurrency Currency object or currency code
     * @param Currency|string|null $toCurrency Currency object or currency code (null for default)
     * @return float
     */
    public function convert(float $amount, $fromCurrency, $toCurrency = null): float
    {
        // Get currency objects if codes were provided
        if (is_string($fromCurrency)) {
            $fromCurrency = $this->getCurrencyByCode($fromCurrency);
        }
        
        if ($toCurrency === null) {
            $toCurrency = $this->getDefaultCurrency();
        } elseif (is_string($toCurrency)) {
            $toCurrency = $this->getCurrencyByCode($toCurrency);
        }
        
        // Convert amount to the base currency first (if not already)
        if ($fromCurrency->code !== 'GHS') {
            $amount = $amount / $fromCurrency->exchange_rate;
        }
        
        // Then convert to target currency (if different from base)
        if ($toCurrency->code !== 'GHS') {
            $amount = $amount * $toCurrency->exchange_rate;
        }
        
        return $amount;
    }

    /**
     * Format an amount with the specified currency
     *
     * @param float $amount
     * @param Currency|string|null $currency
     * @param bool $includeSymbol
     * @return string
     */
    public function format(float $amount, $currency = null, bool $includeSymbol = true): string
    {
        if ($currency === null) {
            $currency = $this->getDefaultCurrency();
        } elseif (is_string($currency)) {
            $currency = $this->getCurrencyByCode($currency);
        }
        
        return $currency->format($amount, $includeSymbol);
    }

    /**
     * Get a currency by its code
     *
     * @param string $code
     * @return Currency
     */
    public function getCurrencyByCode(string $code): Currency
    {
        return Cache::remember("currency_{$code}", self::CACHE_TTL, function () use ($code) {
            return Currency::where('code', $code)->first() 
                ?? $this->getDefaultCurrency();
        });
    }
    
    /**
     * Get all billing tiers
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBillingTiers()
    {
        return Cache::remember('billing_tiers', self::CACHE_TTL, function () {
            return BillingTier::where('is_active', true)
                ->orderBy('min_purchase')
                ->get();
        });
    }
    
    /**
     * Get SMS price for a user in their preferred currency
     * 
     * @param User $user
     * @return float
     */
    public function getUserSmsPrice(User $user): float
    {
        $basePricePerSms = $user->billingTier->price_per_sms;
        $userCurrency = $user->currency;
        
        // Convert base price to user's currency
        return $basePricePerSms * $userCurrency->exchange_rate;
    }
    
    /**
     * Calculate the appropriate billing tier for a purchase
     *
     * @param float $amount Amount in base currency (GHS)
     * @return BillingTier
     */
    public function determineBillingTierForPurchase(float $amount): BillingTier
    {
        return BillingTier::getTierForPurchase($amount);
    }
    
    /**
     * Update a user's billing tier based on purchase history
     *
     * @param User $user
     * @param float $purchaseAmount Amount in base currency (GHS)
     * @return void
     */
    public function updateUserBillingTier(User $user, float $purchaseAmount): void
    {
        $appropriateTier = $this->determineBillingTierForPurchase($purchaseAmount);
        
        // Only upgrade tiers - never downgrade automatically
        if ($user->billing_tier_id === null || 
            $appropriateTier->price_per_sms < $user->billingTier->price_per_sms) {
            $user->billing_tier_id = $appropriateTier->id;
            $user->save();
        }
    }
}