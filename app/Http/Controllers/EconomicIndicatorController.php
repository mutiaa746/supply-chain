<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EconomicIndicator;

class EconomicIndicatorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $economics = EconomicIndicator::with('country')

            ->when($search, function ($query) use ($search) {

                $query->whereHas('country', function ($q) use ($search) {

                    $q->where('country_name', 'like', "%{$search}%");

                });

            })

            ->orderByDesc('id')

            ->paginate(10);

        return view('economic.index', compact('economics', 'search'));
    }
}