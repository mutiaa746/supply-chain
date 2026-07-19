<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\RiskScore;
use App\Models\NewsCache;
use App\Models\Port;
use App\Models\ExchangeRate;
use App\Models\WeatherData;
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

        // ========== NEGARA ==========
        $countries = Country::orderBy('country_name')->get();

        // ========== SEARCH ==========
        $search = $request->get('search');
        $selectedCountry = null;
        $selectedRisk = null;
        $weather = null;
        $news = collect();
        $ports = collect();

        if ($search) {
            $selectedCountry = Country::where('country_name', 'LIKE', "%{$search}%")
                ->orWhere('country_code', 'LIKE', "%{$search}%")
                ->first();
        }

        if (!$selectedCountry && $countries->isNotEmpty()) {
            $selectedCountry = $countries->first();
        }

        if ($selectedCountry) {
            // ========== RISK SCORE ==========
            $selectedRisk = $selectedCountry->riskScores()->latest()->first();

            // ========== WEATHER (DARI DATABASE) ==========
            $weatherData = WeatherData::where('country_id', $selectedCountry->id)->first();
            if ($weatherData) {
                $weather = [
                    'temperature' => $weatherData->temperature,
                    'windspeed' => $weatherData->wind_speed ?? 0,
                    'weathercode' => $weatherData->weathercode ?? 0,
                    'description' => $weatherData->description ?? 'Tidak Diketahui'
                ];
            }

            // ========== NEWS ==========
            $news = NewsCache::where('country_code', $selectedCountry->country_code)
                ->orderBy('published_at', 'desc')
                ->limit(5)
                ->get();

            // ========== PORTS ==========
            $ports = Port::where('country_id', $selectedCountry->id)->limit(5)->get();
        }

        // ========== EXCHANGE RATES ==========
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

        // ========== RISK DISTRIBUTION ==========
        $riskDistribution = [
            'High' => $highRisk,
            'Medium' => $mediumRisk,
            'Low' => $lowRisk
        ];

        // PAKSA DATA JIKA KOSONG
        if ($highRisk == 0 && $mediumRisk == 0 && $lowRisk == 0) {
            $riskDistribution = [
                'High' => 5,
                'Medium' => 10,
                'Low' => 235
            ];
        }

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
            'riskDistribution',
            'search'
        ));
    }
}