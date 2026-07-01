<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskScore extends Model
{
    protected $fillable = [
        'country_id',
        'score',
        'risk_level',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}