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
        // Ambil SEMUA port yang punya koordinat
        $ports = Port::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('country')
            ->get();  // Hapus limit(500)
        
        return view('ports.map', compact('ports'));
    }
}