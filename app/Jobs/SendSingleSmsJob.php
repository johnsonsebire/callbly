<?php

namespace App\Jobs;

use App\Models\SmsCampaign;
use App\Services\SmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class SendSingleSmsJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     *
     * @var int
     */
    public $timeout = 60; // 1 minute

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $campaignId,
        public string $recipient,
        public string $message,
        public string $senderName,
        public ?int $contactId = null
    ) {
        // Set queue name for SMS processing
        $this->onQueue('sms');
    }

    /**
     * Execute the job.
     */
    public function handle(SmsService $smsService): void
    {
        try {
            Log::info('Starting single SMS job processing', [
                'campaign_id' => $this->campaignId,
                'recipient' => $this->recipient,
                'job_id' => $this->job->getJobId()
            ]);

            // Update campaign status to processing
            $campaign = SmsCampaign::find($this->campaignId);
            if (!$campaign) {
                throw new Exception("Campaign {$this->campaignId} not found");
            }

            $campaign->update([
                'status' => 'processing',
                'started_at' => now()
            ]);

            // Process the single SMS through the existing service
            $result = $smsService->sendSingle(
                $this->recipient,
                $this->message,
                $this->senderName,
                $this->campaignId,
                $this->contactId
            );

            // Update campaign status based on result
            if ($result['success']) {
                $campaign->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                    'delivered_count' => 1
                ]);
                
                Log::info('Single SMS job completed successfully', [
                    'campaign_id' => $this->campaignId,
                    'recipient' => $this->recipient
                ]);
                
                // Send completion notification
                $campaign->user->notify(new \App\Notifications\SmsCampaignCompleted($campaign));
            } else {
                $campaign->update([
                    'status' => 'failed',
                    'completed_at' => now(),
                    'failed_count' => 1
                ]);
                
                Log::error('Single SMS job failed', [
                    'campaign_id' => $this->campaignId,
                    'recipient' => $this->recipient,
                    'error' => $result['message'] ?? 'Unknown error'
                ]);
                
                // Send failure notification
                $campaign->user->notify(new \App\Notifications\SmsCampaignCompleted($campaign));
            }

        } catch (Exception $e) {
            Log::error('Single SMS job exception', [
                'campaign_id' => $this->campaignId,
                'recipient' => $this->recipient,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Update campaign status to failed
            if (isset($campaign)) {
                $campaign->update([
                    'status' => 'failed',
                    'completed_at' => now(),
                    'failed_count' => 1
                ]);
            }

            // Re-throw to trigger job failure
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Exception $exception): void
    {
        Log::error('Single SMS job failed permanently', [
            'campaign_id' => $this->campaignId,
            'recipient' => $this->recipient,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);

        // Update campaign status to failed
        $campaign = SmsCampaign::find($this->campaignId);
        if ($campaign) {
            $campaign->update([
                'status' => 'failed',
                'completed_at' => now(),
                'failed_count' => 1
            ]);
            
            // Send failure notification
            $campaign->user->notify(new \App\Notifications\SmsCampaignCompleted($campaign));
        }
    }
}
