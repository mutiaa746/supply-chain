<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExchangeRateController extends Controller
{
    public function index()
    {
        // Fetch dari API
        $this->fetchRates();
        
        // Ambil data
        $rates = ExchangeRate::where('base_currency', 'USD')
            ->orderBy('target_currency')
            ->get();
        
        // Data untuk grafik (10 mata uang utama)
        $mainCurrencies = ['IDR', 'EUR', 'GBP', 'JPY', 'CNY', 'SGD', 'MYR', 'PHP', 'THB', 'VND'];
        $chartRates = ExchangeRate::where('base_currency', 'USD')
            ->whereIn('target_currency', $mainCurrencies)
            ->get();
        
        return view('exchange.index', compact('rates', 'chartRates', 'mainCurrencies'));
    }
    
    public function fetchRates()
    {
        try {
            $response = Http::timeout(10)->get('https://api.exchangerate-api.com/v4/latest/USD');
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['rates'])) {
                    foreach ($data['rates'] as $currency => $rate) {
                        ExchangeRate::updateOrCreate(
                            ['base_currency' => 'USD', 'target_currency' => $currency],
                            ['rate' => $rate]
                        );
                    }
                    return true;
                }
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}