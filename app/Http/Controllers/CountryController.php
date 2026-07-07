<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $countries = Country::when($search, function ($query) use ($search) {

                $query->where('country_name', 'like', "%{$search}%");

            })
            ->orderBy('country_name')
            ->paginate(10);

        return view('countries.index', compact('countries', 'search'));
    }
}