<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsRecipient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'campaign_id',
        'phone_number',
        'status',
        'delivered_at',
        'error_message',
        'provider_message_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'delivered_at' => 'datetime',
    ];

    /**
     * Get the campaign that this recipient belongs to.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(SmsCampaign::class, 'campaign_id');
    }

    /**
     * Mark the message as delivered
     */
    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
            'error_message' => null
        ]);

        // Update campaign metrics
        $this->campaign->increment('delivered_count');
    }

    /**
     * Mark the message as failed
     */
    public function markAsFailed(string $errorMessage = null): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage ?? 'Failed to deliver message',
            'delivered_at' => null
        ]);

        // Update campaign metrics
        $this->campaign->increment('failed_count');
    }

    /**
     * Check if the message is delivered
     */
    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    /**
     * Check if the message failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if the message is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Get the formatted phone number
     */
    public function getFormattedPhoneNumber(): string
    {
        // Basic phone number formatting - can be enhanced based on country codes
        return preg_replace('/(\d{3})(\d{3})(\d{4})/', '$1-$2-$3', $this->phone_number);
    }
}