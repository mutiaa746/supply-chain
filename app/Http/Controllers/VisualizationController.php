<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\ExchangeRate;
use App\Models\RiskScore;
use Illuminate\Http\Request;

class VisualizationController extends Controller
{
    public function index()
    {
        // ========== 1. GDP DATA ==========
        $gdpData = Country::whereNotNull('gdp')
            ->orderBy('gdp', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'country' => $item->country_name,
                    'gdp' => $item->gdp / 1000000000000 // Convert ke Trillion
                ];
            });

        // ========== 2. INFLATION DATA ==========
        $inflationData = Country::whereNotNull('inflation')
            ->orderBy('inflation', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'country' => $item->country_name,
                    'inflation' => $item->inflation
                ];
            });

        // ========== 3. CURRENCY DATA ==========
        $currencyData = ExchangeRate::where('base_currency', 'USD')
            ->whereIn('target_currency', ['IDR', 'EUR', 'GBP', 'JPY', 'CNY', 'SGD', 'MYR', 'PHP', 'THB', 'VND'])
            ->get()
            ->map(function($item) {
                return [
                    'currency' => $item->target_currency,
                    'rate' => $item->rate
                ];
            });

        // ========== 4. RISK DATA ==========
        $riskData = RiskScore::with('country')
            ->orderBy('total_score', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'country' => $item->country->country_name ?? 'Unknown',
                    'risk' => $item->total_score
                ];
            });

        // ========== 5. RISK DISTRIBUTION ==========
        $riskDistribution = [
            'High' => RiskScore::whereIn('risk_level', ['High', 'Critical'])->count(),
            'Medium' => RiskScore::where('risk_level', 'Medium')->count(),
            'Low' => RiskScore::where('risk_level', 'Low')->count()
        ];

        return view('visualization.index', compact(
            'gdpData',
            'inflationData',
            'currencyData',
            'riskData',
            'riskDistribution'
        ));
    }
}