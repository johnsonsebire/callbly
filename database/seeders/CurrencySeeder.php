<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'name' => 'Ghana Cedi',
                'code' => 'GHS',
                'symbol' => '₵',
                'exchange_rate' => 1.0000,
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'name' => 'United States Dollar',
                'code' => 'USD',
                'symbol' => '$',
                'exchange_rate' => 0.0833, // 1 USD = 12 GHS (example rate)
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'name' => 'British Pound',
                'code' => 'GBP',
                'symbol' => '£',
                'exchange_rate' => 0.0625, // 1 GBP = 16 GHS (example rate)
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Euro',
                'code' => 'EUR',
                'symbol' => '€',
                'exchange_rate' => 0.0714, // 1 EUR = 14 GHS (example rate)
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Nigerian Naira',
                'code' => 'NGN',
                'symbol' => '₦',
                'exchange_rate' => 15.3846, // 1 GHS = 15.38 NGN (example rate)
                'is_default' => false,
                'is_active' => true,
            ],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['code' => $currency['code']],
                $currency
            );
        }
    }
}
