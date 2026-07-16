<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExchangeRateController extends Controller
{
    // Mapping currency ke country
    private $currencyToCountry = [
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
        'TRY' => 'Turkiye',
        'SAR' => 'Saudi Arabia',
        'AED' => 'UAE',
        'EGP' => 'Egypt',
        'NGN' => 'Nigeria',
        'PKR' => 'Pakistan',
        'BDT' => 'Bangladesh',
        'HKD' => 'Hong Kong',
        'TWD' => 'Taiwan',
        'SEK' => 'Sweden',
        'NOK' => 'Norway',
        'DKK' => 'Denmark',
        'PLN' => 'Poland',
        'CZK' => 'Czech Republic',
        'HUF' => 'Hungary',
        'ILS' => 'Israel',
        'CLP' => 'Chile',
        'COP' => 'Colombia',
        'PEN' => 'Peru',
        'ARS' => 'Argentina',
        'UYU' => 'Uruguay',
        'PYG' => 'Paraguay',
        'BOB' => 'Bolivia',
        'VES' => 'Venezuela',
        'CRC' => 'Costa Rica',
        'DOP' => 'Dominican Republic',
        'GTQ' => 'Guatemala',
        'HNL' => 'Honduras',
        'NIO' => 'Nicaragua',
        'PAB' => 'Panama',
        'BBD' => 'Barbados',
        'BSD' => 'Bahamas',
        'BMD' => 'Bermuda',
        'KYD' => 'Cayman Islands',
        'JMD' => 'Jamaica',
        'TTD' => 'Trinidad and Tobago',
        'XCD' => 'East Caribbean',
        'FJD' => 'Fiji',
        'PGK' => 'Papua New Guinea',
        'SBD' => 'Solomon Islands',
        'TOP' => 'Tonga',
        'VUV' => 'Vanuatu',
        'WST' => 'Samoa',
        'BGN' => 'Bulgaria',
        'RON' => 'Romania',
        'RSD' => 'Serbia',
        'ISK' => 'Iceland',
        'MKD' => 'North Macedonia',
        'BAM' => 'Bosnia',
        'ALL' => 'Albania',
        'MDL' => 'Moldova',
        'GEL' => 'Georgia',
        'AMD' => 'Armenia',
        'AZN' => 'Azerbaijan',
        'KZT' => 'Kazakhstan',
        'UZS' => 'Uzbekistan',
        'TMT' => 'Turkmenistan',
        'KGS' => 'Kyrgyzstan',
        'TJS' => 'Tajikistan',
        'AFN' => 'Afghanistan',
        'IRR' => 'Iran',
        'IQD' => 'Iraq',
        'JOD' => 'Jordan',
        'KWD' => 'Kuwait',
        'LBP' => 'Lebanon',
        'LYD' => 'Libya',
        'MAD' => 'Morocco',
        'MUR' => 'Mauritius',
        'OMR' => 'Oman',
        'QAR' => 'Qatar',
        'SCR' => 'Seychelles',
        'TND' => 'Tunisia',
        'YER' => 'Yemen',
    ];

    public function index(Request $request)
    {
        // Ambil semua data
        $allRates = ExchangeRate::where('base_currency', 'USD')
            ->orderBy('target_currency')
            ->get();

        if ($allRates->isEmpty()) {
            $this->fetchRatesFromAPI();
            $allRates = ExchangeRate::where('base_currency', 'USD')
                ->orderBy('target_currency')
                ->get();
        }

        // SEARCH - cari berdasarkan kode mata uang ATAU nama negara
        $search = $request->get('search');
        $rates = $allRates;

        if ($search) {
            $searchLower = strtolower($search);
            $rates = $allRates->filter(function($rate) use ($searchLower) {
                // Cari di kode mata uang
                if (stripos($rate->target_currency, $searchLower) !== false) {
                    return true;
                }
                // Cari di nama negara
                $countryName = $this->currencyToCountry[$rate->target_currency] ?? '';
                return stripos($countryName, $searchLower) !== false;
            });
        }

        // ========== DATA GRAFIK ==========
        $mainCurrencies = ['IDR', 'EUR', 'GBP', 'JPY', 'CNY'];
        $chartRates = [];
        
        foreach ($mainCurrencies as $currency) {
            $rate = ExchangeRate::where('base_currency', 'USD')
                ->where('target_currency', $currency)
                ->first();
            $chartRates[$currency] = $rate ? $rate->rate : 0;
        }

        $defaults = ['IDR' => 15500, 'EUR' => 0.92, 'GBP' => 0.78, 'JPY' => 148.50, 'CNY' => 7.25];
        foreach ($defaults as $currency => $default) {
            if ($chartRates[$currency] == 0) {
                $chartRates[$currency] = $default;
            }
        }

        $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
        
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

        return view('exchange.index', [
            'rates' => $rates,
            'allRates' => $allRates,
            'chartData' => $chartData,
            'search' => $search,
            'currencyToCountry' => $this->currencyToCountry
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