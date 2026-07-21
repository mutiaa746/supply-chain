<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExchangeRate;

class ExchangeRateSeeder extends Seeder
{
    public function run(): void
    {
        $currencies = ['IDR', 'EUR', 'GBP', 'JPY', 'CNY', 'SGD', 'MYR', 'PHP', 'THB', 'VND'];
        foreach ($currencies as $currency) {
            ExchangeRate::updateOrCreate(
                [
                    'base_currency' => 'USD',
                    'target_currency' => $currency,
                ],
                [
                    'rate' => match($currency) {
                        'IDR' => 16000.0,
                        'EUR' => 0.92,
                        'GBP' => 0.78,
                        'JPY' => 155.0,
                        'CNY' => 7.25,
                        'SGD' => 1.34,
                        'MYR' => 4.70,
                        'PHP' => 58.0,
                        'THB' => 36.5,
                        'VND' => 25000.0,
                        default => 1.0
                    }
                ]
            );
        }
    }
}