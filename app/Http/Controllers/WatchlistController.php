<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function index()
    {
        $watchlists = Watchlist::with('country')
            ->where('user_id', Auth::id())
            ->get();

        return view('watchlist.index', compact('watchlists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id'
        ]);

        $exists = Watchlist::where('user_id', Auth::id())
            ->where('country_id', $request->country_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Negara sudah ada di watchlist!');
        }

        Watchlist::create([
            'user_id' => Auth::id(),
            'country_id' => $request->country_id
        ]);

        return redirect()->back()->with('success', '✅ Negara berhasil ditambahkan ke watchlist!');
    }

    public function destroy($id)
    {
        $watchlist = Watchlist::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (!$watchlist) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $watchlist->delete();

        return redirect()->back()->with('success', '✅ Negara dihapus dari watchlist!');
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id'
        ]);

        $exists = Watchlist::where('user_id', Auth::id())
            ->where('country_id', $request->country_id)
            ->exists();

        if ($exists) {
            Watchlist::where('user_id', Auth::id())
                ->where('country_id', $request->country_id)
                ->delete();
            return redirect()->back()->with('success', '❌ Dihapus dari watchlist!');
        } else {
            Watchlist::create([
                'user_id' => Auth::id(),
                'country_id' => $request->country_id
            ]);
            return redirect()->back()->with('success', '✅ Ditambahkan ke watchlist!');
        }
    }
}