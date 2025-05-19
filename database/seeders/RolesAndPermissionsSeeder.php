<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for SMS module
        $smsPermissions = [
            'view sms dashboard',
            'send sms',
            'create contact',
            'edit contact',
            'delete contact',
            'view contact',
            'create contact group',
            'edit contact group',
            'delete contact group',
            'view contact group',
            'register sender name',
            'view sender name',
            'purchase sms credits',
            'view sms campaigns',
            'create sms campaign',
            'edit sms campaign',
            'delete sms campaign',
        ];

        // Create permissions for admin functions
        $adminPermissions = [
            'approve sender name',
            'reject sender name',
            'manage users',
            'manage roles',
            'manage permissions',
            'view admin dashboard',
            'view all sms campaigns',
            'view all sender names',
            'view all contacts',
            'view all contact groups',
            'view financial reports',
            'manage sms pricing',
            'manage sms providers',
        ];

        // Create permissions for USSD and Contact Center modules
        $otherPermissions = [
            'manage ussd services',
            'manage contact center calls',
            'manage virtual numbers',
        ];

        // Combine all permissions
        $allPermissions = array_merge($smsPermissions, $adminPermissions, $otherPermissions);

        // Create permissions
        foreach ($allPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Super Admin role
        $superAdminRole = Role::create(['name' => 'super admin']);
        // Super admin gets everything
        $superAdminRole->givePermissionTo(Permission::all());
        
        // Staff role
        $staffRole = Role::create(['name' => 'staff']);
        // Staff gets admin permissions except for managing roles/permissions
        $staffRole->givePermissionTo(array_diff(
            $allPermissions, 
            ['manage roles', 'manage permissions', 'view financial reports']
        ));
        
        // Customer role
        $customerRole = Role::create(['name' => 'customer']);
        // Customers get SMS permissions and some other basic permissions
        $customerRole->givePermissionTo($smsPermissions);
        $customerRole->givePermissionTo([
            'manage ussd services',
            'manage contact center calls',
            'manage virtual numbers',
        ]);
    }
}
