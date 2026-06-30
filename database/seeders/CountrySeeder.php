<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $response = Http::get('https://restcountries.com/v3.1/all');

        if ($response->successful()) {

            foreach ($response->json() as $country) {

                $countryCode = $country['cca2'] ?? null;

                // Lewati data jika tidak memiliki kode negara
                if (!$countryCode) {
                    continue;
                }

                Country::updateOrCreate(
                    [
                        'country_code' => $countryCode,
                    ],
                    [
                        'country_name' => $country['name']['common'] ?? '',
                        'capital'      => $country['capital'][0] ?? '',
                        'region'       => $country['region'] ?? '',
                        'currency'     => isset($country['currencies'])
                            ? implode(',', array_keys($country['currencies']))
                            : '',
                        'language'     => isset($country['languages'])
                            ? implode(', ', array_values($country['languages']))
                            : '',
                        'flag'         => $country['flags']['png'] ?? '',
                    ]
                );
            }
        }
    }
}