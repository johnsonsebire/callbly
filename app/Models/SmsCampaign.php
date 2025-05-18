<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsCampaign extends Model
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
        'message',
        'sender_name',
        'status',
        'recipients_count',
        'delivered_count',
        'failed_count',
        'scheduled_at',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the SMS campaign.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
