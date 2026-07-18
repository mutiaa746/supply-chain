<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        return view('compare.index', compact('countries'));
    }

    public function result(Request $request)
    {
        $country1Name = $request->get('country1_search');
        $country2Name = $request->get('country2_search');

        $country1 = Country::with('riskScores')
            ->where('country_name', 'LIKE', "%{$country1Name}%")
            ->first();

        $country2 = Country::with('riskScores')
            ->where('country_name', 'LIKE', "%{$country2Name}%")
            ->first();

        if (!$country1 || !$country2) {
            return redirect()->route('compare')->with('error', 'Negara tidak ditemukan!');
        }

        // Ambil kurs dari database (data dari ExchangeRate API)
        $currency1 = 1;
        $currency2 = 1;

        if ($country1->currency_code) {
            $rate1 = ExchangeRate::where('target_currency', $country1->currency_code)->first();
            if ($rate1) $currency1 = $rate1->rate;
        }

        if ($country2->currency_code) {
            $rate2 = ExchangeRate::where('target_currency', $country2->currency_code)->first();
            if ($rate2) $currency2 = $rate2->rate;
        }

        return view('compare.result', compact(
            'country1',
            'country2',
            'currency1',
            'currency2'
        ));
    }
}