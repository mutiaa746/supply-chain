<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'country_name',
        'country_code',
        'capital',
        'region',
        'currency',
        'language',
        'flag'
    ];

    public function economicIndicators()
    {
        return $this->hasMany(EconomicIndicator::class);
    }

    public function weatherData()
    {
        return $this->hasMany(WeatherData::class);
    }

    public function exchangeRates()
    {
        return $this->hasMany(ExchangeRate::class);
    }

    public function ports()
    {
        return $this->hasMany(Port::class);
    }

    public function newsCaches()
    {
        return $this->hasMany(NewsCache::class);
    }

    public function riskScores()
    {
        return $this->hasMany(RiskScore::class);
    }

    public function watchlists()
    {
        return $this->hasMany(Watchlist::class);
    }
}