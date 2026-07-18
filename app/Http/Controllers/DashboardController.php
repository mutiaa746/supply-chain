<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\RiskScore;
use App\Models\NewsCache;
use App\Models\Port;
use App\Models\ExchangeRate;
use App\Models\WeatherData;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ========== STATISTIK ==========
        $totalCountries = Country::count();
        $totalPorts = Port::count();
        $totalNews = NewsCache::count();

        // ========== RISK ==========
        $highRisk = RiskScore::whereIn('risk_level', ['High', 'Critical'])->count();
        $mediumRisk = RiskScore::where('risk_level', 'Medium')->count();
        $lowRisk = RiskScore::where('risk_level', 'Low')->count();

        // ========== DROPDOWN COUNTRIES ==========
        $countries = Country::orderBy('country_name')->get();
        $selectedCountry = null;
        $selectedRisk = null;
        $weather = null;
        $news = collect();
        $ports = collect();

        // Jika ada parameter country, ambil data negara tersebut
        if ($request->has('country') && $request->country) {
            $selectedCountry = Country::with('riskScores')->find($request->country);
        } else {
            // Default: ambil negara pertama (Indonesia atau lainnya)
            $selectedCountry = Country::first();
        }

        if ($selectedCountry) {
            // Risk terbaru
            $selectedRisk = $selectedCountry->riskScores()->latest()->first();

            // Weather
            if ($selectedCountry->latitude && $selectedCountry->longitude) {
                try {
                    $weatherService = new WeatherService();
                    $weatherRaw = $weatherService->getWeather($selectedCountry->latitude, $selectedCountry->longitude);
                    if ($weatherRaw && isset($weatherRaw['current_weather'])) {
                        $weather = $weatherRaw['current_weather'];
                        $weather['description'] = $weatherService->getWeatherDescription(
                            $weatherRaw['current_weather']['weathercode'] ?? 0
                        );
                    }
                } catch (\Exception $e) {
                    $weather = null;
                }
            }

            // News (5 terbaru)
            $news = NewsCache::where('country_code', $selectedCountry->country_code)
                ->orderBy('published_at', 'desc')
                ->limit(5)
                ->get();

            // Ports
            $ports = Port::where('country_id', $selectedCountry->id)->limit(5)->get();
        }

        // ========== EXCHANGE RATES UNTUK GRAFIK (DI ECONOMIC) ==========
        $exchangeRates = ExchangeRate::where('base_currency', 'USD')
            ->whereIn('target_currency', ['IDR', 'EUR', 'GBP', 'JPY', 'CNY', 'SGD', 'MYR', 'PHP', 'THB', 'VND'])
            ->get();

        if ($exchangeRates->isEmpty()) {
            $exchangeRates = collect([
                (object) ['target_currency' => 'IDR', 'rate' => 15500],
                (object) ['target_currency' => 'EUR', 'rate' => 0.92],
                (object) ['target_currency' => 'GBP', 'rate' => 0.78],
                (object) ['target_currency' => 'JPY', 'rate' => 148.50],
                (object) ['target_currency' => 'CNY', 'rate' => 7.25],
                (object) ['target_currency' => 'SGD', 'rate' => 1.35],
                (object) ['target_currency' => 'MYR', 'rate' => 4.70],
                (object) ['target_currency' => 'PHP', 'rate' => 56.50],
                (object) ['target_currency' => 'THB', 'rate' => 36.00],
                (object) ['target_currency' => 'VND', 'rate' => 25400],
            ]);
        }

        $chartData = [
            'labels' => $exchangeRates->pluck('target_currency')->toArray(),
            'rates' => $exchangeRates->pluck('rate')->toArray()
        ];

        $riskDistribution = [
            'High' => $highRisk,
            'Medium' => $mediumRisk,
            'Low' => $lowRisk
        ];

        return view('dashboard.index', compact(
            'totalCountries',
            'totalPorts',
            'totalNews',
            'highRisk',
            'mediumRisk',
            'lowRisk',
            'countries',
            'selectedCountry',
            'selectedRisk',
            'weather',
            'news',
            'ports',
            'chartData',
            'riskDistribution'
        ));
    }
}