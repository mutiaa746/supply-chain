<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RiskScore;

class RiskScoreController extends Controller
{
    public function index()
    {
        return response()->json(
            RiskScore::with('country')->get()
        );
    }
}