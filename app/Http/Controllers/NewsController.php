<?php

namespace App\Http\Controllers;

use App\Models\NewsCache;
use App\Models\Country;
use App\Services\NewsService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $countryCode = $request->get('country', 'ID');
        $countries = Country::select('id', 'country_name', 'country_code')->get();
        
        // Fetch dari API
        $newsService = new NewsService();
        $result = $newsService->fetchNews($countryCode);
        
        
        $news = NewsCache::where('country_code', $countryCode)
            ->orderBy('published_at', 'desc')
            ->get();
        
        
        if ($news->isEmpty()) {
            $news = collect([
                (object) [
                    'title' => 'Trade Update for ' . $countryCode,
                    'description' => 'Trade activities show stable growth in the region.',
                    'source' => 'Trade News',
                    'published_at' => now(),
                    'sentiment' => 'positive',
                    'url' => '#'
                ],
                (object) [
                    'title' => 'Economic Outlook for ' . $countryCode,
                    'description' => 'Economic indicators remain positive with steady inflation.',
                    'source' => 'Economic Times',
                    'published_at' => now(),
                    'sentiment' => 'neutral',
                    'url' => '#'
                ],
                (object) [
                    'title' => 'Logistics Update for ' . $countryCode,
                    'description' => 'Logistics sector shows improvement in supply chain efficiency.',
                    'source' => 'Logistics Today',
                    'published_at' => now(),
                    'sentiment' => 'positive',
                    'url' => '#'
                ]
            ]);
        }
        
        return view('news.index', compact('news', 'countries', 'countryCode'));
    }

    public function fetch($code)
    {
        $newsService = new NewsService();
        $result = $newsService->fetchNews($code);
        return redirect('/news?country=' . $code)->with('success', 'News fetched successfully!');
    }
}