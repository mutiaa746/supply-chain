<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentimentResult extends Model
{
    protected $fillable = [
        'news_cache_id',
        'positive_score',
        'negative_score',
        'sentiment',
    ];

    public function newsCache()
    {
        return $this->belongsTo(NewsCache::class);
    }
}