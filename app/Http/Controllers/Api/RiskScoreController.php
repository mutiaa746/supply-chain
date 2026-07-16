<?php

namespace App\Http\Controllers;

use App\Models\RiskScore;
use App\Models\Country;
use Illuminate\Http\Request;

class RiskScoreController extends Controller
{
    public function index(Request $request)
    {
        $query = RiskScore::with('country');

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('country', function($q) use ($search) {
                $q->where('country_name', 'LIKE', "%{$search}%");
            });
        }

        $riskScores = $query->orderBy('total_score', 'desc')->get();

        $highRisk = RiskScore::whereIn('risk_level', ['High', 'Critical'])->count();
        $mediumRisk = RiskScore::where('risk_level', 'Medium')->count();
        $lowRisk = RiskScore::where('risk_level', 'Low')->count();

        return view('risk.index', compact('riskScores', 'highRisk', 'mediumRisk', 'lowRisk'));
    }
}