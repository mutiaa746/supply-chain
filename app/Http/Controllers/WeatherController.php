<?php

namespace App\Http\Controllers;

use App\Models\WeatherData;
use App\Models\Country;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function index()
    {
        $weatherData = WeatherData::with('country')->get()->map(function($item) {
            return [
                'country' => $item->country,
                'weather' => [
                    'temperature' => $item->temperature,
                    'windspeed' => $item->wind_speed ?? 0,
                    'weathercode' => $item->weathercode ?? 0,
                    'rain' => $item->rain ?? 0,
                    'humidity' => $item->humidity ?? 0,
                ],
                'description' => $item->description ?? 'Tidak Diketahui',
                'risk' => $this->getWeatherRisk($item->weathercode ?? 0, $item->wind_speed ?? 0, $item->rain ?? 0)
            ];
        });

        return view('weather.index', compact('weatherData'));
    }

    private function getWeatherRisk($code, $wind, $rain)
    {
        // Cuaca ekstrem
        if ($code == 95 || $code == 96 || $code == 99) return 'Badai Petir';
        if ($code == 65 || $code == 82) return 'Hujan Lebat';
        if ($code == 63 || $code == 81) return 'Hujan Sedang';
        if ($code == 61 || $code == 80) return 'Hujan Ringan';
        if ($wind > 60) return 'Angin Kencang';
        if ($rain > 25) return 'Hujan Lebat';
        if ($wind > 40) return 'Angin Kencang';
        
        return 'Normal';
    }

    public function refresh()
    {
        $countries = Country::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $service = new WeatherService();
        $success = 0;
        $total = $countries->count();

        WeatherData::truncate();

        foreach ($countries as $country) {
            try {
                $result = $service->getWeather($country->latitude, $country->longitude);

                if ($result && isset($result['current_weather'])) {
                    $current = $result['current_weather'];
                    WeatherData::create([
                        'country_id' => $country->id,
                        'temperature' => $current['temperature'] ?? 0,
                        'wind_speed' => $current['windspeed'] ?? 0,
                        'weathercode' => $current['weathercode'] ?? 0,
                        'rain' => $current['rain'] ?? 0,
                        'humidity' => $current['humidity'] ?? 0,
                        'description' => $service->getWeatherDescription($current['weathercode'] ?? 0)
                    ]);
                    $success++;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return redirect('/weather')->with('success', "✅ Data cuaca diupdate dari API! ($success dari $total negara)");
    }
}