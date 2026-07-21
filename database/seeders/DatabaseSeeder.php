<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CountrySeeder::class,
            PortSeeder::class,
            ExchangeRateSeeder::class,
            EconomicIndicatorSeeder::class,
            SentimentWordsSeeder::class,
            NewsCacheSeeder::class,
            WeatherDataSeeder::class,
            RiskScoreSeeder::class,
        ]);
    }
}