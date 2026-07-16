<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    public function getWeather($latitude, $longitude)
    {
        try {
            // Timeout 3 detik agar cepat
            $response = Http::timeout(3)->get("https://api.open-meteo.com/v1/forecast", [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'current_weather' => true,
                'timezone' => 'auto'
            ]);

            if ($response->successful()) {
                return $response->json();
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Weather API Error: ' . $e->getMessage());
            return null;
        }
    }

    public static function getWeatherDescription($code)
    {
        $weatherCodes = [
            0 => 'Cerah',
            1 => 'Cerah Berawan',
            2 => 'Berawan',
            3 => 'Mendung',
            45 => 'Kabut',
            48 => 'Kabut Es',
            51 => 'Gerimis Ringan',
            53 => 'Gerimis Sedang',
            55 => 'Gerimis Lebat',
            61 => 'Hujan Ringan',
            63 => 'Hujan Sedang',
            65 => 'Hujan Lebat',
            71 => 'Salju Ringan',
            73 => 'Salju Sedang',
            75 => 'Salju Lebat',
            80 => 'Hujan Ringan',
            81 => 'Hujan Sedang',
            82 => 'Hujan Lebat',
            95 => 'Badai Petir',
            96 => 'Badai Petir + Hujan Es',
            99 => 'Badai Petir + Hujan Es Lebat'
        ];
        return $weatherCodes[$code] ?? 'Tidak Diketahui';
    }
}