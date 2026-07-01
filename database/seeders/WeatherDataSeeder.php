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
                    'rainfall'    => rand(0, 300),
                    'wind_speed'  => rand(5, 40),
                    'storm_risk'  => collect([
                        'Low',
                        'Medium',
                        'High'
                    ])->random(),
                    'recorded_at' => now(),
                ]
            );

        }
    }
}