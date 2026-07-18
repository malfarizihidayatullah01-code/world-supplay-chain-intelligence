<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsService;

class SyncNews extends Command
{
    protected $signature = 'sync:news';
    protected $description = 'Sync news articles for all active countries';

    public function handle(NewsService $newsService)
    {
        $this->info('Starting news sync...');
        $result = $newsService->syncNews();
        
        if ($result['success']) {
            $this->info($result['message']);
        } else {
            $this->error($result['message']);
        }
    }
}
