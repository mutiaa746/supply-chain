<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use App\Services\ExchangeRateService;
use App\Services\NewsService;
use App\Services\RiskService;

class RefreshController extends Controller
{
    public function index(
        WeatherService $weather,
        ExchangeRateService $exchange,
        NewsService $news,
        RiskService $risk
    )
    {
        try {

            // Weather API
            $weather->sync();

            // Exchange Rate API
            $exchange->sync();

            // News API
            $news->sync();

            // Hitung Risk Score
            $risk->calculate();

            return redirect('/')
                ->with('success','Data berhasil diperbarui.');

        } catch (\Throwable $e) {

            return redirect('/')
                ->with('error',$e->getMessage());

        }
    }
}