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

            Country::updateOrCreate(
                [
                    'country_code' => $country['cca2'] ?? ''
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
                ]
            );
        }

        $this->command->info('Semua negara berhasil diimport.');
    }
}