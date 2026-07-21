<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherAlert extends Model
{
    protected $table = 'weather_alerts';
    
    protected $fillable = [
        'country_id',
        'port_id',
        'type',
        'severity', // low, medium, high, critical
        'title',
        'description',
        'latitude',
        'longitude',
        'radius_km',
        'start_at',
        'end_at',
        'is_active'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function port()
    {
        return $this->belongsTo(Port::class);
    }

    public function getSeverityColor()
    {
        return match($this->severity) {
            'low' => '#22c55e',      // Hijau
            'medium' => '#eab308',   // Kuning
            'high' => '#f97316',     // Oranye
            'critical' => '#ef4444', // Merah
            default => '#6b7280'
        };
    }

    public function getSeverityBadge()
    {
        return match($this->severity) {
            'low' => '🟢 Aman',
            'medium' => '🟡 Waspada',
            'high' => '🟠 Siaga',
            'critical' => '🔴 Bahaya',
            default => '⚪ Normal'
        };
    }

    public function getTypeIcon()
    {
        return match($this->type) {
            'storm' => '🌩️',
            'rain' => '🌧️',
            'wind' => '💨',
            'flood' => '🌊',
            'fog' => '🌫️',
            'heatwave' => '🌡️',
            'snow' => '❄️',
            default => '⚠️'
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where(function($q) {
                         $q->whereNull('end_at')
                           ->orWhere('end_at', '>=', now());
                     });
    }

    public function scopeNearLocation($query, $lat, $lng, $radius = 500)
    {
        return $query->whereRaw("
            ST_Distance_Sphere(
                point(longitude, latitude),
                point(?, ?)
            ) <= ?
        ", [$lng, $lat, $radius * 1000]);
    }
}