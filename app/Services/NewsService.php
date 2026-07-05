<?php

namespace App\Services;

use App\Models\Country;
use App\Models\NewsCache;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class NewsService
{
    public function sync(): bool
    {
        $apiKey = env('GNEWS_API_KEY');

        if (!$apiKey) {
            throw new \Exception('GNEWS_API_KEY belum diatur di file .env');
        }

        /*
        |--------------------------------------------------------------------------
        | Negara yang dipantau
        |--------------------------------------------------------------------------
        | Mengikuti studi kasus Supply Chain.
        | Tidak mengambil 250 negara karena GNews Free memiliki limit request.
        |--------------------------------------------------------------------------
        */
        $countries = Country::whereIn('country_name', [
            'Indonesia',
            'China',
            'United States',
            'Japan',
            'Germany',
            'Singapore',
            'Malaysia',
            'Australia',
            'India',
            'South Korea',
        ])->get();

        foreach ($countries as $country) {

            $this->syncCountryNews($country, $apiKey);

            // Menghindari rate limit
            usleep(500000);
        }

        return true;
    }

    private function syncCountryNews(Country $country, string $apiKey): void
    {
        $query = sprintf(
            '"%s" AND (economy OR logistics OR shipping OR trade)',
            $country->country_name
        );

        $response = Http::timeout(60)
            ->retry(3, 1000)
            ->get(
                'https://gnews.io/api/v4/search',
                [
                    'q' => $query,
                    'lang' => 'en',
                    'max' => 5,
                    'sortby' => 'publishedAt',
                    'apikey' => $apiKey,
                ]
            );

        if (!$response->successful()) {

            echo "Gagal mengambil berita {$country->country_name}\n";

            return;
        }

        $articles = $response->json()['articles'] ?? [];

        foreach ($articles as $article) {

            NewsCache::updateOrCreate(

                [
                    'url' => $article['url']
                ],

                [
                    'country_id' => $country->id,
                    'title' => $article['title'] ?? '',
                    'source' => $article['source']['name'] ?? 'Unknown',
                    'sentiment' => null,
                    'published_at' => isset($article['publishedAt'])
    ? Carbon::parse($article['publishedAt'])
    : now(),
                ]
            );
        }

        echo "✓ {$country->country_name} : " . count($articles) . " berita\n";
    }
}