public function country()
{
    return $this->belongsTo(Country::class);
}

public function sentimentResults()
{
    return $this->hasMany(SentimentResult::class);
}