<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServicePlan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'description',
        'price',
        'validity_days',
        'features',
        'units',
        'is_popular',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'validity_days' => 'integer',
        'features' => 'json',
        'units' => 'integer',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the orders for this service plan.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scope a query to only include active service plans.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
