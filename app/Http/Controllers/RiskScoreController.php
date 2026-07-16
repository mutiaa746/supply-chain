<?php

namespace App\Http\Controllers;

use App\Models\RiskScore;
use App\Models\Country;
use Illuminate\Http\Request;

class RiskScoreController extends Controller
{
    public function index(Request $request)
    {
        // Query dengan search
        $query = RiskScore::with('country');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('country', function($q) use ($search) {
                $q->where('country_name', 'LIKE', "%{$search}%");
            });
        }

        $riskScores = $query->orderBy('total_score', 'desc')->get();

        // Statistik
        $highRisk = RiskScore::whereIn('risk_level', ['High', 'Critical'])->count();
        $mediumRisk = RiskScore::where('risk_level', 'Medium')->count();
        $lowRisk = RiskScore::where('risk_level', 'Low')->count();

        // Jika tidak ada data, generate dari countries
        if ($riskScores->isEmpty()) {
            $this->generateRiskScores();
            $riskScores = RiskScore::with('country')->orderBy('total_score', 'desc')->get();
            $highRisk = RiskScore::whereIn('risk_level', ['High', 'Critical'])->count();
            $mediumRisk = RiskScore::where('risk_level', 'Medium')->count();
            $lowRisk = RiskScore::where('risk_level', 'Low')->count();
        }

        return view('risk.index', compact('riskScores', 'highRisk', 'mediumRisk', 'lowRisk'));
    }

    private function generateRiskScores()
    {
        $countries = Country::all();
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