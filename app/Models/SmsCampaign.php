<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'started_at',
        'completed_at',
        'provider_response',
        'credits_used',
        'provider_batch_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'provider_response' => 'array',
        'credits_used' => 'integer',
    ];

    /**
     * Get the user that owns the SMS campaign.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the recipients for the SMS campaign.
     */
    public function recipients(): HasMany
    {
        return $this->hasMany(SmsRecipient::class, 'campaign_id');
    }

    /**
     * Calculate the total credits used for this campaign
     */
    public function calculateCreditsUsed(): int
    {
        $messageLength = mb_strlen($this->message);
        $hasUnicode = preg_match('/[\x{0080}-\x{FFFF}]/u', $this->message);
        
        // Calculate message pages
        if ($hasUnicode) {
            $parts = $messageLength <= 70 ? 1 : ceil(($messageLength - 70) / 67) + 1;
        } else {
            $parts = $messageLength <= 160 ? 1 : ceil(($messageLength - 160) / 153) + 1;
        }
        
        return $parts * $this->recipients_count;
    }

    /**
     * Update campaign metrics from recipients
     */
    public function updateMetrics(): void
    {
        $metrics = $this->recipients()
            ->selectRaw('
                COUNT(*) as total_count,
                SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as delivered_count,
                SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed_count,
                SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_count
            ')
            ->first();

        // Calculate the appropriate status based on recipient statuses
        $newStatus = $this->calculateStatus(
            $metrics->total_count ?? 0,
            $metrics->delivered_count ?? 0,
            $metrics->failed_count ?? 0,
            $metrics->pending_count ?? 0
        );

        $this->update([
            'recipients_count' => $metrics->total_count ?? 0,
            'delivered_count' => $metrics->delivered_count ?? 0,
            'failed_count' => $metrics->failed_count ?? 0,
            'credits_used' => $this->calculateCreditsUsed(),
            'status' => $newStatus
        ]);
    }

    /**
     * Calculate campaign status based on recipient statuses
     */
    protected function calculateStatus(int $totalCount, int $deliveredCount, int $failedCount, int $pendingCount): string
    {
        // Edge case: no recipients
        if ($totalCount === 0) {
            return 'pending';
        }

        // If all messages failed
        if ($failedCount === $totalCount) {
            return 'failed';
        }

        // If some or all messages delivered and none pending
        if ($deliveredCount > 0 && $pendingCount === 0) {
            return 'completed';
        }

        // If all messages pending
        if ($pendingCount === $totalCount) {
            return 'pending';
        }

        // Mixed status with some pending
        if ($pendingCount > 0) {
            return 'processing';
        }

        // Default fallback
        return 'sent';
    }

    /**
     * Get the delivery success rate as a percentage
     */
    public function getSuccessRate(): float
    {
        if ($this->recipients_count === 0) return 0;
        return round(($this->delivered_count / $this->recipients_count) * 100, 2);
    }

    /**
     * Get the delivery failure rate as a percentage
     */
    public function getFailureRate(): float
    {
        if ($this->recipients_count === 0) return 0;
        return round(($this->failed_count / $this->recipients_count) * 100, 2);
    }

    /**
     * Get the pending rate as a percentage
     */
    public function getPendingRate(): float
    {
        if ($this->recipients_count === 0) return 0;
        $pendingCount = $this->recipients_count - ($this->delivered_count + $this->failed_count);
        return round(($pendingCount / $this->recipients_count) * 100, 2);
    }

    /**
     * Get total credits used for the campaign
     */
    public function getTotalCreditsUsed(): int
    {
        return $this->credits_used ?? $this->calculateCreditsUsed();
    }
}
