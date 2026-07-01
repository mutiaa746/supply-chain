<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\ExchangeRate;

class ExchangeRateSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Country::all() as $country) {

            ExchangeRate::updateOrCreate(
                [
                    'country_id' => $country->id,
                ],
                [
                    'currency'      => 'USD',
                    'exchange_rate' => rand(13000, 17000),
                    'recorded_at'   => now(),
                ]
            );

        }
    }
}