<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'currency_id',
        'balance'
    ];

    /**
     * Get the user that owns the wallet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the currency for this wallet.
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    
    /**
     * Add funds to wallet.
     *
     * @param float $amount
     * @return bool
     */
    public function addFunds($amount)
    {
        if ($amount <= 0) {
            return false;
        }
        
        $this->balance += $amount;
        return $this->save();
    }
    
    /**
     * Deduct funds from wallet.
     *
     * @param float $amount
     * @return bool
     */
    public function deductFunds($amount)
    {
        if ($amount <= 0 || $this->balance < $amount) {
            return false;
        }
        
        $this->balance -= $amount;
        return $this->save();
    }
    
    /**
     * Check if the wallet has enough funds.
     *
     * @param float $amount
     * @return bool
     */
    public function hasSufficientFunds($amount)
    {
        return $this->balance >= $amount;
    }
}
