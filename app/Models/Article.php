<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title', 'slug', 'content', 'country_id',
        'category', 'author', 'image', 'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}