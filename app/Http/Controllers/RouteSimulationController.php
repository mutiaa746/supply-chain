<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Port;
use App\Models\ExchangeRate;
use App\Models\NewsCache;
use App\Models\WeatherData;
use Illuminate\Http\Request;

class RouteSimulationController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('country_name')->get();
        return view('route-simulation.index', compact('countries'));
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'country1' => 'required|exists:countries,id',
            'country2' => 'required|exists:countries,id',
            'transport' => 'required|in:plane,ship'
        ]);

        $country1 = Country::with('riskScores')->find($request->country1);
        $country2 = Country::with('riskScores')->find($request->country2);

        if (!$country1->latitude || !$country1->longitude || !$country2->latitude || !$country2->longitude) {
            return redirect()->route('route-simulation')
                ->with('error', 'Salah satu negara tidak memiliki koordinat!');
        }

        // ========== HITUNG JARAK ==========
        $distance = $this->calculateDistance(
            $country1->latitude, $country1->longitude,
            $country2->latitude, $country2->longitude
        );

        $distanceKm = round($distance, 0);
        $distanceMiles = round($distance * 0.621371, 0);
        $distanceNautical = round($distance * 0.539957, 0);

        // ========== ESTIMASI WAKTU ==========
        $transport = $request->transport;
        if ($transport == 'plane') {
            $speed = 800; // km/h
            $timeHours = round($distanceKm / $speed, 1);
            $timeDays = round($timeHours / 24, 1);
            $timeText = $timeHours . ' jam (' . $timeDays . ' hari)';
            $transportIcon = '✈️';
            $transportName = 'Pesawat';
            $symbol = 'plane';
        } else {
            $speed = 30; // km/h
            $timeHours = round($distanceKm / $speed, 1);
            $timeDays = round($timeHours / 24, 1);
            $timeText = $timeHours . ' jam (' . $timeDays . ' hari)';
            $transportIcon = '🚢';
            $transportName = 'Kapal';
            $symbol = 'ship';
        }

        // ========== RISK MONITORING ==========
        $weather1 = WeatherData::where('country_id', $country1->id)->first();
        $weather2 = WeatherData::where('country_id', $country2->id)->first();

        $weatherRisk1 = $this->getWeatherRisk($weather1);
        $weatherRisk2 = $this->getWeatherRisk($weather2);

        $currency1 = $this->getCurrencyRisk($country1->currency_code);
        $currency2 = $this->getCurrencyRisk($country2->currency_code);

        $inflationRisk1 = $this->getInflationRisk($country1->inflation);
        $inflationRisk2 = $this->getInflationRisk($country2->inflation);

        $newsRisk1 = $this->getNewsRisk($country1->country_code);
        $newsRisk2 = $this->getNewsRisk($country2->country_code);

        $totalRisk1 = round(($weatherRisk1 + $currency1 + $inflationRisk1 + $newsRisk1) / 4, 1);
        $totalRisk2 = round(($weatherRisk2 + $currency2 + $inflationRisk2 + $newsRisk2) / 4, 1);

        $riskLevel1 = $this->getRiskLevel($totalRisk1);
        $riskLevel2 = $this->getRiskLevel($totalRisk2);

        // ========== PELABUHAN TERDEKAT ==========
        $nearestPorts = $this->getNearestPorts($country1->latitude, $country1->longitude, 3);

        // ========== ROUTE ==========
        $route = [
            [$country1->latitude, $country1->longitude],
            [$country2->latitude, $country2->longitude],
        ];

        // Titik tengah untuk garis melengkung
        $midLat = ($country1->latitude + $country2->latitude) / 2;
        $midLng = ($country1->longitude + $country2->longitude) / 2;
        $offset = 5;
        $routeCurved = [
            [$country1->latitude, $country1->longitude],
            [$midLat + $offset, $midLng],
            [$country2->latitude, $country2->longitude],
        ];

        return view('route-simulation.result', compact(
            'country1', 'country2',
            'distanceKm', 'distanceMiles', 'distanceNautical',
            'timeText', 'transport', 'transportIcon', 'transportName', 'symbol',
            'weather1', 'weather2',
            'weatherRisk1', 'weatherRisk2',
            'currency1', 'currency2',
            'inflationRisk1', 'inflationRisk2',
            'newsRisk1', 'newsRisk2',
            'totalRisk1', 'totalRisk2',
            'riskLevel1', 'riskLevel2',
            'nearestPorts',
            'route', 'routeCurved'
        ));
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        $a = sin($dlat/2) * sin($dlat/2) + cos($lat1) * cos($lat2) * sin($dlon/2) * sin($dlon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earthRadius * $c;
    }

    private function getWeatherRisk($weather)
    {
        if (!$weather) return 50;
        $temp = $weather->temperature ?? 0;
        $wind = $weather->wind_speed ?? 0;
        if ($temp > 35 || $wind > 50) return 80;
        if ($temp > 30 || $wind > 30) return 50;
        return 20;
    }

    private function getCurrencyRisk($currencyCode)
    {
        if (!$currencyCode) return 30;
        $rate = ExchangeRate::where('target_currency', $currencyCode)->first();
        if (!$rate) return 30;
        if ($rate->rate < 0.5 || $rate->rate > 15000) return 80;
        if ($rate->rate < 1 || $rate->rate > 10000) return 50;
        return 20;
    }

    private function getInflationRisk($inflation)
    {
        if (!$inflation) return 30;
        if ($inflation > 10) return 80;
        if ($inflation > 7) return 60;
        if ($inflation > 5) return 40;
        return 20;
    }

    private function getNewsRisk($countryCode)
    {
        $news = NewsCache::where('country_code', $countryCode)
            ->where('sentiment', 'negative')
            ->count();
        if ($news > 5) return 80;
        if ($news > 2) return 50;
        return 20;
    }

    private function getRiskLevel($score)
    {
        if ($score >= 70) return 'High';
        if ($score >= 40) return 'Medium';
        return 'Low';
    }

    private function getNearestPorts($lat, $lng, $limit = 3)
    {
        $ports = Port::whereNotNull('latitude')->whereNotNull('longitude')->get();
        foreach ($ports as $port) {
            $port->distance = $this->calculateDistance($lat, $lng, $port->latitude, $port->longitude);
        }
        return $ports->sortBy('distance')->take($limit);
    }
}