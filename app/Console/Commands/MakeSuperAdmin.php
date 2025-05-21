<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MakeSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-super-admin {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set a specified user as a super admin';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email') ?: 'johnson@manifestghana.com';
        
        try {
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->error("User with email {$email} not found.");
                return Command::FAILURE;
            }
            
            DB::transaction(function () use ($user) {
                // Remove any existing roles
                $user->roles()->detach();
                
                // Assign super admin role
                $user->assignRole('super admin');
                
                // Log the action
                Log::info("User {$user->email} was assigned the super admin role");
            });
            
            $this->info("User {$email} successfully set as super admin!");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("An error occurred: {$e->getMessage()}");
            Log::error("Failed to set user as super admin: {$e->getMessage()}", [
                'email' => $email,
                'exception' => $e,
            ]);
            return Command::FAILURE;
        }
    }
}