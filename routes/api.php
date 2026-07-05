<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RiskAnalysisController;
use App\Http\Controllers\Api\RiskScoreController;

Route::post('/risk-analysis', [RiskAnalysisController::class, 'analyze']);
Route::post('/risk-score/calculate', [RiskScoreController::class, 'calculate']);
Route::get('/risk-score', [RiskScoreController::class, 'index']);