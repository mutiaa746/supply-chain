<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    protected $fillable = [
        'user_id',
        'tracking_number',
        'origin',
        'destination',
        'status',
        'estimated_delivery',
        'weight',
        'package_type'
    ];

    protected $casts = [
        'estimated_delivery' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}