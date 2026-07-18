<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function getWeather($latitude, $longitude)
    {
        try {
            $response = Http::timeout(10)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'current' => 'temperature_2m,relative_humidity_2m,rain,wind_speed_10m,weather_code',
                'timezone' => 'auto'
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getWeatherDescription($code)
    {
        $weather = [
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
        return $weather[$code] ?? 'Tidak Diketahui';
    }

    public static function getStormRisk($windSpeed, $rain)
    {
        if ($windSpeed >= 60 || $rain >= 25) return 'High';
        if ($windSpeed >= 35 || $rain >= 10) return 'Medium';
        return 'Low';
    }

    public static function getMarkerColor($temperature)
    {
        if ($temperature > 30) return '#e74a3b';
        if ($temperature >= 20) return '#f6c23e';
        if ($temperature >= 10) return '#1cc88a';
        if ($temperature >= 0) return '#4e73df';
        return '#858796';
    }
}