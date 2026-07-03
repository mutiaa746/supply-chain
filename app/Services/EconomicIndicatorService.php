<?php

namespace App\Services;

use App\Models\Country;
use App\Models\EconomicIndicator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class EconomicIndicatorService
{
    protected array $indicators = [
        'gdp'        => 'NY.GDP.MKTP.CD',
        'inflation' => 'FP.CPI.TOTL.ZG',
        'population'=> 'SP.POP.TOTL',
        'exports'   => 'NE.EXP.GNFS.CD',
        'imports'   => 'NE.IMP.GNFS.CD',
    ];

    protected array $isoMap = [];

    public function __construct()
    {
        $this->loadIsoMap();
    }

    private function loadIsoMap(): void
    {
        $path = database_path('data/countries.json');

        if (!File::exists($path)) {
            return;
        }

        $countries = json_decode(File::get($path), true);

        foreach ($countries as $country) {

            if (
                isset($country['cca2']) &&
                isset($country['cca3'])
            ) {

                $this->isoMap[
                    strtoupper($country['cca2'])
                ] = strtoupper($country['cca3']);
            }
        }
    }

    public function sync(): bool
    {
        $countries = Country::all();

        $economicData = [];

        foreach ($this->indicators as $field => $indicator) {

            echo "Mengambil {$field}...\n";

            $page = 1;

            do {

                $response = Http::timeout(120)
                    ->retry(3, 2000)
                    ->get(
                        "https://api.worldbank.org/v2/country/all/indicator/{$indicator}",
                        [
                            'format'   => 'json',
                            'per_page' => 1000,
                            'page'     => $page,
                        ]
                    );

                if (!$response->successful()) {
                    break;
                }

                $json = $response->json();

                if (!isset($json[1])) {
                    break;
                }

                foreach ($json[1] as $row) {

                    if (empty($row['value'])) {
                        continue;
                    }

                    $iso3 = strtoupper($row['countryiso3code'] ?? '');

                    if (!$iso3) {
                        continue;
                    }

                    if (!isset($economicData[$iso3])) {
                        $economicData[$iso3] = [];
                    }

                    if (!isset($economicData[$iso3][$field])) {
                        $economicData[$iso3][$field] = $row['value'];
                    }
                }

                $pages = $json[0]['pages'] ?? 1;

                $page++;

            } while ($page <= $pages);
        }

        foreach ($countries as $country) {

            $iso2 = strtoupper($country->country_code);

            if (!isset($this->isoMap[$iso2])) {
                continue;
            }

            $iso3 = $this->isoMap[$iso2];

            if (!isset($economicData[$iso3])) {
                continue;
            }

            EconomicIndicator::updateOrCreate(
                [
                    'country_id' => $country->id
                ],
                [
                    'gdp'        => $economicData[$iso3]['gdp'] ?? null,
                    'inflation'  => $economicData[$iso3]['inflation'] ?? null,
                    'population' => $economicData[$iso3]['population'] ?? null,
                    'exports'    => $economicData[$iso3]['exports'] ?? null,
                    'imports'    => $economicData[$iso3]['imports'] ?? null,
                ]
            );
        }

        return true;
    }
}