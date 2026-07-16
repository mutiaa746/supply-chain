<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function index()
    {
        // Ambil SEMUA negara yang punya koordinat
        $countries = Country::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();
        
        $weatherData = [];
        $weatherService = new WeatherService();
        
        // Proses semua negara satu per satu (tapi dibatasi waktu)
        $processed = 0;
        $maxCountries = 250; // Ambil semua
        
        foreach ($countries as $country) {
            if ($processed >= $maxCountries) {
                break;
            }
            
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
                    $processed++;
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        
        // Kirim data ke view
        return view('weather.index', compact('weatherData'));
    }
}