<?php

namespace App\Support;

use App\Models\Country;
use Illuminate\Support\Facades\File;

class CountryIso3
{
    public static function getMap(): array
    {
        static $map = null;

        if ($map !== null) {
            return $map;
        }

        $json = json_decode(
            File::get(database_path('data/countries.json')),
            true
        );

        $map = [];

        foreach ($json as $country) {

            $iso2 = $country['cca2'] ?? null;
            $iso3 = $country['cca3'] ?? null;

            if (!$iso2 || !$iso3) {
                continue;
            }

            $dbCountry = Country::where('country_code', $iso2)->first();

            if ($dbCountry) {
                $map[$iso3] = $dbCountry->id;
            }
        }

        return $map;
    }
}