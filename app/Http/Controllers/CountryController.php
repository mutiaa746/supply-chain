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
        $country = Country::findOrFail($id);
        
        // Risk
        $latestRisk = RiskScore::where('country_id', $id)->latest()->first();
        
        // News
        $news = NewsCache::where('country_code', $country->country_code)
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();
        
        // Ports
        $ports = Port::where('country_id', $id)->get();
        
        // Weather
        $weather = null;
        if ($country->latitude && $country->longitude) {
            try {
                $weatherService = new WeatherService();
                $weather = $weatherService->getWeather($country->latitude, $country->longitude);
            } catch (\Exception $e) {
                $weather = null;
            }
        }
        
        return view('countries.show', compact('country', 'latestRisk', 'news', 'ports', 'weather'));
    }
}