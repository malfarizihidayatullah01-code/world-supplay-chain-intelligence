<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Log;

class SyncWeatherJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Only attempt the job once — no retries.
     * Sync is idempotent; if it fails the user can press Sync again.
     *
     * @var int
     */
    public int $tries = 1;

    /**
     * Maximum seconds the job may run before the queue worker kills it.
     * Set higher than DB_QUEUE_RETRY_AFTER (950s) so the worker never
     * prematurely marks the job as timed-out and retries it.
     *
     * @var int
     */
    public int $timeout = 900;

    /**
     * Mark the job as failed (not retried) when it exceeds $timeout.
     * Requires Laravel 10+.
     *
     * @var bool
     */
    public bool $failOnTimeout = true;

    /**
     * Execute the job.
     */
    public function handle(WeatherService $weatherService): void
    {
        Log::info('[SyncWeatherJob] Job started. Timeout set to ' . $this->timeout . 's.');

        $result = $weatherService->syncWeather();

        if ($result['success']) {
            Log::info('[SyncWeatherJob] Completed successfully: ' . $result['message']);
        } else {
            Log::error('[SyncWeatherJob] Finished with errors: ' . $result['message']);
        }
    }

    /**
     * Handle a job failure.
     * Called by the queue worker when the job fails (e.g. hard timeout).
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('[SyncWeatherJob] Job failed: ' . $exception->getMessage());
    }
}
