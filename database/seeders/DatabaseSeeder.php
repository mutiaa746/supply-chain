public function run(): void
{
    $this->call([
        CountrySeeder::class,
        EconomicIndicatorSeeder::class,
        WeatherDataSeeder::class,
        ExchangeRateSeeder::class,
        PortSeeder::class,
        NewsCacheSeeder::class,
        PositiveWordSeeder::class,
        NegativeWordSeeder::class,
        RiskScoreSeeder::class,
    ]);
}