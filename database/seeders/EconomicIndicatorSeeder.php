<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\EconomicIndicator;

class EconomicIndicatorSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Country::all() as $country) {

            EconomicIndicator::updateOrCreate(
                [
                    'country_id' => $country->id,
                ],
                [
                    'gdp' => rand(50000, 3000000),
                    'inflation_rate' => rand(1, 10),
                    'interest_rate' => rand(1, 8),
                    'unemployment_rate' => rand(2, 12),
                ]
            );

        }
    }
}