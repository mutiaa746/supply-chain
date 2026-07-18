<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherData extends Model
{
    protected $table = 'weather_data';

    protected $fillable = [

        'country_id',

        'temperature',

        'wind_speed',

        'weathercode',

        'description',

        'storm_risk',

        'rain',

        'humidity',

    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}