<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCache extends Model
{
    protected $fillable = [
        'country_id', 'country_code', 'title', 'description',
        'source', 'url', 'sentiment', 'sentiment_score', 'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function getSentimentBadge()
    {
        $colors = [
            'positive' => 'success',
            'neutral' => 'secondary',
            'negative' => 'danger'
        ];
        return '<span class="badge bg-' . ($colors[$this->sentiment] ?? 'secondary') . '">' . ucfirst($this->sentiment ?? 'Unknown') . '</span>';
    }
}