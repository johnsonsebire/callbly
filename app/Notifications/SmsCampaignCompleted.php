<?php

namespace App\Notifications;

use App\Models\SmsCampaign;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SmsCampaignCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public SmsCampaign $campaign
    ) {
        $this->onQueue('notifications');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->campaign->status === 'completed' 
            ? 'SMS Campaign Completed Successfully'
            : 'SMS Campaign Failed';
            
        $greeting = $this->campaign->status === 'completed'
            ? 'Great news!'
            : 'We need to inform you';
            
        $line1 = $this->campaign->status === 'completed'
            ? "Your SMS campaign \"{$this->campaign->name}\" has been completed successfully."
            : "Your SMS campaign \"{$this->campaign->name}\" has failed to complete.";

        $mailMessage = (new MailMessage)
            ->subject($subject)
            ->greeting("Hello {$notifiable->name},")
            ->line($greeting)
            ->line($line1)
            ->line("Campaign Details:")
            ->line("• Campaign ID: #{$this->campaign->id}")
            ->line("• Recipients: " . number_format($this->campaign->recipients_count))
            ->line("• Delivered: " . number_format($this->campaign->delivered_count))
            ->line("• Failed: " . number_format($this->campaign->failed_count))
            ->line("• Success Rate: " . number_format($this->campaign->getSuccessRate(), 1) . "%")
            ->action('View Campaign Details', route('sms.campaign-details', $this->campaign->id));

        if ($this->campaign->status === 'completed') {
            $mailMessage->line('Thank you for using our SMS service!');
        } else {
            $mailMessage->line('Please check the campaign details for more information about any failures.');
        }

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'campaign_id' => $this->campaign->id,
            'campaign_name' => $this->campaign->name,
            'status' => $this->campaign->status,
            'recipients_count' => $this->campaign->recipients_count,
            'delivered_count' => $this->campaign->delivered_count,
            'failed_count' => $this->campaign->failed_count,
            'success_rate' => $this->campaign->getSuccessRate(),
            'completed_at' => $this->campaign->completed_at?->toISOString(),
        ];
    }
}
