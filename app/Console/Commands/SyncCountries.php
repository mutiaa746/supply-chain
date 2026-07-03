<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CountryService;

class SyncCountries extends Command
{
    protected $signature = 'countries:sync';

    protected $description = 'Sync countries from REST Countries API';

    public function handle(CountryService $countryService)
    {
        $this->info('Mengambil data negara...');

        if ($countryService->sync()) {
            $this->info('Berhasil menyimpan data negara.');
        } else {
            $this->error('Gagal mengambil data.');
        }

        return Command::SUCCESS;
    }
}