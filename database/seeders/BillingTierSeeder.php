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
                'description' => "For businesses just getting started.\nFeatures:\n• All SMS Features\n• Custom Sender Names\n• Delivery Reports\n• API Access",
                'price_per_sms' => 0.035, // 0.035 cedis per SMS
                'min_purchase' => 0,
                'max_purchase' => 1499.99,
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Plus',
                'description' => "Better rates for regular users.\nFeatures:\n• All Basic Features\n• 8.6% Cost Savings\n• Priority Support\n• Advanced Analytics",
                'price_per_sms' => 0.032, // 0.032 cedis per SMS
                'min_purchase' => 1500,
                'max_purchase' => 2999.99,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Premium',
                'description' => "Great value for high volume. Popular.\nFeatures:\n• All Plus Features\n• 17% Cost Savings\n• Dedicated Account Manager\n• Custom Integrations",
                'price_per_sms' => 0.029, // 0.029 cedis per SMS
                'min_purchase' => 3000,
                'max_purchase' => 5999.99,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Gold',
                'description' => "Best rates for enterprise.\nFeatures:\n• All Premium Features\n• 29% Cost Savings\n• 24/7 Premium Support\n• SLA Guarantee",
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
