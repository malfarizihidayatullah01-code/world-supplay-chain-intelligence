<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ExchangeRateService;

class SyncExchangeRates extends Command
{
    protected $signature = 'sync:exchange-rates';
    protected $description = 'Sync exchange rates for all active countries';

    public function handle(ExchangeRateService $exchangeRateService)
    {
        $this->info('Starting exchange rates sync...');
        $result = $exchangeRateService->syncExchangeRates();
        
        if ($result['success']) {
            $this->info($result['message']);
        } else {
            $this->error($result['message']);
        }
    }
}
