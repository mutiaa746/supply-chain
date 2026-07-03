<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ExchangeRateService;

class ExchangeRateSync extends Command
{
    protected $signature = 'exchange:sync';

    protected $description = 'Sync exchange rates from ExchangeRate API';

    public function handle(ExchangeRateService $exchangeRateService)
    {
        $this->info('Mengambil data nilai tukar...');

        if ($exchangeRateService->sync()) {
            $this->info('Berhasil menyimpan data nilai tukar.');
        } else {
            $this->error('Gagal mengambil data nilai tukar.');
        }

        return Command::SUCCESS;
    }
}