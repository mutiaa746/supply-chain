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

    // =============================================
    // 🔥 TAMBAHKAN METHOD INI UNTUK ROUTE SIMULATION
    // =============================================

    /**
     * Get full country name with flag
     */
    public function getFullNameAttribute()
    {
        return ($this->flag ?? '🏳️') . ' ' . $this->country_name;
    }

    /**
     * Check if country has valid coordinates
     */
    public function hasCoordinates()
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    /**
     * Get coordinates as array [lat, lng]
     */
    public function getCoordinatesAttribute()
    {
        if ($this->hasCoordinates()) {
            return [(float) $this->latitude, (float) $this->longitude];
        }
        return null;
    }

    /**
     * Get distance to another country in KM
     */
    public function distanceTo(Country $other)
    {
        if (!$this->hasCoordinates() || !$other->hasCoordinates()) {
            return null;
        }

        return $this->haversineDistance(
            (float) $this->latitude,
            (float) $this->longitude,
            (float) $other->latitude,
            (float) $other->longitude
        );
    }

    /**
     * Haversine formula to calculate distance between two points
     */
    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // KM
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);
        
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        
        $a = sin($dlat/2) * sin($dlat/2) + 
             cos($lat1) * cos($lat2) * 
             sin($dlon/2) * sin($dlon/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }

    /**
     * Scope: get countries with coordinates
     */
    public function scopeWithCoordinates($query)
    {
        return $query->whereNotNull('latitude')
                     ->whereNotNull('longitude');
    }

    /**
     * Scope: search by country name
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('country_name', 'LIKE', "%{$search}%")
                     ->orWhere('country_code', 'LIKE', "%{$search}%");
    }
}