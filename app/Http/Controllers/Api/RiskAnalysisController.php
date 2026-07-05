<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SentimentAnalysisService;
use App\Services\RiskAnalysisService;

class RiskAnalysisController extends Controller
{
    protected $sentimentService;
    protected $riskService;

    public function __construct(
        SentimentAnalysisService $sentimentService,
        RiskAnalysisService $riskService
    ) {
        $this->sentimentService = $sentimentService;
        $this->riskService = $riskService;
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'text' => 'required|string'
        ]);

        $sentiment = $this->sentimentService->analyze($request->text);

        $result = $this->riskService->analyze($sentiment);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}