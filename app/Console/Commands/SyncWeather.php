<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WeatherService;

class SyncWeather extends Command
{
    protected $signature = 'sync:weather';
    protected $description = 'Sync weather data for all active countries';

    public function handle(WeatherService $weatherService)
    {
        $this->info('Starting weather sync...');
        $result = $weatherService->syncWeather();
        
        if ($result['success']) {
            $this->info($result['message']);
        } else {
            $this->error($result['message']);
        }
    }
}
