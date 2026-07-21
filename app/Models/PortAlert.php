<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortAlert extends Model
{
    protected $table = 'port_alerts';
    
    protected $fillable = [
        'port_id',
        'type', // congestion, closure, strike, maintenance, weather
        'severity', // low, medium, high, critical
        'title',
        'description',
        'status', // active, resolved
        'start_at',
        'end_at'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime'
    ];

    public function port()
    {
        return $this->belongsTo(Port::class);
    }

    public function getSeverityColor()
    {
        return match($this->severity) {
            'low' => '#22c55e',
            'medium' => '#eab308',
            'high' => '#f97316',
            'critical' => '#ef4444',
            default => '#6b7280'
        };
    }

    public function getSeverityBadge()
    {
        return match($this->severity) {
            'low' => '🟢 Normal',
            'medium' => '🟡 Waspada',
            'high' => '🟠 Siaga',
            'critical' => '🔴 Kritis',
            default => '⚪ Normal'
        };
    }

    public function getTypeIcon()
    {
        return match($this->type) {
            'congestion' => '🚦',
            'closure' => '🚫',
            'strike' => '✊',
            'maintenance' => '🔧',
            'weather' => '🌩️',
            'accident' => '🚨',
            default => '⚠️'
        };
    }

    public function getStatusBadge()
    {
        return $this->status == 'active' 
            ? '<span class="badge bg-danger">Aktif</span>'
            : '<span class="badge bg-success">Selesai</span>';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where(function($q) {
                         $q->whereNull('end_at')
                           ->orWhere('end_at', '>=', now());
                     });
    }
}