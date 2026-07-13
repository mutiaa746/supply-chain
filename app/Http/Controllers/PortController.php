<?php

namespace App\Http\Controllers;

use App\Models\Port;
use Illuminate\Http\Request;

class PortController extends Controller
{
    public function index()
    {
        $ports = Port::with('country')->get();
        return view('ports.index', compact('ports'));
    }

    public function map()
    {
        // Ambil port yang punya koordinat
        $ports = Port::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('country')
            ->limit(100)
            ->get();
        
        // Jika kosong, buat data dummy
        if ($ports->isEmpty()) {
            $ports = collect([
                (object) [
                    'port_name' => 'Port of Singapore',
                    'latitude' => 1.27,
                    'longitude' => 103.84,
                    'country' => (object) ['country_name' => 'Singapore'],
                    'status' => 'Operational',
                    'harbor_type' => 'Deepwater',
                    'harbor_size' => 'Large'
                ],
                (object) [
                    'port_name' => 'Port of Jakarta',
                    'latitude' => -6.10,
                    'longitude' => 106.89,
                    'country' => (object) ['country_name' => 'Indonesia'],
                    'status' => 'Operational',
                    'harbor_type' => 'Deepwater',
                    'harbor_size' => 'Large'
                ],
                (object) [
                    'port_name' => 'Port of Bangkok',
                    'latitude' => 13.55,
                    'longitude' => 100.60,
                    'country' => (object) ['country_name' => 'Thailand'],
                    'status' => 'Operational',
                    'harbor_type' => 'River',
                    'harbor_size' => 'Medium'
                ],
                (object) [
                    'port_name' => 'Port of Tokyo',
                    'latitude' => 35.65,
                    'longitude' => 139.75,
                    'country' => (object) ['country_name' => 'Japan'],
                    'status' => 'Operational',
                    'harbor_type' => 'Deepwater',
                    'harbor_size' => 'Large'
                ],
                (object) [
                    'port_name' => 'Port of London',
                    'latitude' => 51.50,
                    'longitude' => 0.00,
                    'country' => (object) ['country_name' => 'United Kingdom'],
                    'status' => 'Operational',
                    'harbor_type' => 'River',
                    'harbor_size' => 'Large'
                ],
                (object) [
                    'port_name' => 'Port of New York',
                    'latitude' => 40.70,
                    'longitude' => -74.00,
                    'country' => (object) ['country_name' => 'United States'],
                    'status' => 'Operational',
                    'harbor_type' => 'Deepwater',
                    'harbor_size' => 'Large'
                ],
                (object) [
                    'port_name' => 'Port of Sydney',
                    'latitude' => -33.85,
                    'longitude' => 151.20,
                    'country' => (object) ['country_name' => 'Australia'],
                    'status' => 'Operational',
                    'harbor_type' => 'Deepwater',
                    'harbor_size' => 'Large'
                ],
                (object) [
                    'port_name' => 'Port of Shanghai',
                    'latitude' => 31.23,
                    'longitude' => 121.47,
                    'country' => (object) ['country_name' => 'China'],
                    'status' => 'Operational',
                    'harbor_type' => 'Deepwater',
                    'harbor_size' => 'Large'
                ],
                (object) [
                    'port_name' => 'Port of Rotterdam',
                    'latitude' => 51.92,
                    'longitude' => 4.48,
                    'country' => (object) ['country_name' => 'Netherlands'],
                    'status' => 'Operational',
                    'harbor_type' => 'Deepwater',
                    'harbor_size' => 'Large'
                ],
                (object) [
                    'port_name' => 'Port of Dubai',
                    'latitude' => 25.20,
                    'longitude' => 55.27,
                    'country' => (object) ['country_name' => 'UAE'],
                    'status' => 'Operational',
                    'harbor_type' => 'Deepwater',
                    'harbor_size' => 'Large'
                ],
            ]);
        }
        
        return view('ports.map', compact('ports'));
    }
}