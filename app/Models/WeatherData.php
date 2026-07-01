<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeatherData extends Model
{
    protected $fillable = [
        'country_id',
        'temperature',
        'humidity',
        'wind_speed',
        'weather_condition',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}