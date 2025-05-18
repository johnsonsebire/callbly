<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VirtualNumber extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'number',
        'country_code',
        'status',
        'type',
        'monthly_fee',
        'features',
        'forwarding_number',
        'reserved_until',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'monthly_fee' => 'decimal:2',
        'reserved_until' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the virtual number.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the orders for this virtual number.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
