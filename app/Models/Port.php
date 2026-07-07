<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'status'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}