<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsService;

class NewsSync extends Command
{
    protected $signature = 'news:sync';

    protected $description = 'Sync news from GNews API';

    public function handle(NewsService $service)
    {
        $this->info('Mengambil berita...');

        $service->sync();

        $this->info('Berhasil menyimpan berita.');

        return self::SUCCESS;
    }
}