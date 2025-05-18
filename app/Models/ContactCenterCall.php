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
        'caller_number',
        'receiver_number',
        'duration',
        'status',
        'notes',
        'ivr_path',
        'recording_url',
        'cost',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'duration' => 'integer',
        'ivr_path' => 'json',
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
