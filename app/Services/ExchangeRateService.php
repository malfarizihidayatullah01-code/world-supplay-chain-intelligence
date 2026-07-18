<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Repositories\ExchangeRateRepositoryInterface;
use App\Repositories\CountryRepositoryInterface;
use Exception;
use Carbon\Carbon;

class ExchangeRateService
{
    protected $exchangeRateRepository;
    protected $countryRepository;
    protected $apiUrl;

    public function __construct(ExchangeRateRepositoryInterface $exchangeRateRepository, CountryRepositoryInterface $countryRepository)
    {
        $this->exchangeRateRepository = $exchangeRateRepository;
        $this->countryRepository = $countryRepository;
        // Using open.er-api.com as it provides free latest rates without requiring an API key.
        $this->apiUrl = 'https://open.er-api.com/v6/latest/USD';
    }

    public function getPaginated(int $perPage = 10)
    {
        return $this->exchangeRateRepository->getPaginated($perPage);
    }

    public function syncExchangeRates()
    {
        Log::info('Started Exchange Rate API synchronization (open.er-api.com).');

        try {
            // Fetch all exchange rates at once
            $response = Http::timeout(30)
                ->connectTimeout(10)
                ->retry(3, 1000)
                ->get($this->apiUrl);

            if ($response->failed()) {
                Log::error('Exchange Rate API failed to fetch global rates. Status: ' . $response->status());
                return [
                    'success' => false,
                    'message' => 'Failed to fetch rates from API. HTTP Status: ' . $response->status()
                ];
            }

            $data = $response->json();

            if (!isset($data['rates']) || !is_array($data['rates'])) {
                Log::error('Exchange Rate API returned invalid format (missing rates).');
                return [
                    'success' => false,
                    'message' => 'API returned invalid format.'
                ];
            }

            $rates = $data['rates'];
            $countries = $this->countryRepository->getAll();
            $syncedCount = 0;
            $skippedCount = 0;

            foreach ($countries as $country) {
                if ($country->status !== 'Active') {
                    continue;
                }

                $currencyCode = $country->currency_code;

                if (!$currencyCode || !isset($rates[$currencyCode])) {
                    Log::warning("Skipping {$country->country_name}: Currency code '{$currencyCode}' not found in API response.");
                    $skippedCount++;
                    continue;
                }

                try {
                    DB::beginTransaction();

                    $this->exchangeRateRepository->updateOrCreate(
                        ['country_id' => $country->id],
                        [
                            'base_currency' => 'USD',
                            'target_currency' => $currencyCode,
                            'exchange_rate' => $rates[$currencyCode],
                            'exchange_date' => Carbon::now(),
                            'status' => 'Active',
                        ]
                    );

                    DB::commit();
                    $syncedCount++;
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error("Exception syncing exchange rates for {$country->country_name}: " . $e->getMessage());
                }
            }

            Log::info("Successfully synced exchange rates for {$syncedCount} countries. Skipped {$skippedCount} countries.");

            return [
                'success' => true,
                'message' => "Successfully synced exchange rates for {$syncedCount} countries. (Skipped {$skippedCount})",
                'count' => $syncedCount
            ];

        } catch (Exception $e) {
            Log::error('Critical error during Exchange Rate API sync: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred during exchange rate synchronization: ' . $e->getMessage()
            ];
        }
    }
}
