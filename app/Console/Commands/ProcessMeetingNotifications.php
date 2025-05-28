<?php

namespace App\Console\Commands;

use App\Services\Meeting\MeetingNotificationService;
use Illuminate\Console\Command;

class ProcessMeetingNotifications extends Command
{
    protected $signature = 'meeting:process-notifications';
    protected $description = 'Process due meeting notifications (reminders, confirmations, etc.)';

    protected MeetingNotificationService $notificationService;

    public function __construct(MeetingNotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    public function handle(): int
    {
        $this->info('Processing due meeting notifications...');

        try {
            $this->notificationService->processDueNotifications();
            $this->info('Meeting notifications processed successfully.');
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to process meeting notifications: ' . $e->getMessage());
            return 1;
        }
    }
}