<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Port;

class PortController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $ports = Port::with('country')

            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('port_name', 'like', "%{$search}%")
                      ->orWhere('country_name', 'like', "%{$search}%");

                });

            })

            ->orderBy('port_name')

            ->paginate(10);

        return view('ports.index', compact('ports', 'search'));
    }
}