<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

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
                // Temporarily disable teams to assign global role
                $originalTeamId = getPermissionsTeamId();
                setPermissionsTeamId(null);
                
                try {
                    // Find or create the super admin role as a global role (team_id = null)
                    $superAdminRole = Role::where('name', 'super admin')
                        ->where('guard_name', 'web')
                        ->whereNull('team_id')
                        ->first();
                    
                    if (!$superAdminRole) {
                        // Create the role with explicit team_id = null
                        $superAdminRole = new Role();
                        $superAdminRole->name = 'super admin';
                        $superAdminRole->guard_name = 'web';
                        $superAdminRole->team_id = null;
                        $superAdminRole->save();
                        
                        $this->info("Created global super admin role.");
                    }
                    
                    // Remove any existing role assignments for this user
                    DB::table('model_has_roles')
                        ->where('model_type', User::class)
                        ->where('model_id', $user->id)
                        ->delete();
                    
                    // Assign the global super admin role
                    DB::table('model_has_roles')->insert([
                        'role_id' => $superAdminRole->id,
                        'model_type' => User::class,
                        'model_id' => $user->id,
                        'team_id' => null, // Explicitly set as global role
                    ]);
                    
                    // Clear the permission cache
                    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
                    
                    // Log the action
                    Log::info("User {$user->email} was assigned the global super admin role");
                    
                } finally {
                    // Restore original team context
                    setPermissionsTeamId($originalTeamId);
                }
            });
            
            $this->info("User {$email} successfully set as global super admin!");
            
            // Verify the assignment worked
            setPermissionsTeamId(null); // Check in global context
            if ($user->hasRole('super admin')) {
                $this->info("✓ Role assignment verified successfully!");
            } else {
                $this->warn("⚠ Role assignment may not have worked properly.");
            }
            
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