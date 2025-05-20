<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class MakeSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-super-admin {email : The email of the user to make super admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a user a super admin';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        
        // Find the user by email
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return Command::FAILURE;
        }
        
        // Check if super-admin role exists, if not create it
        $superAdminRole = Role::where('name', 'super-admin')->first();
        if (!$superAdminRole) {
            $superAdminRole = Role::create(['name' => 'super-admin', 'guard_name' => 'web']);
            $this->info('Created "super-admin" role');
        }
        
        // Assign super-admin role to the user
        $user->assignRole('super-admin');
        
        // Set role field to 'admin' 
        $user->role = 'admin';
        $user->save();
        
        $this->info("User {$user->name} ({$email}) has been made a super admin!");
        return Command::SUCCESS;
    }
}