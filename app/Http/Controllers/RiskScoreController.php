<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiskScore;

class RiskScoreController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $riskScores = RiskScore::with('country')

            ->when($search, function ($query) use ($search) {

                $query->whereHas('country', function ($q) use ($search) {

                    $q->where('country_name', 'like', "%{$search}%");

                });

            })

            ->orderByDesc('total_score')

            ->paginate(10);

        return view('risk.index', compact('riskScores', 'search'));
    }
}