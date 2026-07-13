<?php

namespace App\Services;

use App\Models\Country;
use App\Models\RiskScore;
use App\Models\NewsCache;
use Illuminate\Support\Facades\Log;

class RiskScoreService
{
    protected $weatherService;
    protected $currencyService;
    protected $newsService;
    
    protected $weights = [
        'weather' => 0.30,
        'inflation' => 0.20,
        'political' => 0.40,
        'currency' => 0.10
    ];
    
    public function __construct(
        WeatherService $weatherService,
        CurrencyService $currencyService,
        NewsService $newsService
    ) {
        $this->weatherService = $weatherService;
        $this->currencyService = $currencyService;
        $this->newsService = $newsService;
    }
    
    public function calculateRisk($countryCode)
    {
        try {
            $country = Country::where('country_code', $countryCode)->first();
            
            if (!$country) {
                return null;
            }
            
            $weatherRisk = $this->calculateWeatherRisk($country);
            $inflationRisk = $this->calculateInflationRisk($country);
            $politicalRisk = $this->calculatePoliticalRisk($country);
            $currencyRisk = $this->calculateCurrencyRisk($country);
            
            $totalScore = ($weatherRisk * $this->weights['weather']) +
                         ($inflationRisk * $this->weights['inflation']) +
                         ($politicalRisk * $this->weights['political']) +
                         ($currencyRisk * $this->weights['currency']);
            
            $riskLevel = $this->getRiskLevel($totalScore);
            
            RiskScore::updateOrCreate(
                ['country_id' => $country->id],
                [
                    'weather_score' => $weatherRisk,
                    'inflation_score' => $inflationRisk,
                    'news_score' => $politicalRisk,
                    'currency_score' => $currencyRisk,
                    'total_score' => $totalScore,
                    'risk_level' => $riskLevel,
                    'calculated_at' => now()
                ]
            );
            
            return [
                'country' => $country->country_name,
                'weather_risk' => $weatherRisk,
                'inflation_risk' => $inflationRisk,
                'political_risk' => $politicalRisk,
                'currency_risk' => $currencyRisk,
                'total_score' => $totalScore,
                'risk_level' => $riskLevel
            ];
            
        } catch (\Exception $e) {
            Log::error('RiskScoreService Error: ' . $e->getMessage());
            return null;
        }
    }
    
    private function calculateWeatherRisk($country)
    {
        if (!$country->latitude || !$country->longitude) return 20;
        
        try {
            $weather = $this->weatherService->getWeather($country->latitude, $country->longitude);
            
            if (!$weather || !isset($weather['current_weather'])) return 20;
            
            $weatherCode = $weather['current_weather']['weathercode'] ?? 0;
            $level = $this->weatherService->getWeatherRiskLevel($weatherCode);
            
            return match($level) {
                'High' => 80,
                'Medium' => 50,
                'Low' => 20,
                default => 30
            };
        } catch (\Exception $e) {
            return 20;
        }
    }
    
    private function calculateInflationRisk($country)
    {
        if (!$country->inflation) return 20;
        
        $inflation = $country->inflation;
        
        if ($inflation > 10) return 90;
        if ($inflation > 7) return 70;
        if ($inflation > 5) return 50;
        if ($inflation > 3) return 30;
        return 10;
    }
    
    private function calculatePoliticalRisk($country)
    {
        try {
            $news = NewsCache::where('country_code', $country->country_code)
                ->orderBy('published_at', 'desc')
                ->limit(10)
                ->get();
                
            if ($news->isEmpty()) return 30;
            
            $positiveCount = 0;
            $negativeCount = 0;
            $neutralCount = 0;
            
            foreach ($news as $item) {
                if ($item->sentiment == 'positive') $positiveCount++;
                elseif ($item->sentiment == 'negative') $negativeCount++;
                else $neutralCount++;
            }
            
            $total = $positiveCount + $negativeCount + $neutralCount;
            if ($total === 0) return 30;
            
            $negativePercentage = ($negativeCount / $total) * 100;
            
            if ($negativePercentage > 60) return 90;
            if ($negativePercentage > 40) return 70;
            if ($negativePercentage > 20) return 50;
            return 30;
            
        } catch (\Exception $e) {
            return 30;
        }
    }
    
    private function calculateCurrencyRisk($country)
    {
        if (!$country->currency_code) return 20;
        
        try {
            $rate = $this->currencyService->getExchangeRate($country->currency_code, 'USD');
            
            if (!$rate) return 20;
            
            if ($rate < 0.5) return 80;
            if ($rate < 1) return 60;
            if ($rate < 2) return 40;
            return 20;
            
        } catch (\Exception $e) {
            return 20;
        }
    }
    
    private function getRiskLevel($score)
    {
        if ($score >= 70) return 'Critical';
        if ($score >= 50) return 'High';
        if ($score >= 30) return 'Medium';
        return 'Low';
    }
    
    public function calculateAllRisks()
    {
        $countries = Country::all();
        $results = [];
        
        foreach ($countries as $country) {
            $result = $this->calculateRisk($country->country_code);
            if ($result) {
                $results[] = $result;
            }
        }
        
        return $results;
    }
}