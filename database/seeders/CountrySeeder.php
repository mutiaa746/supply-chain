<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [

            [
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'capital' => 'Jakarta',
                'region' => 'Asia',
                'currency' => 'IDR',
                'language' => 'Indonesian',
                'flag' => 'https://flagcdn.com/w320/id.png',
            ],

            [
                'country_name' => 'China',
                'country_code' => 'CN',
                'capital' => 'Beijing',
                'region' => 'Asia',
                'currency' => 'CNY',
                'language' => 'Chinese',
                'flag' => 'https://flagcdn.com/w320/cn.png',
            ],

            [
                'country_name' => 'Japan',
                'country_code' => 'JP',
                'capital' => 'Tokyo',
                'region' => 'Asia',
                'currency' => 'JPY',
                'language' => 'Japanese',
                'flag' => 'https://flagcdn.com/w320/jp.png',
            ],

            [
                'country_name' => 'South Korea',
                'country_code' => 'KR',
                'capital' => 'Seoul',
                'region' => 'Asia',
                'currency' => 'KRW',
                'language' => 'Korean',
                'flag' => 'https://flagcdn.com/w320/kr.png',
            ],

            [
                'country_name' => 'Singapore',
                'country_code' => 'SG',
                'capital' => 'Singapore',
                'region' => 'Asia',
                'currency' => 'SGD',
                'language' => 'English',
                'flag' => 'https://flagcdn.com/w320/sg.png',
            ],

            [
                'country_name' => 'Malaysia',
                'country_code' => 'MY',
                'capital' => 'Kuala Lumpur',
                'region' => 'Asia',
                'currency' => 'MYR',
                'language' => 'Malay',
                'flag' => 'https://flagcdn.com/w320/my.png',
            ],

            [
                'country_name' => 'Thailand',
                'country_code' => 'TH',
                'capital' => 'Bangkok',
                'region' => 'Asia',
                'currency' => 'THB',
                'language' => 'Thai',
                'flag' => 'https://flagcdn.com/w320/th.png',
            ],

            [
                'country_name' => 'Vietnam',
                'country_code' => 'VN',
                'capital' => 'Hanoi',
                'region' => 'Asia',
                'currency' => 'VND',
                'language' => 'Vietnamese',
                'flag' => 'https://flagcdn.com/w320/vn.png',
            ],

            [
                'country_name' => 'India',
                'country_code' => 'IN',
                'capital' => 'New Delhi',
                'region' => 'Asia',
                'currency' => 'INR',
                'language' => 'Hindi',
                'flag' => 'https://flagcdn.com/w320/in.png',
            ],

            [
                'country_name' => 'United States',
                'country_code' => 'US',
                'capital' => 'Washington D.C.',
                'region' => 'North America',
                'currency' => 'USD',
                'language' => 'English',
                'flag' => 'https://flagcdn.com/w320/us.png',
            ],

        ];

        foreach ($countries as $country) {

            Country::updateOrCreate(
                [
                    'country_code' => $country['country_code']
                ],
                [
                    'country_name' => $country['country_name'],
                    'capital'      => $country['capital'],
                    'region'       => $country['region'],
                    'currency'     => $country['currency'],
                    'language'     => $country['language'],
                    'flag'         => $country['flag'],
                ]
            );

        }
    }
}