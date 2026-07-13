<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentimentWord extends Model
{
    protected $fillable = ['word', 'type'];

    public function scopePositive($query)
    {
        return $query->where('type', 'positive');
    }

    public function scopeNegative($query)
    {
        return $query->where('type', 'negative');
    }
}