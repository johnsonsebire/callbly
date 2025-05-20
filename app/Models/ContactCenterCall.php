<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactCenterCall extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'from_number',
        'to_number',
        'duration',
        'status',
        'direction',
        'reference_id',
        'recording_enabled',
        'callback_url',
        'call_timeout',
        'metadata',
        'cost',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'duration' => 'integer',
        'recording_enabled' => 'boolean',
        'call_timeout' => 'integer',
        'metadata' => 'array',
        'cost' => 'decimal:2',
    ];

    /**
     * Get the user that owns the contact center call.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
