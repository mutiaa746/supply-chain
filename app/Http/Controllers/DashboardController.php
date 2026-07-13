<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\RiskScore;
use App\Models\NewsCache;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCountries = Country::count();
        $totalPorts = \App\Models\Port::count();
        $highRiskCountries = RiskScore::where('risk_level', 'High')->count();
        $totalNews = NewsCache::count();

        $recentRisks = RiskScore::with('country')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalCountries',
            'totalPorts',
            'highRiskCountries',
            'totalNews',
            'recentRisks'
        ));
    }
}