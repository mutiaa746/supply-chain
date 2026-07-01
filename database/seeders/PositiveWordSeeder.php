<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PositiveWord;

class PositiveWordSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'stable',
            'growth',
            'efficient',
            'improve',
            'safe',
            'increase',
            'success'
        ] as $word){

            PositiveWord::create([
                'word'=>$word
            ]);

        }
    }
}