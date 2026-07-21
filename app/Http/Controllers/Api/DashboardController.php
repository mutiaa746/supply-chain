<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Country;
use App\Models\RiskScore;
use App\Models\NewsCache;
use App\Models\Port;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik
        $totalCountries = Country::count();
        $totalPorts = Port::count();
        $totalNews = NewsCache::count();

        // Risk
        $highRisk = RiskScore::whereIn('risk_level', ['High', 'Critical'])->count();
        $mediumRisk = RiskScore::where('risk_level', 'Medium')->count();
        $lowRisk = RiskScore::where('risk_level', 'Low')->count();

        if ($highRisk == 0 && $mediumRisk == 0 && $lowRisk == 0) {
            $this->generateRiskDummy();
            $highRisk = RiskScore::whereIn('risk_level', ['High', 'Critical'])->count();
            $mediumRisk = RiskScore::where('risk_level', 'Medium')->count();
            $lowRisk = RiskScore::where('risk_level', 'Low')->count();
        }

        // Recent Risk
        $recentRisks = RiskScore::with('country')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Exchange Rates untuk grafik
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
            'recentRisks',
            'chartData',
            'riskDistribution'
        ));
    }

    private function generateRiskDummy()
    {
        $countries = Country::limit(100)->get();
        $levels = ['Low', 'Medium', 'High', 'Critical'];

        foreach ($countries as $country) {
            RiskScore::updateOrCreate(
                ['country_id' => $country->id],
                [
                    'weather_score' => rand(10, 80),
                    'inflation_score' => rand(10, 80),
                    'currency_score' => rand(10, 80),
                    'news_score' => rand(10, 80),
                    'total_score' => rand(20, 90),
                    'risk_level' => $levels[array_rand($levels)],
                    'calculated_at' => now()
                ]
            );
        }
    }
}