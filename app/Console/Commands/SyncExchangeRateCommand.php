<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ExchangeRateService;

class SyncExchangeRateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:exchange-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize exchange rates from Exchange Rate API';

    /**
     * Execute the console command.
     */
    public function handle(ExchangeRateService $exchangeRateService)
    {
        $this->info('Starting Exchange Rate API synchronization...');
        
        $result = $exchangeRateService->syncExchangeRates();
        
        if ($result['success']) {
            $this->info($result['message']);
            return Command::SUCCESS;
        } else {
            $this->error($result['message']);
            return Command::FAILURE;
        }
    }
}
