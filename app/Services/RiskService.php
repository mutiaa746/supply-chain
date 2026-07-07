<?php

namespace App\Services;

use App\Models\Country;
use App\Models\WeatherData;
use App\Models\EconomicIndicator;
use App\Models\ExchangeRate;
use App\Models\NewsCache;
use App\Models\RiskScore;

class RiskService
{
    public function calculate()
    {
        $countries = Country::all();

        foreach ($countries as $country) {

            // ==========================
            // WEATHER
            // ==========================

            $weather = WeatherData::where('country_id', $country->id)->first();

            $weatherScore = 0;

            if ($weather) {

                switch ($weather->storm_risk) {

                    case 'High':
                        $weatherScore = 100;
                        break;

                    case 'Medium':
                        $weatherScore = 60;
                        break;

                    default:
                        $weatherScore = 20;
                }
            }

            // ==========================
            // ECONOMIC
            // ==========================

            $economic = EconomicIndicator::where('country_id', $country->id)->first();

            $inflationScore = 0;

            if ($economic) {

                if ($economic->inflation >= 10) {

                    $inflationScore = 100;

                } elseif ($economic->inflation >= 5) {

                    $inflationScore = 60;

                } else {

                    $inflationScore = 20;

                }

            }

            // ==========================
            // EXCHANGE RATE
            // ==========================

            $exchange = ExchangeRate::where('country_id', $country->id)->first();

            $currencyScore = 40;

            if ($exchange) {

                if ($exchange->exchange_rate >= 15000) {

                    $currencyScore = 100;

                } elseif ($exchange->exchange_rate >= 10000) {

                    $currencyScore = 60;

                } else {

                    $currencyScore = 20;

                }

            }

            // ==========================
            // NEWS
            // ==========================

            $negative = NewsCache::where('country_id', $country->id)
                ->where('sentiment', 'negative')
                ->count();

            $neutral = NewsCache::where('country_id', $country->id)
                ->where('sentiment', 'neutral')
                ->count();

            $positive = NewsCache::where('country_id', $country->id)
                ->where('sentiment', 'positive')
                ->count();

            $newsScore = ($negative * 20) + ($neutral * 10);

            if ($newsScore > 100) {
                $newsScore = 100;
            }

            // ==========================
            // TOTAL
            // ==========================

            $total = $weatherScore
                + $inflationScore
                + $currencyScore
                + $newsScore;

            if ($total >= 250) {

                $risk = "High";

            } elseif ($total >= 150) {

                $risk = "Medium";

            } else {

                $risk = "Low";

            }

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

                    'risk_level' => $risk,

                ]

            );

        }

        return true;
    }
}