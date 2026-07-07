<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsCache;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $news = NewsCache::with('country')

            ->when($search, function ($query) use ($search) {

                $query->whereHas('country', function ($q) use ($search) {

                    $q->where('country_name', 'like', "%{$search}%");

                });

            })

            ->orderByDesc('published_at')

            ->paginate(10);

        return view('news.index', compact('news', 'search'));
    }
}