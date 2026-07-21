<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/countries.json');

        if (!File::exists($path)) {
            $this->command->error('countries.json tidak ditemukan!');
            return;
        }

        $countries = json_decode(File::get($path), true);

        foreach ($countries as $country) {
            $code = $country['cca2'] ?? '';
            $gdp = null;
            $inflation = null;
            $population = null;

            // Seed realistic values for the 20 main countries used in the app
            if ($code == 'US') { $gdp = 26950000000000; $inflation = 3.4; $population = 333000000; }
            elseif ($code == 'CN') { $gdp = 17790000000000; $inflation = 0.2; $population = 1412000000; }
            elseif ($code == 'DE') { $gdp = 4430000000000; $inflation = 2.2; $population = 84000000; }
            elseif ($code == 'JP') { $gdp = 4210000000000; $inflation = 2.8; $population = 125000000; }
            elseif ($code == 'IN') { $gdp = 3730000000000; $inflation = 5.1; $population = 1428000000; }
            elseif ($code == 'GB') { $gdp = 3160000000000; $inflation = 3.0; $population = 67000000; }
            elseif ($code == 'FR') { $gdp = 2920000000000; $inflation = 2.6; $population = 68000000; }
            elseif ($code == 'IT') { $gdp = 2250000000000; $inflation = 2.0; $population = 59000000; }
            elseif ($code == 'BR') { $gdp = 2170000000000; $inflation = 4.6; $population = 215000000; }
            elseif ($code == 'CA') { $gdp = 2140000000000; $inflation = 3.1; $population = 38000000; }
            elseif ($code == 'RU') { $gdp = 2000000000000; $inflation = 7.4; $population = 146000000; }
            elseif ($code == 'MX') { $gdp = 1790000000000; $inflation = 4.3; $population = 128000000; }
            elseif ($code == 'AU') { $gdp = 1710000000000; $inflation = 3.6; $population = 26000000; }
            elseif ($code == 'KR') { $gdp = 1710000000000; $inflation = 2.6; $population = 51000000; }
            elseif ($code == 'ES') { $gdp = 1580000000000; $inflation = 3.4; $population = 47000000; }
            elseif ($code == 'ID') { $gdp = 1370000000000; $inflation = 2.6; $population = 277000000; }
            elseif ($code == 'TR') { $gdp = 1020000000000; $inflation = 64.8; $population = 85000000; }
            elseif ($code == 'SA') { $gdp = 1060000000000; $inflation = 1.6; $population = 36000000; }
            elseif ($code == 'NL') { $gdp = 1090000000000; $inflation = 3.8; $population = 17000000; }
            elseif ($code == 'CH') { $gdp = 885000000000; $inflation = 1.3; $population = 8800000; }
            else {
                // minor countries
                $gdp = rand(10000000, 1000000000);
                $inflation = rand(1, 8) + (rand(0, 9) / 10);
                $population = rand(500000, 15000000);
            }

            Country::updateOrCreate(
                [
                    'country_code' => $code
                ],
                [
                    'country_name' => $country['name']['common'] ?? '',
                    'capital' => $country['capital'][0] ?? null,
                    'region' => $country['region'] ?? null,

                    'currency' => isset($country['currencies'])
                        ? implode(', ', array_keys($country['currencies']))
                        : null,

                    'language' => isset($country['languages'])
                        ? implode(', ', $country['languages'])
                        : null,

                    'flag' => $country['flags']['png'] ?? null,
                    'gdp' => $gdp,
                    'inflation' => $inflation,
                    'population' => $population,
                    'latitude' => $country['latlng'][0] ?? null,
                    'longitude' => $country['latlng'][1] ?? null,
                ]
            );
        }

        $this->command->info('Semua negara berhasil diimport.');
    }
}