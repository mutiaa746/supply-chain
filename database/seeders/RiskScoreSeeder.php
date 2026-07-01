<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\RiskScore;

class RiskScoreSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Country::all() as $country) {

            $weather = rand(10,40);
            $inflation = rand(10,30);
            $currency = rand(10,20);
            $news = rand(10,20);

            $total = $weather + $inflation + $currency + $news;

            if($total < 40){
                $risk='Low';
            }elseif($total < 70){
                $risk='Medium';
            }else{
                $risk='High';
            }

            RiskScore::updateOrCreate(
                [
                    'country_id'=>$country->id,
                ],
                [
                    'weather_score'=>$weather,
                    'inflation_score'=>$inflation,
                    'currency_score'=>$currency,
                    'news_score'=>$news,
                    'total_score'=>$total,
                    'risk_level'=>$risk,
                ]
            );

        }
    }
}