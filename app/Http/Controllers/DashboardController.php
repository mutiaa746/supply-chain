<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\WeatherData;
use App\Models\NewsCache;
use App\Models\RiskScore;
use App\Models\EconomicIndicator;
use App\Models\ExchangeRate;

class DashboardController extends Controller
{
    public function index()
    {
        // ==========================
        // Summary Card
        // ==========================

        $countries = Country::count();
        $weather   = WeatherData::count();
        $news      = NewsCache::count();
        $risk      = RiskScore::count();

        // ==========================
        // Risk Level
        // ==========================

        $lowRisk = RiskScore::where('risk_level', 'Low')->count();

        $mediumRisk = RiskScore::where('risk_level', 'Medium')->count();

        $highRisk = RiskScore::where('risk_level', 'High')->count();

        // ==========================
        // Latest Data
        // ==========================

        $recentRisk = RiskScore::with('country')
            ->latest()
            ->take(5)
            ->get();

        $recentNews = NewsCache::with('country')
            ->latest('published_at')
            ->take(5)
            ->get();

        $recentWeather = WeatherData::with('country')
            ->latest('recorded_at')
            ->take(5)
            ->get();

        // ==========================
        // Top Risk
        // ==========================

        $topRisk = RiskScore::with('country')
            ->orderByDesc('total_score')
            ->take(5)
            ->get();

        // ==========================
        // Sentiment
        // ==========================

        $positiveNews = NewsCache::where('sentiment', 'positive')->count();

        $neutralNews = NewsCache::where('sentiment', 'neutral')->count();

        $negativeNews = NewsCache::where('sentiment', 'negative')->count();

        // ==========================
        // Weather Chart
        // ==========================

        $lowStorm = WeatherData::where('storm_risk', 'Low')->count();

        $mediumStorm = WeatherData::where('storm_risk', 'Medium')->count();

        $highStorm = WeatherData::where('storm_risk', 'High')->count();

        // ==========================
        // Economic
        // ==========================

        $avgInflation = round(EconomicIndicator::avg('inflation'),2);

        $avgGDP = round(EconomicIndicator::avg('gdp'),2);

        // ==========================
        // Exchange
        // ==========================

        $avgExchange = round(
            ExchangeRate::avg('exchange_rate'),
            2
        );

        // ==========================
        // Last Update
        // ==========================

        $lastUpdate = collect([

            WeatherData::max('updated_at'),

            NewsCache::max('updated_at'),

            EconomicIndicator::max('updated_at'),

            ExchangeRate::max('updated_at'),

            RiskScore::max('updated_at'),

        ])->filter()->max();

        return view('dashboard', compact(

            'countries',
            'weather',
            'news',
            'risk',

            'lowRisk',
            'mediumRisk',
            'highRisk',

            'recentRisk',
            'recentNews',
            'recentWeather',

            'topRisk',

            'positiveNews',
            'neutralNews',
            'negativeNews',

            'lowStorm',
            'mediumStorm',
            'highStorm',

            'avgInflation',
            'avgGDP',
            'avgExchange',

            'lastUpdate'
        ));
    }
}