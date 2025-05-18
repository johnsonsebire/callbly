<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UssdService extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'shortcode',
        'menu_structure',
        'status',
        'callback_url',
        'monthly_requests',
        'activated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'menu_structure' => 'json',
        'monthly_requests' => 'integer',
        'activated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the USSD service.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
