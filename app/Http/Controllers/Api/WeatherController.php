<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\WeatherData;
use App\Models\Country;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function index()
    {
        // Ambil dari database
        $weatherData = WeatherData::with('country')
            ->orderBy('country_id')
            ->get()
            ->map(function($item) {
                return [
                    'country' => $item->country,
                    'weather' => [
                        'temperature' => $item->temperature,
                        'windspeed' => $item->windspeed,
                        'weathercode' => $item->weathercode
                    ],
                    'description' => $item->description ?? 'Tidak Diketahui'
                ];
            });

        return view('weather.index', compact('weatherData'));
    }

    public function fetchFromAPI()
    {
        // Ambil SEMUA negara yang punya koordinat
        $countries = Country::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $weatherService = new WeatherService();
        $count = 0;
        $total = $countries->count();

        // Hapus data lama
        WeatherData::truncate();

        foreach ($countries as $country) {
            try {
                $weather = $weatherService->getWeather($country->latitude, $country->longitude);
                if ($weather && isset($weather['current_weather'])) {
                    WeatherData::create([
                        'country_id' => $country->id,
                        'temperature' => $weather['current_weather']['temperature'] ?? 0,
                        'windspeed' => $weather['current_weather']['windspeed'] ?? 0,
                        'weathercode' => $weather['current_weather']['weathercode'] ?? 0,
                        'description' => $weatherService->getWeatherDescription(
                            $weather['current_weather']['weathercode'] ?? 0
                        )
                    ]);
                    $count++;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // Kembali ke halaman weather dengan pesan sukses
        return redirect('/weather')->with('success', "✅ Data cuaca berhasil diupdate dari API! ($count dari $total negara)");
    }

    public function refresh()
    {
        return $this->fetchFromAPI();
    }
}