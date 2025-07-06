<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartSmsQueueWorker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:work {--daemon : Run as daemon} {--timeout=300 : Job timeout in seconds}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start processing SMS jobs from the queue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting SMS Queue Worker...');
        
        $timeout = $this->option('timeout');
        $isDaemon = $this->option('daemon');
        
        $command = "php artisan queue:work --queue=sms,notifications,default --timeout={$timeout} --tries=3 --backoff=5";
        
        if ($isDaemon) {
            $command .= ' --daemon';
            $this->info('Running as daemon - use Ctrl+C to stop');
        } else {
            $command .= ' --stop-when-empty';
            $this->info('Processing available jobs then stopping');
        }
        
        $this->info("Executing: {$command}");
        
        // Execute the queue work command
        system($command);
        
        $this->info('SMS Queue Worker stopped.');
        
        return 0;
    }
}
