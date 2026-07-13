<?php

namespace App\Services;

use App\Models\Port;
use App\Models\Country;

class PortService
{
    public function searchPorts($query)
    {
        return Port::where('port_name', 'LIKE', "%{$query}%")
            ->orWhere('country_name', 'LIKE', "%{$query}%")
            ->with('country')
            ->get();
    }

    public function getPortsByCountry($countryCode)
    {
        $country = Country::where('country_code', $countryCode)->first();

        if (!$country) {
            return collect();
        }

        return Port::where('country_id', $country->id)->get();
    }

    public function getAllPorts()
    {
        return Port::with('country')->get();
    }

    public function getPortsWithCoordinates()
    {
        return Port::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('country')
            ->get();
    }
}