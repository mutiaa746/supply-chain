<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\EconomicIndicator;
use App\Models\ExchangeRate;
use App\Models\NewsCache;
use App\Models\WeatherData;
use App\Models\RiskScore;

class RiskScoreController extends Controller
{
    public function calculate()
    {
        $countries = Country::all();

        foreach ($countries as $country) {

            // ==========================
            // WEATHER
            // ==========================
            $weather = WeatherData::where('country_id', $country->id)
                ->latest()
                ->first();

            $weatherScore = 0;

            if ($weather) {

                if ($weather->temperature > 35)
                    $weatherScore += 30;

                if ($weather->humidity > 80)
                    $weatherScore += 20;

                if ($weather->wind_speed > 30)
                    $weatherScore += 20;
            }

            // ==========================
            // ECONOMY
            // ==========================

            $eco = EconomicIndicator::where('country_id', $country->id)
                ->latest()
                ->first();

            $inflationScore = 0;

            if ($eco) {

                if ($eco->inflation_rate >= 10)
                    $inflationScore = 100;

                elseif ($eco->inflation_rate >= 7)
                    $inflationScore = 70;

                elseif ($eco->inflation_rate >= 4)
                    $inflationScore = 40;
            }

            // ==========================
            // CURRENCY
            // ==========================

            $currency = ExchangeRate::where('country_id', $country->id)
                ->latest()
                ->first();

            $currencyScore = 0;

            if ($currency) {

                if ($currency->change_percentage <= -5)
                    $currencyScore = 100;

                elseif ($currency->change_percentage <= -3)
                    $currencyScore = 70;

                elseif ($currency->change_percentage <= -1)
                    $currencyScore = 40;
            }

            // ==========================
            // NEWS
            // ==========================

            $news = NewsCache::where('country_id', $country->id)->count();

            $newsScore = 0;

            if ($news >= 15)
                $newsScore = 100;

            elseif ($news >= 8)
                $newsScore = 70;

            elseif ($news >= 3)
                $newsScore = 40;

            // ==========================
            // TOTAL
            // ==========================

            $total =
                $weatherScore +
                $inflationScore +
                $currencyScore +
                $newsScore;

            if ($total >= 220)
                $level = "High";

            elseif ($total >= 120)
                $level = "Medium";

            else
                $level = "Low";

            RiskScore::updateOrCreate(

                [
                    'country_id' => $country->id
                ],

                [

                    'weather_score' => $weatherScore,
                    'inflation_score' => $inflationScore,
                    'currency_score' => $currencyScore,
                    'news_score' => $newsScore,
                    'total_score' => $total,
                    'risk_level' => $level,

                ]
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Risk Score berhasil dihitung'
        ]);
    }

    public function index()
    {
        return RiskScore::with('country')->get();
    }
}