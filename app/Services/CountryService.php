<?php

namespace App\Services;

use App\Models\Country;
use Illuminate\Support\Facades\Http;

class CountryService
{
    public function sync(): bool
    {
        $response = Http::get(
            'https://countriesnow.space/api/v0.1/countries/info?returns=currency,flag,unicodeFlag,dialCode'
        );

        if (!$response->successful()) {
            return false;
        }

        $json = $response->json();

        if (!isset($json['data'])) {
            return false;
        }

        foreach ($json['data'] as $country) {

            Country::updateOrCreate(
                [
                    'country_code' => $country['iso2'] ?? '',
                ],
                [
                    'country_name' => $country['name'] ?? '',
                    'capital'      => $country['capital'] ?? null,
                    'region'       => $country['region'] ?? null,
                    'currency'     => $country['currency'] ?? null,
                    'language'     => null,
                    'flag'         => $country['flag'] ?? null,
                ]
            );
        }

        return true;
    }
}