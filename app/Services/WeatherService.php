<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Repositories\WeatherRepositoryInterface;
use App\Repositories\CountryRepositoryInterface;
use Exception;
use Carbon\Carbon;

class WeatherService
{
    protected $weatherRepository;
    protected $countryRepository;
    protected $apiUrl;

    public function __construct(WeatherRepositoryInterface $weatherRepository, CountryRepositoryInterface $countryRepository)
    {
        $this->weatherRepository = $weatherRepository;
        $this->countryRepository = $countryRepository;
        $this->apiUrl = 'https://api.open-meteo.com/v1/forecast';
    }

    public function getPaginated(int $perPage = 10)
    {
        return $this->weatherRepository->getPaginated($perPage);
    }

    private function getWeatherCondition($code): string
    {
        $map = [
            0 => 'Clear sky',
            1 => 'Mainly clear', 2 => 'Partly cloudy', 3 => 'Overcast',
            45 => 'Fog', 48 => 'Depositing rime fog',
            51 => 'Light drizzle', 53 => 'Moderate drizzle', 55 => 'Dense drizzle',
            61 => 'Slight rain', 63 => 'Moderate rain', 65 => 'Heavy rain',
            71 => 'Slight snow', 73 => 'Moderate snow', 75 => 'Heavy snow',
            80 => 'Slight rain showers', 81 => 'Moderate rain showers', 82 => 'Violent rain showers',
            95 => 'Thunderstorm', 96 => 'Thunderstorm with hail', 99 => 'Thunderstorm with heavy hail',
        ];
        return $map[$code] ?? 'Unknown';
    }

    /**
     * Synchronize weather data for all active countries.
     *
     * Supports an optional progress callback so callers (e.g. Artisan Command)
     * can display real-time feedback without coupling this service to Console output.
     *
     * @param  callable|null  $onProgress  Called after each country is processed.
     *                                     Signature: fn(int $processed, int $total)
     * @return array{success: bool, message: string, count?: int}
     */
    public function syncWeather(?callable $onProgress = null): array
    {
        Log::info('Started Weather API synchronization (Open-Meteo).');

        try {
            // One unified timestamp for the entire batch — ensures all
            // updated_at values are identical so Dashboard "Last Sync" is consistent.
            $syncTime = Carbon::now();

            $countries = $this->countryRepository->getAll();
            $syncedCount = 0;
            $processedCount = 0;

            // Filter to only countries with coordinates before we start
            $eligibleCountries = $countries->filter(function ($country) {
                return $country->status === 'Active'
                    && !empty($country->latitude)
                    && !empty($country->longitude);
            })->values();

            $total = $eligibleCountries->count();

            foreach ($eligibleCountries as $country) {
                // -------------------------------------------------------
                // NOTE FOR SPRINT 6:
                // In Sprint 6, replace $country->latitude / $country->longitude
                // below with the capital city's geocoded coordinates so that
                // the weather reflects the capital's climate, not the geographic
                // centre of the country.  The capital_city name is already stored
                // in countries.capital_city after the Sprint 4 sync fix.
                // Example:
                //   [$lat, $lng] = Geocoder::fromCapital($country->capital_city);
                // -------------------------------------------------------
                $latitude  = $country->latitude;
                $longitude = $country->longitude;

                try {
                    $response = Http::timeout(15)        // Stable: allows slow endpoints to respond
                        ->connectTimeout(5)              // Stable: don't hang on unreachable hosts
                        ->retry(3, 1000)
                        ->get($this->apiUrl, [
                            'latitude'  => $latitude,
                            'longitude' => $longitude,
                            'current'   => 'temperature_2m,relative_humidity_2m,wind_speed_10m,weather_code',
                        ]);

                    if ($response->failed()) {
                        Log::error("Weather API failed for {$country->country_name}. Status: " . $response->status());
                        continue;
                    }

                    $weatherData = $response->json();

                    if (!isset($weatherData['current'])) {
                        Log::warning("Weather data missing 'current' key for {$country->country_name}.");
                        continue;
                    }

                    $current   = $weatherData['current'];
                    $condition = $this->getWeatherCondition($current['weather_code'] ?? -1);

                    DB::beginTransaction();

                    $model = $this->weatherRepository->updateOrCreate(
                        ['country_id' => $country->id],
                        [
                            'temperature'         => $current['temperature_2m'] ?? null,
                            'humidity'            => $current['relative_humidity_2m'] ?? null,
                            'wind_speed'          => $current['wind_speed_10m'] ?? null,
                            'weather_condition'   => $condition,
                            'weather_description' => $condition,
                            // observation_time comes directly from Open-Meteo, NOT server time
                            'observation_time'    => isset($current['time']) ? Carbon::parse($current['time']) : now(),
                            'status'              => 'Active',
                        ]
                    );

                    // Override updated_at with the batch timestamp so every record
                    // in this sync run shares the same "Last Sync" value.
                    $model->timestamps = false;
                    $model->updated_at = $syncTime;
                    $model->save();

                    DB::commit();
                    $syncedCount++;
                } catch (Exception $e) {
                    DB::rollBack();
                    // Log as warning — a single country failure must NOT
                    // stop the batch or throw out of the job.
                    $errMsg = $e->getMessage();
                    if (str_contains($errMsg, 'timed out') || str_contains($errMsg, 'cURL error')) {
                        Log::warning("Weather API timeout for {$country->country_name}: " . $errMsg);
                    } else {
                        Log::error("Exception syncing weather for {$country->country_name}: " . $errMsg);
                    }
                }

                $processedCount++;

                // Log progress every 25 countries for visibility in log files / Queue workers
                if ($processedCount % 25 === 0 || $processedCount === $total) {
                    Log::info("Weather sync progress: {$processedCount}/{$total} countries processed.");
                }

                // Invoke caller-supplied progress callback (used by Artisan Command)
                if ($onProgress !== null) {
                    ($onProgress)($processedCount, $total);
                }
            }

            $message = "Successfully synced weather for {$syncedCount} countries.";
            Log::info($message);

            return [
                'success' => true,
                'message' => $message,
                'count'   => $syncedCount,
            ];

        } catch (Exception $e) {
            Log::error('Critical error during Weather API sync: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred during weather synchronization: ' . $e->getMessage(),
            ];
        }
    }
}
