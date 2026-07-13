<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function index()
    {
        // Daftar kota dengan koordinat
        $cities = [
            ['name' => 'Jakarta', 'lat' => -6.2, 'lng' => 106.8, 'code' => 'ID'],
            ['name' => 'Singapore', 'lat' => 1.35, 'lng' => 103.8, 'code' => 'SG'],
            ['name' => 'Kuala Lumpur', 'lat' => 3.13, 'lng' => 101.68, 'code' => 'MY'],
            ['name' => 'Bangkok', 'lat' => 13.75, 'lng' => 100.5, 'code' => 'TH'],
            ['name' => 'Tokyo', 'lat' => 35.68, 'lng' => 139.76, 'code' => 'JP'],
            ['name' => 'London', 'lat' => 51.5, 'lng' => -0.12, 'code' => 'GB'],
            ['name' => 'New York', 'lat' => 40.71, 'lng' => -74.0, 'code' => 'US'],
            ['name' => 'Sydney', 'lat' => -33.86, 'lng' => 151.2, 'code' => 'AU'],
            ['name' => 'Paris', 'lat' => 48.85, 'lng' => 2.35, 'code' => 'FR'],
            ['name' => 'Berlin', 'lat' => 52.52, 'lng' => 13.40, 'code' => 'DE'],
            ['name' => 'Rome', 'lat' => 41.90, 'lng' => 12.50, 'code' => 'IT'],
            ['name' => 'Madrid', 'lat' => 40.41, 'lng' => -3.70, 'code' => 'ES'],
            ['name' => 'Moscow', 'lat' => 55.75, 'lng' => 37.61, 'code' => 'RU'],
            ['name' => 'Dubai', 'lat' => 25.20, 'lng' => 55.27, 'code' => 'AE'],
            ['name' => 'Mumbai', 'lat' => 19.07, 'lng' => 72.87, 'code' => 'IN'],
            ['name' => 'Shanghai', 'lat' => 31.23, 'lng' => 121.47, 'code' => 'CN'],
            ['name' => 'Seoul', 'lat' => 37.56, 'lng' => 126.97, 'code' => 'KR'],
            ['name' => 'Mexico City', 'lat' => 19.43, 'lng' => -99.13, 'code' => 'MX'],
            ['name' => 'Cairo', 'lat' => 30.04, 'lng' => 31.23, 'code' => 'EG'],
            ['name' => 'Cape Town', 'lat' => -33.92, 'lng' => 18.42, 'code' => 'ZA'],
        ];
        
        $weatherData = [];
        $weatherService = new WeatherService();
        
        foreach ($cities as $city) {
            try {
                $weather = $weatherService->getWeather($city['lat'], $city['lng']);
                if ($weather && isset($weather['current_weather'])) {
                    // Cari country di database
                    $country = Country::where('country_code', $city['code'])->first();
                    if (!$country) {
                        $country = new Country();
                        $country->country_name = $city['name'];
                        $country->country_code = $city['code'];
                    }
                    $weatherData[] = [
                        'country' => $country,
                        'weather' => $weather['current_weather'],
                        'description' => $weatherService->getWeatherDescription(
                            $weather['current_weather']['weathercode'] ?? 0
                        )
                    ];
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        
        return view('weather.index', compact('weatherData'));
    }
}