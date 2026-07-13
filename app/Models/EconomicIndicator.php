<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EconomicIndicator extends Model
{
    protected $fillable = ['country_id', 'indicator_name', 'indicator_code', 'value', 'year'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}