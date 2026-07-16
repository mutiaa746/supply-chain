<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExchangeRateController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua data exchange rate
        $rates = ExchangeRate::where('base_currency', 'USD')
            ->orderBy('target_currency')
            ->get();

        // Jika kosong, fetch dari API
        if ($rates->isEmpty()) {
            $this->fetchRatesFromAPI();
            $rates = ExchangeRate::where('base_currency', 'USD')
                ->orderBy('target_currency')
                ->get();
        }

        // SEARCH
        $search = $request->get('search');
        if ($search) {
            $search = strtoupper($search);
            $rates = ExchangeRate::where('base_currency', 'USD')
                ->where('target_currency', 'LIKE', "%{$search}%")
                ->orderBy('target_currency')
                ->get();
        }

        // ========== DATA GRAFIK (PAKSA ADA) ==========
        // Ambil 5 mata uang utama
        $mainCurrencies = ['IDR', 'EUR', 'GBP', 'JPY', 'CNY'];
        $chartRates = [];
        
        foreach ($mainCurrencies as $currency) {
            $rate = ExchangeRate::where('base_currency', 'USD')
                ->where('target_currency', $currency)
                ->first();
            
            $chartRates[$currency] = $rate ? $rate->rate : 0;
        }

        // Jika masih ada yang 0, pakai default
        $defaults = ['IDR' => 15500, 'EUR' => 0.92, 'GBP' => 0.78, 'JPY' => 148.50, 'CNY' => 7.25];
        foreach ($defaults as $currency => $default) {
            if ($chartRates[$currency] == 0) {
                $chartRates[$currency] = $default;
            }
        }

        // Data untuk grafik (4 kuartal)
        $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
        
        // Buat data fluktuasi (simulasi perubahan per kuartal)
        $chartData = [
            'labels' => $quarters,
            'idr' => [
                $chartRates['IDR'] * 0.95,
                $chartRates['IDR'] * 0.97,
                $chartRates['IDR'] * 1.02,
                $chartRates['IDR'] * 1.05
            ],
            'eur' => [
                $chartRates['EUR'] * 0.98,
                $chartRates['EUR'] * 0.99,
                $chartRates['EUR'] * 1.01,
                $chartRates['EUR'] * 1.03
            ],
            'gbp' => [
                $chartRates['GBP'] * 0.97,
                $chartRates['GBP'] * 0.98,
                $chartRates['GBP'] * 1.02,
                $chartRates['GBP'] * 1.04
            ],
            'jpy' => [
                $chartRates['JPY'] * 0.96,
                $chartRates['JPY'] * 0.98,
                $chartRates['JPY'] * 1.01,
                $chartRates['JPY'] * 1.04
            ],
            'cny' => [
                $chartRates['CNY'] * 0.97,
                $chartRates['CNY'] * 0.99,
                $chartRates['CNY'] * 1.01,
                $chartRates['CNY'] * 1.03
            ]
        ];

        // Tambahkan nama negara ke tabel
        $currencyToCountry = [
            'IDR' => 'Indonesia',
            'MYR' => 'Malaysia',
            'SGD' => 'Singapore',
            'PHP' => 'Philippines',
            'THB' => 'Thailand',
            'VND' => 'Vietnam',
            'USD' => 'United States',
            'EUR' => 'Eurozone',
            'GBP' => 'United Kingdom',
            'JPY' => 'Japan',
            'CNY' => 'China',
            'AUD' => 'Australia',
            'CAD' => 'Canada',
            'CHF' => 'Switzerland',
            'NZD' => 'New Zealand',
            'KRW' => 'South Korea',
            'INR' => 'India',
            'BRL' => 'Brazil',
            'MXN' => 'Mexico',
            'ZAR' => 'South Africa',
            'RUB' => 'Russia',
            'TRY' => 'Turkey',
            'SAR' => 'Saudi Arabia',
            'AED' => 'UAE',
            'EGP' => 'Egypt',
            'NGN' => 'Nigeria',
        ];

        $ratesWithCountry = $rates->map(function($rate) use ($currencyToCountry) {
            $rate->country_name = $currencyToCountry[$rate->target_currency] ?? $rate->target_currency;
            return $rate;
        });

        return view('exchange.index', [
            'rates' => $ratesWithCountry,
            'chartData' => $chartData,
            'search' => $search
        ]);
    }

    public function fetchRates()
    {
        $result = $this->fetchRatesFromAPI();
        
        if ($result) {
            return redirect('/exchange')->with('success', '✅ Exchange rates updated from API!');
        }
        return redirect('/exchange')->with('error', '❌ Failed to fetch exchange rates');
    }

    private function fetchRatesFromAPI()
    {
        try {
            $response = Http::timeout(15)->get('https://api.exchangerate-api.com/v4/latest/USD');
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['rates'])) {
                    ExchangeRate::truncate();
                    foreach ($data['rates'] as $currency => $rate) {
                        ExchangeRate::create([
                            'base_currency' => 'USD',
                            'target_currency' => $currency,
                            'rate' => $rate
                        ]);
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