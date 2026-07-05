<?php

namespace App\Services;

use App\Models\Country;
use App\Models\Port;

class PortService
{
    public function import()
    {
        $file = storage_path('app/ports/UpdatedPub150.csv');

        if (!file_exists($file)) {
            throw new \Exception("File CSV tidak ditemukan.");
        }

        $handle = fopen($file, 'r');

        $header = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {

            if (count($header) != count($row)) {
                continue;
            }

            $data = array_combine($header, $row);

            $countryName = trim($data['Country Code']);

            $country = Country::whereRaw(
                'LOWER(country_name) = ?',
                [strtolower($countryName)]
            )->first();

            // Jika negara tidak ditemukan, lewati saja
            if (!$country) {
                continue;
            }

            Port::updateOrCreate(

                [
                    'port_name' => trim($data['Main Port Name']),
                    'country_id' => $country->id,
                ],

                [
                    'country_name' => $countryName,
                    'latitude' => !empty($data['Latitude']) ? $data['Latitude'] : null,
                    'longitude' => !empty($data['Longitude']) ? $data['Longitude'] : null,
                    'harbor_size' => !empty($data['Harbor Size']) ? $data['Harbor Size'] : null,
                    'harbor_type' => !empty($data['Harbor Type']) ? $data['Harbor Type'] : null,
                    'status' => 'Active',
                ]
            );
        }

        fclose($handle);
    }
}