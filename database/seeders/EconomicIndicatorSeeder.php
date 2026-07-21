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
            $indicators = [
                ['name' => 'GDP', 'code' => 'NY.GDP.MKTP.CD', 'value' => rand(500000, 3000000000)],
                ['name' => 'Inflation', 'code' => 'FP.CPI.TOTL.ZG', 'value' => rand(1, 10) + (rand(0, 99) / 100)],
                ['name' => 'Population', 'code' => 'SP.POP.TOTL', 'value' => rand(1000000, 200000000)],
                ['name' => 'Exports', 'code' => 'NE.EXP.GNFS.CD', 'value' => rand(100000, 500000000)],
                ['name' => 'Imports', 'code' => 'NE.IMP.GNFS.CD', 'value' => rand(100000, 500000000)],
            ];

            foreach ($indicators as $ind) {
                EconomicIndicator::updateOrCreate(
                    [
                        'country_id' => $country->id,
                        'indicator_name' => $ind['name'],
                    ],
                    [
                        'indicator_code' => $ind['code'],
                        'value' => $ind['value'],
                        'year' => 2023,
                    ]
                );
            }
        }
    }
}