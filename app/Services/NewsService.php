<?php

namespace App\Services;

use App\Models\NewsCache;
use App\Models\SentimentWord;
use App\Models\Country;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsService
{
    public function fetchNews($countryCode, $query = null)
    {
        try {
            $apiKey = env('GNEWS_API_KEY', '');
            
            if (empty($apiKey)) {
                return $this->getDummyNews($countryCode);
            }
            
            $searchQuery = $query ?? "logistics trade shipping economy {$countryCode}";
            
            $response = Http::timeout(10)->get("https://gnews.io/api/v4/search", [
                'q' => $searchQuery,
                'token' => $apiKey,
                'lang' => 'en',
                'max' => 10,
                'country' => $countryCode
            ]);
            
            if (!$response->successful()) {
                return $this->getDummyNews($countryCode);
            }
            
            $data = $response->json();
            
            if (!isset($data['articles']) || !is_array($data['articles'])) {
                return $this->getDummyNews($countryCode);
            }
            
            // Cari country_id
            $country = Country::where('country_code', $countryCode)->first();
            $countryId = $country ? $country->id : null;
            
            foreach ($data['articles'] as $article) {
                $text = ($article['description'] ?? $article['title'] ?? '');
                $sentiment = $this->analyzeSentiment($text);
                
                NewsCache::updateOrCreate(
                    ['url' => $article['url'] ?? ''],
                    [
                        'country_id' => $countryId,
                        'country_code' => $countryCode,
                        'title' => $article['title'] ?? 'No Title',
                        'description' => $article['description'] ?? '',
                        'source' => $article['source']['name'] ?? 'Unknown',
                        'published_at' => $article['publishedAt'] ?? now(),
                        'sentiment' => $sentiment['sentiment'],
                        'sentiment_score' => $sentiment['score']
                    ]
                );
            }
            
            return $data;
            
        } catch (\Exception $e) {
            return $this->getDummyNews($countryCode);
        }
    }
    
    public function getDummyNews($countryCode)
    {
        return [
            'articles' => [
                [
                    'title' => "Trade Update for {$countryCode}",
                    'description' => "Trade activities show stable growth in the region.",
                    'source' => ['name' => 'Trade News'],
                    'publishedAt' => now()->toISOString(),
                    'url' => '#'
                ],
                [
                    'title' => "Economic Outlook for {$countryCode}",
                    'description' => "Economic indicators remain positive with steady inflation.",
                    'source' => ['name' => 'Economic Times'],
                    'publishedAt' => now()->toISOString(),
                    'url' => '#'
                ],
                [
                    'title' => "Logistics Update for {$countryCode}",
                    'description' => "Logistics sector shows improvement in supply chain efficiency.",
                    'source' => ['name' => 'Logistics Today'],
                    'publishedAt' => now()->toISOString(),
                    'url' => '#'
                ]
            ]
        ];
    }
    
    public function analyzeSentiment($text)
    {
        $positiveWords = SentimentWord::where('type', 'positive')->pluck('word')->toArray();
        $negativeWords = SentimentWord::where('type', 'negative')->pluck('word')->toArray();
        
        $text = strtolower($text);
        $words = str_word_count($text, 1);
        
        $positiveCount = 0;
        $negativeCount = 0;
        
        foreach ($words as $word) {
            if (in_array($word, $positiveWords)) $positiveCount++;
            if (in_array($word, $negativeWords)) $negativeCount++;
        }
        
        $total = $positiveCount + $negativeCount;
        
        if ($total === 0) {
            return ['sentiment' => 'neutral', 'score' => 0];
        }
        
        $score = ($positiveCount - $negativeCount) / $total;
        
        if ($score > 0.2) {
            return ['sentiment' => 'positive', 'score' => $score];
        } elseif ($score < -0.2) {
            return ['sentiment' => 'negative', 'score' => $score];
        } else {
            return ['sentiment' => 'neutral', 'score' => $score];
        }
    }
}