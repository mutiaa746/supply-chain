<?php

namespace App\Http\Controllers;

use App\Models\NewsCache;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $countryCode = $request->get('country', 'ID');
        $countries = Country::select('id', 'country_name', 'country_code')->get();

        // Ambil dari database
        $news = NewsCache::where('country_code', $countryCode)
            ->orderBy('published_at', 'desc')
            ->get();

        // Jika kosong, coba fetch dari API
        if ($news->isEmpty()) {
            $result = $this->fetchFromAPI($countryCode);
            if ($result['success']) {
                $news = NewsCache::where('country_code', $countryCode)
                    ->orderBy('published_at', 'desc')
                    ->get();
            }
        }

        return view('news.index', compact('news', 'countries', 'countryCode'));
    }

    public function fetch($countryCode)
    {
        $result = $this->fetchFromAPI($countryCode);

        if ($result['success']) {
            return redirect('/news?country=' . $countryCode)
                ->with('success', $result['message']);
        } else {
            return redirect('/news?country=' . $countryCode)
                ->with('error', $result['message']);
        }
    }

    private function fetchFromAPI($countryCode)
    {
        $apiKey = env('GNEWS_API_KEY', '');

        // Jika tidak ada API Key, gunakan data dummy
        if (empty($apiKey)) {
            return $this->useDummyNews($countryCode);
        }

        try {
            $response = Http::timeout(15)->get("https://gnews.io/api/v4/search", [
                'q' => "logistics OR trade OR shipping OR economy {$countryCode}",
                'token' => $apiKey,
                'lang' => 'en',
                'max' => 10,
                'country' => $countryCode
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['articles']) && count($data['articles']) > 0) {
                    $country = Country::where('country_code', $countryCode)->first();
                    $countryId = $country ? $country->id : null;

                    NewsCache::where('country_code', $countryCode)->delete();

                    foreach ($data['articles'] as $article) {
                        NewsCache::create([
                            'country_id' => $countryId,
                            'country_code' => $countryCode,
                            'title' => $article['title'] ?? 'No Title',
                            'description' => $article['description'] ?? '',
                            'source' => $article['source']['name'] ?? 'Unknown',
                            'url' => $article['url'] ?? '#',
                            'published_at' => $article['publishedAt'] ?? now(),
                            'sentiment' => $this->analyzeSentiment(($article['description'] ?? '') . ' ' . ($article['title'] ?? ''))
                        ]);
                    }

                    return [
                        'success' => true,
                        'message' => count($data['articles']) . ' news fetched!'
                    ];
                }
            }

            // Jika API gagal, gunakan dummy
            return $this->useDummyNews($countryCode);

        } catch (\Exception $e) {
            // Jika error, gunakan dummy
            return $this->useDummyNews($countryCode);
        }
    }

    private function useDummyNews($countryCode)
    {
        $country = Country::where('country_code', $countryCode)->first();
        $countryId = $country ? $country->id : null;

        NewsCache::where('country_code', $countryCode)->delete();

        $dummyNews = [
            [
                'title' => "Trade Update for {$countryCode}",
                'description' => "Trade activities show stable growth in the region. Exports increased by 5% this quarter.",
                'source' => 'Trade News Network',
                'url' => '#',
                'sentiment' => 'positive'
            ],
            [
                'title' => "Economic Outlook for {$countryCode}",
                'description' => "Economic indicators remain positive with steady inflation. GDP growth projected at 4.2%.",
                'source' => 'Economic Times',
                'url' => '#',
                'sentiment' => 'positive'
            ],
            [
                'title' => "Logistics Update for {$countryCode}",
                'description' => "Logistics sector shows improvement in supply chain efficiency. New port developments underway.",
                'source' => 'Logistics Today',
                'url' => '#',
                'sentiment' => 'neutral'
            ],
            [
                'title' => "Supply Chain Challenges in {$countryCode}",
                'description' => "Supply chain faces challenges due to global uncertainty. Companies are diversifying suppliers.",
                'source' => 'Supply Chain Review',
                'url' => '#',
                'sentiment' => 'negative'
            ]
        ];

        foreach ($dummyNews as $item) {
            NewsCache::create([
                'country_id' => $countryId,
                'country_code' => $countryCode,
                'title' => $item['title'],
                'description' => $item['description'],
                'source' => $item['source'],
                'url' => $item['url'],
                'published_at' => now(),
                'sentiment' => $item['sentiment']
            ]);
        }

        return [
            'success' => true,
            'message' => count($dummyNews) . ' dummy news created! (API not available)'
        ];
    }

    private function analyzeSentiment($text)
    {
        $positiveWords = ['growth', 'increase', 'profit', 'stable', 'improve', 'recovery', 'success', 'opportunity', 'benefit', 'rise', 'boost', 'gain', 'positive', 'good', 'great', 'strong', 'safe', 'secure'];
        $negativeWords = ['war', 'crisis', 'inflation', 'delay', 'disaster', 'decline', 'drop', 'fall', 'loss', 'conflict', 'sanction', 'shortage', 'strike', 'storm', 'flood', 'crash', 'recession', 'unrest', 'protest', 'threat', 'damage', 'destroy', 'risk', 'danger', 'uncertainty'];

        $text = strtolower($text);
        $words = str_word_count($text, 1);

        $positive = 0;
        $negative = 0;

        foreach ($words as $word) {
            if (in_array($word, $positiveWords)) $positive++;
            if (in_array($word, $negativeWords)) $negative++;
        }

        if ($positive > $negative) return 'positive';
        if ($negative > $positive) return 'negative';
        return 'neutral';
    }
}