<?php

namespace Database\Seeders;

use App\Models\BillingTier;
use Illuminate\Database\Seeder;

class BillingTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiers = [
            [
                'name' => 'Basic',
                'description' => 'Basic tier with standard pricing. Purchase any amount of SMS credits.',
                'price_per_sms' => 0.035, // 0.035 cedis per SMS
                'min_purchase' => 0,
                'max_purchase' => 1499.99,
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Plus',
                'description' => 'Plus tier with better pricing for medium volume users.',
                'price_per_sms' => 0.032, // 0.032 cedis per SMS
                'min_purchase' => 1500,
                'max_purchase' => 2999.99,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Premium',
                'description' => 'Premium tier with favorable pricing for high volume users.',
                'price_per_sms' => 0.029, // 0.029 cedis per SMS
                'min_purchase' => 3000,
                'max_purchase' => 5999.99,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Gold',
                'description' => 'Gold tier with best pricing for enterprise users.',
                'price_per_sms' => 0.025, // 0.025 cedis per SMS
                'min_purchase' => 6000,
                'max_purchase' => null, // No upper limit
                'is_default' => false,
                'is_active' => true,
            ],
        ];

        foreach ($tiers as $tier) {
            BillingTier::updateOrCreate(
                ['name' => $tier['name']],
                $tier
            );
        }
    }
}
