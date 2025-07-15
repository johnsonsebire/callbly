<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Team;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MigrateToSpatieRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:migrate-to-spatie-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate users from database role column to Spatie Permission roles';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting migration from database roles to Spatie Permission roles...');
        
        try {
            // Get or create system team
            $systemTeam = Team::firstOrCreate([
                'name' => 'System',
                'slug' => 'system'
            ], [
                'owner_id' => 1, // Default to first user
                'description' => 'System-wide roles and permissions'
            ]);

            // Set team context
            app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($systemTeam->id);

            $users = User::all();
            $migrated = 0;
            $skipped = 0;

            foreach ($users as $user) {
                // Skip if user already has Spatie roles
                if ($user->roles()->count() > 0) {
                    $this->info("User {$user->email} already has Spatie roles, skipping...");
                    $skipped++;
                    continue;
                }

                // Assign Spatie role based on database role
                $spatieRole = $this->mapDatabaseRoleToSpatieRole($user->role);
                
                if ($spatieRole) {
                    DB::transaction(function () use ($user, $spatieRole, $systemTeam) {
                        $user->assignRole($spatieRole);
                        
                        // Set user's current team if they don't have one
                        if (!$user->current_team_id) {
                            $user->current_team_id = $systemTeam->id;
                            $user->save();
                        }
                    });

                    $this->info("Migrated user {$user->email}: '{$user->role}' -> '{$spatieRole}'");
                    $migrated++;
                } else {
                    $this->warn("Unknown role '{$user->role}' for user {$user->email}, assigning 'customer' by default");
                    
                    DB::transaction(function () use ($user, $systemTeam) {
                        $user->assignRole('customer');
                        
                        if (!$user->current_team_id) {
                            $user->current_team_id = $systemTeam->id;
                            $user->save();
                        }
                    });
                    
                    $migrated++;
                }
            }

            $this->info("Migration completed! Migrated: {$migrated}, Skipped: {$skipped}");
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Migration failed: {$e->getMessage()}");
            Log::error("Failed to migrate to Spatie roles", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }

    /**
     * Map database role to Spatie Permission role
     */
    private function mapDatabaseRoleToSpatieRole(string $databaseRole): ?string
    {
        return match($databaseRole) {
            'admin' => 'staff', // Map 'admin' to 'staff' role
            'user' => 'customer', // Map 'user' to 'customer' role
            'super_admin' => 'super admin', // In case there are any
            default => null
        };
    }
}
