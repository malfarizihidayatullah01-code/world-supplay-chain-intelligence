<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsService;

class SyncNewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize news articles from News API';

    /**
     * Execute the console command.
     */
    public function handle(NewsService $newsService)
    {
        $this->info('Starting News API synchronization...');
        
        $result = $newsService->syncNews();
        
        if ($result['success']) {
            $this->info($result['message']);
            return Command::SUCCESS;
        } else {
            $this->error($result['message']);
            return Command::FAILURE;
        }
    }
}
