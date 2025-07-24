<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignCustomerRoleToUsersWithoutRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-customer-role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign customer role to users who do not have any roles assigned';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting assignment of customer role to users without roles...');

        try {
            // Get or create system team for role assignment
            $systemTeam = \App\Models\Team::firstOrCreate([
                'name' => 'System',
                'slug' => 'system'
            ], [
                'owner_id' => 1, // Default to first user
                'description' => 'System-wide roles and permissions'
            ]);

            // Set team context for permission system
            app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($systemTeam->id);
            
            // Ensure customer role exists
            $customerRole = Role::firstOrCreate(['name' => 'customer']);
            
            // Find users without any roles
            $usersWithoutRoles = User::whereDoesntHave('roles')->get();
            
            if ($usersWithoutRoles->isEmpty()) {
                $this->info('No users found without roles. All users already have roles assigned.');
                return Command::SUCCESS;
            }
            
            $this->info("Found {$usersWithoutRoles->count()} users without roles.");
            
            $assigned = 0;
            
            foreach ($usersWithoutRoles as $user) {
                try {
                    // Set user's current team if they don't have one
                    if (!$user->current_team_id) {
                        $user->current_team_id = $systemTeam->id;
                        $user->save();
                    }
                    
                    $user->assignRole('customer');
                    $this->line("✅ Assigned customer role to: {$user->email}");
                    $assigned++;
                } catch (\Exception $e) {
                    $this->error("❌ Failed to assign role to {$user->email}: {$e->getMessage()}");
                }
            }
            
            $this->info("✅ Successfully assigned customer role to {$assigned} users.");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Command failed: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
}
