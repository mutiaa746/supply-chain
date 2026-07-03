<?php

namespace App\Services;

use App\Models\Country;
use App\Models\WeatherData;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function sync(): bool
    {
        $countries = Country::whereNotNull('capital')->get();

        foreach ($countries as $country) {

            try {

                echo "Memproses : {$country->country_name}\n";

                // ==========================
                // Ambil koordinat ibu kota
                // ==========================
                $geo = Http::timeout(30)
                    ->retry(3, 1000)
                    ->get(
                        'https://geocoding-api.open-meteo.com/v1/search',
                        [
                            'name' => $country->capital,
                            'count' => 1,
                            'language' => 'en',
                            'format' => 'json'
                        ]
                    );

                if (!$geo->successful()) {
                    echo "❌ Geocoding gagal\n";
                    continue;
                }

                $geoData = $geo->json();

                if (!isset($geoData['results'][0])) {
                    echo "❌ Koordinat tidak ditemukan\n";
                    continue;
                }

                $latitude = $geoData['results'][0]['latitude'];
                $longitude = $geoData['results'][0]['longitude'];

                // ==========================
                // Ambil data cuaca
                // ==========================
                $weather = Http::timeout(30)
                    ->retry(3, 1000)
                    ->get(
                        'https://api.open-meteo.com/v1/forecast',
                        [
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'current' => 'temperature_2m,precipitation,wind_speed_10m,weather_code'
                        ]
                    );

                if (!$weather->successful()) {
                    echo "❌ Weather API gagal\n";
                    continue;
                }

                $current = $weather->json('current');

                WeatherData::updateOrCreate(
                    [
                        'country_id' => $country->id,
                    ],
                    [
                        'temperature' => $current['temperature_2m'] ?? null,
                        'rainfall' => $current['precipitation'] ?? null,
                        'wind_speed' => $current['wind_speed_10m'] ?? null,
                        'storm_risk' => $this->stormRisk($current['weather_code'] ?? 0),
                        'recorded_at' => $current['time'] ?? now(),
                    ]
                );

                echo "✅ Berhasil\n\n";

            } catch (\Throwable $e) {

                echo "❌ ERROR : {$country->country_name}\n";
                echo $e->getMessage()."\n\n";

                continue;
            }
        }

        return true;
    }

    private function stormRisk($code): string
    {
        if (in_array($code, [95, 96, 99])) {
            return 'High';
        }

        if (in_array($code, [61, 63, 65, 80, 81, 82])) {
            return 'Medium';
        }

        return 'Low';
    }
}