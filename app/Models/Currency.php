<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'symbol',
        'exchange_rate',
        'is_default',
        'is_active',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'exchange_rate' => 'decimal:4',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the default currency.
     *
     * @return self
     */
    public static function getDefaultCurrency(): self
    {
        $currency = self::where('is_default', true)->first() ?? self::where('code', 'GHS')->first();
        
        // If no currency is found in the database, create a default one
        if (!$currency) {
            $currency = new self([
                'name' => 'Ghana Cedi',
                'code' => 'GHS',
                'symbol' => '₵',
                'exchange_rate' => 1.0000,
                'is_default' => true,
                'is_active' => true,
            ]);
            
            // This is a fallback only - we don't save it to the database here
            // as this might be called before migrations have run
        }
        
        return $currency;
    }
    
    /**
     * Format an amount in this currency.
     *
     * @param float $amount
     * @param bool $includeSymbol
     * @param int $decimals Number of decimal places (default: 2)
     * @return string
     */
    public function format(float $amount, bool $includeSymbol = true, int $decimals = 2): string
    {
        $formattedAmount = number_format($amount, $decimals);
        return $includeSymbol ? "{$this->symbol}{$formattedAmount}" : $formattedAmount;
    }
}
