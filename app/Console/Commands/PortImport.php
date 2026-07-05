<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PortService;

class PortImport extends Command
{
    protected $signature = 'port:import';

    protected $description = 'Import World Port Index CSV';

    public function handle(PortService $service)
    {
        $this->info('Mengimpor data pelabuhan...');

        $service->import();

        $this->info('Berhasil mengimpor data pelabuhan.');

        return self::SUCCESS;
    }
}