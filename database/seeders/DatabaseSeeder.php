<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run the Roles and Permissions seeder first
        $this->call(RolesAndPermissionsSeeder::class);

        // Setup currencies and billing tiers
        $this->call(CurrencySeeder::class);
        $this->call(BillingTierSeeder::class);

        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        // Assign the Super Admin role to our test user
        $user->assignRole('super admin');
    }
}
