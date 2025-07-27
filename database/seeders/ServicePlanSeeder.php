<?php

namespace Database\Seeders;

use App\Models\ServicePlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicePlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Note: All prices are stored in the base currency (GHS - Ghana Cedis).
     * The application's currency service will handle conversion to user's preferred currency.
     */
    public function run(): void
    {
        $plans = [
            // SMS Plans
            [
                'name' => 'SMS Starter',
                'type' => 'sms',
                'description' => 'Perfect for small businesses getting started with SMS marketing',
                'price' => 2500.00, // Base currency: GHS
                'validity_days' => 30,
                'units' => 1000,
                'features' => [
                    '1,000 SMS credits',
                    'Sender name registration',
                    'Basic analytics',
                    'Email support'
                ],
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'name' => 'SMS Business',
                'type' => 'sms',
                'description' => 'Ideal for growing businesses with regular SMS campaigns',
                'price' => 7500.00,
                'validity_days' => 30,
                'units' => 3500,
                'features' => [
                    '3,500 SMS credits',
                    'Multiple sender names',
                    'Advanced analytics',
                    'Contact management',
                    'Priority support'
                ],
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'name' => 'SMS Enterprise',
                'type' => 'sms',
                'description' => 'For large enterprises with high-volume SMS needs',
                'price' => 15000.00,
                'validity_days' => 30,
                'units' => 8000,
                'features' => [
                    '8,000 SMS credits',
                    'Unlimited sender names',
                    'Premium analytics',
                    'API access',
                    'Dedicated support',
                    'Custom integrations'
                ],
                'is_popular' => false,
                'is_active' => true,
            ],

            // Voice Plans
            [
                'name' => 'Voice Basic',
                'type' => 'voice',
                'description' => 'Basic voice calling features for personal use',
                'price' => 3000.00,
                'validity_days' => 30,
                'units' => 120, // minutes
                'features' => [
                    '120 minutes calling',
                    'Call recording',
                    'Basic call logs',
                    'Standard support'
                ],
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Voice Pro',
                'type' => 'voice',
                'description' => 'Professional voice solution for businesses',
                'price' => 8000.00,
                'validity_days' => 30,
                'units' => 400, // minutes
                'features' => [
                    '400 minutes calling',
                    'Advanced call recording',
                    'Detailed analytics',
                    'Conference calling',
                    'Priority support'
                ],
                'is_popular' => true,
                'is_active' => true,
            ],

            // Contact Center Plans
            [
                'name' => 'Contact Center Lite',
                'type' => 'contact-center',
                'description' => 'Basic contact center features for small teams',
                'price' => 12000.00,
                'validity_days' => 30,
                'units' => 5, // agents
                'features' => [
                    'Up to 5 agents',
                    'Call routing',
                    'Basic reporting',
                    'Email integration',
                    'Standard support'
                ],
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Contact Center Pro',
                'type' => 'contact-center',
                'description' => 'Advanced contact center solution for growing teams',
                'price' => 25000.00,
                'validity_days' => 30,
                'units' => 15, // agents
                'features' => [
                    'Up to 15 agents',
                    'Advanced call routing',
                    'Real-time dashboard',
                    'CRM integration',
                    'Queue management',
                    'Priority support'
                ],
                'is_popular' => true,
                'is_active' => true,
            ],

            // Virtual Number Plans
            [
                'name' => 'Virtual Number Basic',
                'type' => 'virtual-number',
                'description' => 'Single virtual number for personal or small business use',
                'price' => 5000.00,
                'validity_days' => 30,
                'units' => 1, // numbers
                'features' => [
                    '1 virtual number',
                    'Call forwarding',
                    'SMS reception',
                    'Basic analytics',
                    'Standard support'
                ],
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Virtual Number Business',
                'type' => 'virtual-number',
                'description' => 'Multiple virtual numbers for business operations',
                'price' => 15000.00,
                'validity_days' => 30,
                'units' => 5, // numbers
                'features' => [
                    '5 virtual numbers',
                    'Advanced call forwarding',
                    'SMS and voice reception',
                    'Detailed analytics',
                    'Number porting',
                    'Priority support'
                ],
                'is_popular' => true,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            ServicePlan::create($plan);
        }
    }
}
