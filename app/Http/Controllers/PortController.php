<?php

namespace App\Http\Controllers;

use App\Models\Port;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PortController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua country untuk filter
        $countries = Country::orderBy('country_name')->get();

        // Query port
        $query = Port::with('country');

        // Filter by country
        $selectedCountry = null;
        if ($request->has('country') && $request->country != '') {
            $query->where('country_id', $request->country);
            $selectedCountry = Country::find($request->country);
        }

        // ========== TAMBAHKAN SEARCH ==========
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('port_name', 'LIKE', "%{$search}%");
        }
        // ========== SAMPAI SINI ==========

        $ports = $query->get();

        return view('ports.index', compact('ports', 'countries', 'selectedCountry'));
    }

    public function fetchPorts()
    {
        try {
            $url = 'https://services9.arcgis.com/j1CY4yzWfwptbTWN/ArcGIS/rest/services/WorldPortIndex_WFL1/FeatureServer/0/query';
            
            $response = Http::timeout(60)->get($url, [
                'where' => '1=1',
                'outFields' => '*',
                'f' => 'geojson',
                'outSR' => '4326',
                'resultRecordCount' => 3000
            ]);

            if (!$response->successful()) {
                return redirect()->route('ports')->with('error', 'Gagal mengambil data dari API');
            }

            $data = $response->json();
            $features = $data['features'] ?? [];

            if (empty($features)) {
                return redirect()->route('ports')->with('error', 'Data kosong dari API');
            }

            // Ambil country OTH (Other) untuk fallback
            $otherCountry = Country::where('country_code', 'OTH')->first();
            if (!$otherCountry) {
                $otherCountry = Country::create([
                    'country_name' => 'Other / Unknown',
                    'country_code' => 'OTH',
                    'region' => 'Unknown',
                ]);
            }

            $count = 0;

            foreach ($features as $feature) {
                $props = $feature['properties'];
                $coords = $feature['geometry']['coordinates'] ?? [0, 0];

                $portName = $props['PORT_NAME'] ?? 'Unknown';
                $countryCode = $props['COUNTRY'] ?? null;
                
                // Cari country berdasarkan kode
                $country = null;
                if ($countryCode) {
                    $country = Country::where('country_code', $countryCode)->first();
                }

                // Jika tidak ditemukan, pakai OTH
                if (!$country) {
                    $country = $otherCountry;
                }

                // Cegah duplikasi data
                $port = Port::where('port_name', $portName)
                    ->where('country_id', $country->id)
                    ->first();

                if ($port) {
                    $port->update([
                        'latitude' => $coords[1] ?? null,
                        'longitude' => $coords[0] ?? null,
                        'harbor_size' => $props['HARBOR_SIZE'] ?? null,
                        'harbor_type' => $props['HARBOR_TYPE'] ?? null,
                        'status' => $props['STATUS'] ?? 'Unknown',
                        'country_name' => $country->country_name,
                    ]);
                } else {
                    Port::create([
                        'port_name' => $portName,
                        'country_id' => $country->id,
                        'country_name' => $country->country_name,
                        'latitude' => $coords[1] ?? null,
                        'longitude' => $coords[0] ?? null,
                        'harbor_size' => $props['HARBOR_SIZE'] ?? null,
                        'harbor_type' => $props['HARBOR_TYPE'] ?? null,
                        'status' => $props['STATUS'] ?? 'Unknown',
                    ]);
                    $count++;
                }
            }

            return redirect()->route('ports')->with('success', "✅ Berhasil menyimpan $count port dari World Port Index!");

        } catch (\Exception $e) {
            return redirect()->route('ports')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}