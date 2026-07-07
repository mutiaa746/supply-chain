<?php

namespace App\Http\Controllers;

use App\Models\Port;
use App\Models\Country;

class PortMapController extends Controller
{
    public function index()
    {
        $ports = Port::with('country')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $totalPorts = Port::count();

        $totalCountries = Country::count();

        $activePorts = Port::where('status', 'Active')->count();

        if ($activePorts == 0) {
            $activePorts = Port::count();
        }

        $lastUpdate = Port::max('updated_at');

        return view('ports.map', compact(
            'ports',
            'totalPorts',
            'totalCountries',
            'activePorts',
            'lastUpdate'
        ));
    }
}