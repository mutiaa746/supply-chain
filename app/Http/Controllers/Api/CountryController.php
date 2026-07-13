<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\RiskScore;
use App\Models\NewsCache;
use App\Models\Port;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('country_name')->get();
        return view('countries.index', compact('countries'));
    }

    public function show($id)
    {
        $country = Country::with(['riskScores', 'news', 'ports'])->findOrFail($id);
        
        // Weather
        $weather = null;
        $weatherService = new WeatherService();
        if ($country->latitude && $country->longitude) {
            $weather = $weatherService->getWeather($country->latitude, $country->longitude);
            if ($weather && isset($weather['current_weather'])) {
                $weather['description'] = $weatherService->getWeatherDescription(
                    $weather['current_weather']['weathercode'] ?? 0
                );
            }
        }
        
        // Latest Risk
        $latestRisk = $country->riskScores()->latest()->first();
        
        // News
        $news = NewsCache::where('country_code', $country->country_code)
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();
        
        // Ports
        $ports = Port::where('country_id', $country->id)->get();
        
        return view('countries.show', compact(
            'country', 'weather', 'latestRisk', 'news', 'ports'
        ));
    }
}