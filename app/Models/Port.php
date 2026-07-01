<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Port extends Model
{
    protected $fillable = [
        'country_id',
        'port_name',
        'status',
        'delay_hours',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}