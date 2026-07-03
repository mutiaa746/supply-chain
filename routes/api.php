<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\RiskScoreController;
use App\Http\Controllers\Api\DashboardController;

Route::get('/countries', [CountryController::class, 'index']);
Route::get('/countries/{id}', [CountryController::class, 'show']);

Route::get('/weather', [WeatherController::class, 'index']);

Route::get('/news', [NewsController::class, 'index']);

Route::get('/risk-scores', [RiskScoreController::class, 'index']);

Route::get('/dashboard', [DashboardController::class, 'index']);