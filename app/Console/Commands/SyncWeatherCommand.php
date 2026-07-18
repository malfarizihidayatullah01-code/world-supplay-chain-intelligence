<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WeatherService;

class SyncWeatherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:weather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize weather data from Open-Meteo for all active countries';

    /**
     * Execute the console command.
     */
    public function handle(WeatherService $weatherService): int
    {
        $this->info('Starting Weather API synchronization...');

        // Determine total eligible countries for the progress bar.
        // We pass a callback to WeatherService so it can advance the bar
        // without coupling itself to Console output.
        $progressBar = null;

        $result = $weatherService->syncWeather(
            function (int $processed, int $total) use (&$progressBar) {
                // Lazy-initialise the progress bar on the first tick
                if ($progressBar === null) {
                    $progressBar = $this->output->createProgressBar($total);
                    $progressBar->setFormat(
                        ' %current%/%max% [%bar%] %percent:3s%% — %message%'
                    );
                    $progressBar->start();
                }

                $progressBar->advance();
            }
        );

        if ($progressBar !== null) {
            $progressBar->finish();
            $this->newLine();
        }

        if ($result['success']) {
            $this->info($result['message']);
            return Command::SUCCESS;
        }

        $this->error($result['message']);
        return Command::FAILURE;
    }
}
