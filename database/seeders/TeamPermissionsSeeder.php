<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TeamPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define team permissions that exactly match the Gates used in controllers
        $teamPermissions = [
            'view-team',           // Used in TeamController->show()
            'update-team',         // Used in TeamController->edit() and update()
            'delete-team',         // Used in TeamController->destroy()
            'update-team-member',  // Used in TeamController->updateMember()
            'remove-team-member',  // Used in TeamController->removeMember()
            'invite-to-team',      // Used in TeamInvitationController
            'view-team-resources', // Permission for viewing team resources
            'use-team-resources',  // Permission for using team resources
        ];

        // Create permissions if they don't exist
        foreach ($teamPermissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // Get the super-admin role
        $superAdminRole = Role::findByName('super admin', 'web');
        
        // Add all team permissions to super-admin role
        $superAdminRole->givePermissionTo($teamPermissions);

        $this->command->info('Team permissions assigned to super admin role successfully.');
    }
}
