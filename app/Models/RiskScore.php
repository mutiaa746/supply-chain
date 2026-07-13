<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskScore extends Model
{
    protected $fillable = [
        'country_id', 'weather_score', 'inflation_score',
        'currency_score', 'news_score', 'total_score',
        'risk_level', 'calculated_at'
    ];

    protected $casts = [
        'calculated_at' => 'datetime',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function getRiskColor()
    {
        return match($this->risk_level) {
            'Low' => 'success',
            'Medium' => 'warning',
            'High' => 'orange',
            'Critical' => 'danger',
            default => 'secondary'
        };
    }

    public function getRiskBadge()
    {
        $colors = [
            'Low' => 'success',
            'Medium' => 'warning',
            'High' => 'danger',
            'Critical' => 'dark'
        ];
        return '<span class="badge bg-' . ($colors[$this->risk_level] ?? 'secondary') . '">' . $this->risk_level . '</span>';
    }
}