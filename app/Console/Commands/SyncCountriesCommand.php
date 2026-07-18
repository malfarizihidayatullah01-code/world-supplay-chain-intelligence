<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RestCountriesService;

class SyncCountriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize countries data from REST Countries API';

    /**
     * Execute the console command.
     */
    public function handle(RestCountriesService $restCountriesService)
    {
        $this->info('Starting REST Countries synchronization...');
        
        $result = $restCountriesService->syncCountries();
        
        if ($result['success']) {
            $this->info($result['message']);
            return Command::SUCCESS;
        } else {
            $this->error($result['message']);
            return Command::FAILURE;
        }
    }
}
