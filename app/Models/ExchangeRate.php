<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExchangeRate extends Model
{
    protected $fillable = [
        'country_id',
        'base_currency',
        'target_currency',
        'exchange_rate',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}