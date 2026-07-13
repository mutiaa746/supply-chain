<?php

namespace App\Http\Controllers;

use App\Models\RiskScore;
use App\Services\RiskScoreService;
use App\Services\WeatherService;
use App\Services\CurrencyService;
use App\Services\NewsService;
use Illuminate\Http\Request;

class RiskScoreController extends Controller
{
    public function index()
    {
        $riskScores = RiskScore::with('country')
            ->orderBy('total_score', 'desc')
            ->get();
        
        $highRisk = RiskScore::where('risk_level', 'High')->orWhere('risk_level', 'Critical')->count();
        $mediumRisk = RiskScore::where('risk_level', 'Medium')->count();
        $lowRisk = RiskScore::where('risk_level', 'Low')->count();
        
        return view('risk.index', compact('riskScores', 'highRisk', 'mediumRisk', 'lowRisk'));
    }
}