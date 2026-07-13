<?php

namespace App\Services;

use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    public function fetchExchangeRates($baseCurrency = 'USD')
    {
        try {
            $response = Http::timeout(30)->get("https://api.exchangerate-api.com/v4/latest/{$baseCurrency}");
            
            if (!$response->successful()) {
                return ['success' => false, 'message' => 'API Error: ' . $response->status()];
            }
            
            $data = $response->json();
            
            if (!isset($data['rates']) || !is_array($data['rates'])) {
                return ['success' => false, 'message' => 'Invalid response format'];
            }
            
            foreach ($data['rates'] as $currency => $rate) {
                ExchangeRate::updateOrCreate(
                    ['base_currency' => $baseCurrency, 'target_currency' => $currency],
                    ['rate' => $rate]
                );
            }
            
            return ['success' => true, 'base' => $baseCurrency, 'rates' => $data['rates']];
            
        } catch (\Exception $e) {
            Log::error('CurrencyService Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function getExchangeRate($fromCurrency, $toCurrency = 'USD')
    {
        try {
            // Cek di database
            $rate = ExchangeRate::where('base_currency', $fromCurrency)
                ->where('target_currency', $toCurrency)
                ->first();
                
            if ($rate && $rate->updated_at->diffInMinutes() < 60) {
                return $rate->rate;
            }
            
            $result = $this->fetchExchangeRates($fromCurrency);
            
            if ($result['success'] && isset($result['rates'][$toCurrency])) {
                return $result['rates'][$toCurrency];
            }
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('getExchangeRate Error: ' . $e->getMessage());
            return null;
        }
    }
}