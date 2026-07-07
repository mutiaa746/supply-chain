<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExchangeRate;

class ExchangeRateController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $exchangeRates = ExchangeRate::with('country')

            ->when($search, function ($query) use ($search) {

                $query->whereHas('country', function ($q) use ($search) {

                    $q->where('country_name', 'like', "%{$search}%");

                });

            })

            ->orderByDesc('id')

            ->paginate(10);

        return view('exchange.index', compact('exchangeRates', 'search'));
    }
}