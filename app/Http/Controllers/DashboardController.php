<?php

namespace App\Http\Controllers;

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
        // STATISTIK
        $totalCountries = Country::count();
        $totalPorts = Port::count();
        $totalNews = NewsCache::count();

        // RISK
        $highRisk = RiskScore::whereIn('risk_level', ['High', 'Critical'])->count();
        $mediumRisk = RiskScore::where('risk_level', 'Medium')->count();
        $lowRisk = RiskScore::where('risk_level', 'Low')->count();

        // RECENT RISK (10 data)
        $recentRisks = RiskScore::with('country')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // EXCHANGE RATES (10 mata uang utama)
        $exchangeRates = ExchangeRate::where('base_currency', 'USD')
            ->whereIn('target_currency', ['IDR', 'EUR', 'GBP', 'JPY', 'CNY', 'SGD', 'MYR', 'PHP', 'THB', 'VND'])
            ->get();

        // Jika kosong, pakai default
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
}