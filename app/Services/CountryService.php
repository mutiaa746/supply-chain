<?php

namespace App\Services;

use App\Models\Country;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CountryService
{
    public function fetchAndUpdateCountries()
    {
        try {
            $response = Http::timeout(30)->get('https://restcountries.com/v3.1/all');
            
            if (!$response->successful()) {
                return ['success' => false, 'message' => 'Gagal mengambil data dari API. Status: ' . $response->status()];
            }
            
            $countries = $response->json();
            
            if (!is_array($countries) || empty($countries)) {
                return ['success' => false, 'message' => 'Data API kosong atau tidak valid'];
            }
            
            $count = 0;
            $errors = [];
            
            foreach ($countries as $index => $data) {
                try {
                    // Debug: log data pertama
                    if ($index === 0) {
                        Log::info('Sample country data:', ['data' => $data]);
                    }
                    
                    // Cek apakah data memiliki field yang diperlukan
                    if (!isset($data['cca2'])) {
                        $errors[] = "No cca2 at index $index";
                        continue;
                    }
                    
                    if (!isset($data['name']) || !isset($data['name']['common'])) {
                        $errors[] = "No name at index $index";
                        continue;
                    }
                    
                    $countryCode = $data['cca2'];
                    
                    // Cari atau buat baru
                    $country = Country::where('country_code', $countryCode)->first();
                    
                    if (!$country) {
                        $country = new Country();
                        $country->country_code = $countryCode;
                    }
                    
                    // Isi data
                    $country->country_name = $data['name']['common'] ?? 'Unknown';
                    $country->capital = isset($data['capital'][0]) ? $data['capital'][0] : null;
                    $country->region = $data['region'] ?? null;
                    
                    // Currency
                    if (isset($data['currencies']) && is_array($data['currencies'])) {
                        $currencyKeys = array_keys($data['currencies']);
                        $country->currency = implode(', ', $currencyKeys);
                        $country->currency_code = $currencyKeys[0] ?? null;
                    }
                    
                    // Language
                    if (isset($data['languages']) && is_array($data['languages'])) {
                        $country->language = implode(', ', array_values($data['languages']));
                    }
                    
                    $country->flag = $data['flags']['svg'] ?? null;
                    $country->population = $data['population'] ?? null;
                    $country->latitude = isset($data['latlng'][0]) ? $data['latlng'][0] : null;
                    $country->longitude = isset($data['latlng'][1]) ? $data['latlng'][1] : null;
                    
                    $country->save();
                    $count++;
                    
                } catch (\Exception $e) {
                    $errors[] = "Error at index $index: " . $e->getMessage();
                    continue;
                }
            }
            
            return [
                'success' => true,
                'message' => "Berhasil mengupdate {$count} negara",
                'total' => $count,
                'errors' => $errors,
                'total_data' => count($countries)
            ];
            
        } catch (\Exception $e) {
            Log::error('CountryService Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    // ... method lainnya
}