<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\NewsCache;

class NewsCacheSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Country::all() as $country) {

            NewsCache::updateOrCreate(
                [
                    'country_id'=>$country->id,
                ],
                [
                    'country_code'=>$country->country_code,
                    'title'=>'Supply Chain Update '.$country->country_name,
                    'source'=>'NewsAPI',
                    'url'=>'https://example.com/news/'.$country->country_code,
                    'sentiment'=>collect([
                        'positive',
                        'neutral',
                        'negative'
                    ])->random(),
                    'published_at'=>now(),
                ]
            );

        }
    }
}