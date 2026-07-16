<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        return view('tracking.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string'
        ]);

        $trackingNumber = $request->tracking_number;

        // Data dummy tracking
        $statuses = ['In Transit', 'Processing', 'Delivered', 'On Hold', 'Customs Clearance'];
        $origins = ['Jakarta, Indonesia', 'Singapore', 'Shanghai, China', 'Tokyo, Japan', 'Busan, South Korea'];
        $destinations = ['Singapore', 'Jakarta, Indonesia', 'Bangkok, Thailand', 'Kuala Lumpur, Malaysia', 'Ho Chi Minh, Vietnam'];

        $trackingData = [
            'number' => $trackingNumber,
            'origin' => $origins[array_rand($origins)],
            'destination' => $destinations[array_rand($destinations)],
            'status' => $statuses[array_rand($statuses)],
            'last_update' => now()->format('d M Y H:i'),
            'estimated_delivery' => now()->addDays(rand(1, 7))->format('d M Y'),
            'weight' => rand(1, 50) . ' kg',
            'package_type' => ['Box', 'Pallet', 'Container', 'Envelope'][rand(0, 3)],
            'history' => [
                ['date' => now()->subDays(3)->format('d M Y H:i'), 'location' => 'Origin Hub', 'status' => 'Picked Up'],
                ['date' => now()->subDays(2)->format('d M Y H:i'), 'location' => 'Distribution Center', 'status' => 'Processing'],
                ['date' => now()->subDays(1)->format('d M Y H:i'), 'location' => 'Transit Hub', 'status' => 'In Transit'],
                ['date' => now()->format('d M Y H:i'), 'location' => 'Destination Hub', 'status' => $statuses[array_rand($statuses)]],
            ]
        ];

        return redirect()->route('tracking')->with('tracking_result', $trackingData);
    }
}