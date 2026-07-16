<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function index()
    {
        $countries = Country::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->limit(50)
            ->get();

        $weatherData = [];
        $weatherService = new WeatherService();

        foreach ($countries as $country) {
            try {
                $weather = $weatherService->getWeather($country->latitude, $country->longitude);
                if ($weather && isset($weather['current_weather'])) {
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