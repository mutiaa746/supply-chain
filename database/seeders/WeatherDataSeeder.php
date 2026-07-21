<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\WeatherData;

class WeatherDataSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Country::all() as $country) {

            WeatherData::updateOrCreate(
                [
                    'country_id' => $country->id,
                ],
                [
                    'temperature' => rand(20, 38),
                    'rain'        => rand(0, 100),
                    'wind_speed'  => rand(5, 40),
                    'humidity'    => rand(40, 90),
                    'weathercode' => rand(0, 99),
                    'storm_risk'  => collect([
                        'Low',
                        'Medium',
                        'High'
                    ])->random(),
                    'description' => 'Sunny with scattered clouds',
                ]
            );

        }
    }
}