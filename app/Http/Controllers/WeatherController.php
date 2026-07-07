<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeatherData;

class WeatherController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $weather = WeatherData::with('country')

            ->when($search, function ($query) use ($search) {

                $query->whereHas('country', function ($q) use ($search) {

                    $q->where('country_name', 'like', "%{$search}%");

                });

            })

            ->latest()

            ->paginate(10);

        return view('weather.index', compact('weather', 'search'));
    }
}