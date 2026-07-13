<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EconomicIndicatorController extends Controller
{
    public function index()
    {
        // Ambil semua countries
        $countries = Country::select('id', 'country_name', 'country_code', 'flag', 'gdp', 'inflation', 'population', 'currency')
            ->orderBy('country_name')
            ->get();
        
        // Jika tidak ada data GDP/Inflation, ambil dari World Bank
        if ($countries->whereNotNull('gdp')->isEmpty()) {
            $this->fetchWorldBankData();
            // Refresh data
            $countries = Country::select('id', 'country_name', 'country_code', 'flag', 'gdp', 'inflation', 'population', 'currency')
                ->orderBy('country_name')
                ->get();
        }
        
        return view('economic.index', compact('countries'));
    }
    
    public function fetchWorldBankData()
    {
        $targetCountries = ['ID', 'US', 'GB', 'DE', 'CN', 'JP', 'IN', 'BR', 'AU', 'CA', 'FR', 'IT', 'KR', 'MX', 'RU', 'SA', 'ZA', 'TR', 'AR', 'NG'];
        
        foreach ($targetCountries as $code) {
            try {
                // GDP
                $gdpResponse = Http::timeout(10)->get("http://api.worldbank.org/v2/country/{$code}/indicator/NY.GDP.MKTP.CD?format=json");
                $gdp = null;
                if ($gdpResponse->successful()) {
                    $gdpData = $gdpResponse->json();
                    if (isset($gdpData[1]) && is_array($gdpData[1])) {
                        foreach ($gdpData[1] as $item) {
                            if (isset($item['value']) && $item['value'] !== null) {
                                $gdp = $item['value'];
                                break;
                            }
                        }
                    }
                }
                
                // Inflation
                $inflation = null;
                $inflationResponse = Http::timeout(10)->get("http://api.worldbank.org/v2/country/{$code}/indicator/FP.CPI.TOTL.ZG?format=json");
                if ($inflationResponse->successful()) {
                    $inflationData = $inflationResponse->json();
                    if (isset($inflationData[1]) && is_array($inflationData[1])) {
                        foreach ($inflationData[1] as $item) {
                            if (isset($item['value']) && $item['value'] !== null) {
                                $inflation = $item['value'];
                                break;
                            }
                        }
                    }
                }
                
                // Update database
                $country = Country::where('country_code', $code)->first();
                if ($country) {
                    if ($gdp) $country->gdp = $gdp;
                    if ($inflation) $country->inflation = $inflation;
                    $country->save();
                }
                
            } catch (\Exception $e) {
                // Skip jika error
                continue;
            }
        }
        
        return true;
    }
}