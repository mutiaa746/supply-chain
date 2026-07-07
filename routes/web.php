<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\EconomicIndicatorController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\RiskScoreController;
use App\Http\Controllers\PortMapController;
use App\Http\Controllers\RefreshController;

Route::get('/', [DashboardController::class,'index']);

Route::get('/countries',[CountryController::class,'index']);

Route::get('/weather',[WeatherController::class,'index']);

Route::get('/economic',[EconomicIndicatorController::class,'index']);

Route::get('/exchange',[ExchangeRateController::class,'index']);

Route::get('/news',[NewsController::class,'index']);

Route::get('/ports',[PortController::class,'index']);

Route::get('/ports/map',[PortMapController::class,'index'])->name('ports.map');

Route::get('/risk',[RiskScoreController::class,'index']);

Route::get('/refresh-data', [RefreshController::class, 'index'])
    ->name('refresh.data');
    