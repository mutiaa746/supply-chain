<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NegativeWord;

class NegativeWordSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'delay',
            'crisis',
            'war',
            'flood',
            'earthquake',
            'inflation',
            'shortage'
        ] as $word){

            NegativeWord::create([
                'word'=>$word
            ]);

        }
    }
}