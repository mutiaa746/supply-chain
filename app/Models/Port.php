<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Port extends Model
{
    protected $fillable = [
        'country_id',
        'country_name',
        'port_name',
        'latitude',
        'longitude',
        'harbor_size',
        'harbor_type',
        'status',
        'delay_hours',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'delay_hours' => 'float',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}