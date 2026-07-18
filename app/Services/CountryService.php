<?php

namespace App\Services;

use App\Models\Country;
use Illuminate\Support\Facades\Http;

class CountryService
{
    /**
     * Ambil data populasi, GDP, dan inflasi dari REST Countries API
     */
    public function updateCountryData()
    {
        try {
            $response = Http::timeout(30)->get('https://restcountries.com/v3.1/all');
            
            if (!$response->successful()) {
                return ['success' => false, 'message' => 'Gagal mengambil data dari API'];
            }

            $countries = $response->json();
            $updated = 0;

            foreach ($countries as $data) {
                $code = $data['cca2'] ?? null;
                if (!$code) continue;

                $country = Country::where('country_code', $code)->first();
                if (!$country) continue;

                // Update populasi
                if (isset($data['population'])) {
                    $country->population = $data['population'];
                }

                // Update GDP (dari data lain, tidak semua country punya)
                if (isset($data['gdp'])) {
                    $country->gdp = $data['gdp'];
                }

                $country->save();
                $updated++;
            }

            return [
                'success' => true,
                'message' => "Berhasil update $updated negara",
                'total' => $updated
            ];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Ambil GDP dan Inflasi dari World Bank API (untuk negara tertentu)
     */
    public function fetchWorldBankData($countryCode)
    {
        try {
            $data = ['gdp' => null, 'inflation' => null];

            // GDP
            $gdpResponse = Http::timeout(15)->get("http://api.worldbank.org/v2/country/{$countryCode}/indicator/NY.GDP.MKTP.CD?format=json");
            if ($gdpResponse->successful()) {
                $gdpData = $gdpResponse->json();
                if (isset($gdpData[1]) && is_array($gdpData[1])) {
                    foreach ($gdpData[1] as $item) {
                        if (isset($item['value']) && $item['value'] !== null) {
                            $data['gdp'] = $item['value'];
                            break;
                        }
                    }
                }
            }

            // Inflasi
            $inflationResponse = Http::timeout(15)->get("http://api.worldbank.org/v2/country/{$countryCode}/indicator/FP.CPI.TOTL.ZG?format=json");
            if ($inflationResponse->successful()) {
                $inflationData = $inflationResponse->json();
                if (isset($inflationData[1]) && is_array($inflationData[1])) {
                    foreach ($inflationData[1] as $item) {
                        if (isset($item['value']) && $item['value'] !== null) {
                            $data['inflation'] = $item['value'];
                            break;
                        }
                    }
                }
            }

            // Update database
            $country = Country::where('country_code', $countryCode)->first();
            if ($country) {
                if ($data['gdp']) $country->gdp = $data['gdp'];
                if ($data['inflation']) $country->inflation = $data['inflation'];
                $country->save();
            }

            return $data;

        } catch (\Exception $e) {
            return ['gdp' => null, 'inflation' => null];
        }
    }

    /**
     * Update semua negara dengan data dari World Bank (untuk 20 negara utama)
     */
    public function updateWorldBankData()
    {
        $mainCountries = ['ID', 'US', 'GB', 'DE', 'CN', 'JP', 'IN', 'BR', 'AU', 'CA', 'FR', 'IT', 'KR', 'MX', 'RU', 'SA', 'ZA', 'TR', 'AR', 'NG'];
        $results = [];

        foreach ($mainCountries as $code) {
            $data = $this->fetchWorldBankData($code);
            $country = Country::where('country_code', $code)->first();
            $results[] = [
                'country' => $country ? $country->country_name : $code,
                'gdp' => $data['gdp'],
                'inflation' => $data['inflation']
            ];
        }

        return $results;
    }

    /**
     * Update semua data (populasi dari REST Countries)
     */
    public function updateAllCountries()
    {
        return $this->updateCountryData();
    }
}