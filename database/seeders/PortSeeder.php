<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Port;

class PortSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Country::all() as $country) {

            Port::updateOrCreate(
                [
                    'country_id' => $country->id,
                ],
                [
                    'port_name' => 'Main Port '.$country->country_code,
                    'latitude'  => rand(-90,90),
                    'longitude' => rand(-180,180),
                    'status'    => collect([
                        'Normal',
                        'Busy',
                        'Closed'
                    ])->random(),
                ]
            );

        }
    }
}