<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WeatherService;

class WeatherSync extends Command
{
    /**
     * Nama command artisan
     */
    protected $signature = 'weather:sync';

    /**
     * Deskripsi command
     */
    protected $description = 'Sync weather data from Open-Meteo API';

    /**
     * Execute the console command.
     */
    public function handle(WeatherService $weatherService)
    {
        $this->info('Mengambil data cuaca...');

        if ($weatherService->sync()) {

            $this->info('Data cuaca berhasil disimpan.');

        } else {

            $this->error('Gagal mengambil data cuaca.');

        }

        return Command::SUCCESS;
    }
}