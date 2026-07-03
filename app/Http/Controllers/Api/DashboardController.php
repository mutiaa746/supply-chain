<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\WeatherData;
use App\Models\NewsCache;
use App\Models\RiskScore;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'countries' => Country::count(),
            'weather' => WeatherData::count(),
            'news' => NewsCache::count(),
            'risk_scores' => RiskScore::count(),
        ]);
    }
}