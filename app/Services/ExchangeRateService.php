<?php

namespace App\Services;

use App\Models\Country;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Http;

class ExchangeRateService
{
    public function sync(): bool
    {
        $apiKey = env('EXCHANGE_RATE_API_KEY');

        $response = Http::get(
            "https://v6.exchangerate-api.com/v6/{$apiKey}/latest/USD"
        );

        if (!$response->successful()) {
            return false;
        }

        $rates = $response->json('conversion_rates');

        if (!$rates) {
            return false;
        }

        $countries = Country::all();

        foreach ($countries as $country) {

            if (!$country->currency) {
                continue;
            }

            if (!isset($rates[$country->currency])) {
                continue;
            }

            ExchangeRate::updateOrCreate(
                [
                    'country_id' => $country->id
                ],
                [
                    'currency' => $country->currency,
                    'exchange_rate' => $rates[$country->currency],
                    'recorded_at' => now(),
                ]
            );
        }

        return true;
    }
}