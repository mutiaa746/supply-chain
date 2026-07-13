<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'country_name', 'country_code', 'capital', 'region',
        'currency', 'currency_code', 'language', 'flag',
        'population', 'gdp', 'inflation', 'latitude', 'longitude'
    ];

    public function riskScores()
    {
        return $this->hasMany(RiskScore::class);
    }

    public function news()
    {
        return $this->hasMany(NewsCache::class);
    }

    public function ports()
    {
        return $this->hasMany(Port::class);
    }

    public function watchlists()
    {
        return $this->hasMany(Watchlist::class);
    }

    public function economicIndicators()
    {
        return $this->hasMany(EconomicIndicator::class);
    }

    public function getLatestRiskScore()
    {
        return $this->riskScores()->latest()->first();
    }

    public function getFlagUrl()
    {
        return $this->flag ?? "https://flagcdn.com/w320/{$this->country_code}.png";
    }
}