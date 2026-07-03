<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EconomicIndicatorService;

class EconomicIndicatorSync extends Command
{
    protected $signature = 'economic:sync';

    protected $description = 'Sync economic indicators from World Bank API';

    public function handle(EconomicIndicatorService $service)
    {
        $this->info('Mengambil data indikator ekonomi...');

        if ($service->sync()) {
            $this->info('Berhasil menyimpan data indikator ekonomi.');
        } else {
            $this->error('Gagal mengambil data indikator ekonomi.');
        }

        return Command::SUCCESS;
    }
}