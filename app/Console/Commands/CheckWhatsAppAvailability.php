<?php

namespace App\Console\Commands;

use App\Services\Contact\WhatsAppDetectionService;
use Illuminate\Console\Command;

class CheckWhatsAppAvailability extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contacts:check-whatsapp {--limit=100 : Maximum number of contacts to check} {--force : Force check all contacts regardless of last check date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check WhatsApp availability for contacts with stale or missing WhatsApp status';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppDetectionService $whatsAppService)
    {
        $this->info('Starting WhatsApp availability check...');
        
        $limit = $this->option('limit');
        $force = $this->option('force');
        
        if ($force) {
            $this->info('Force checking all contacts...');
            $checkedCount = $whatsAppService->checkStaleContacts();
        } else {
            $checkedCount = $whatsAppService->checkStaleContacts();
        }
        
        $this->info("Checked WhatsApp availability for {$checkedCount} contacts.");
        
        return Command::SUCCESS;
    }
}
