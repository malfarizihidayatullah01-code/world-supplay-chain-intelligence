<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Repositories\CountryRepositoryInterface;
use Exception;

class RestCountriesService
{
    protected $countryRepository;
    protected $apiUrl;
    protected $apiKey;

    public function __construct(CountryRepositoryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;
        $this->apiUrl = config('services.rest_countries.base_url');
        $this->apiKey = config('services.rest_countries.key');
    }

    public function syncCountries()
    {
        Log::info('Started REST Countries API v5 synchronization.');

        try {
            $syncedCount = 0;
            $limit = 100;
            $offset = 0;
            $hasMore = true;

            DB::beginTransaction();

            do {
                $request = Http::timeout(30)
                    ->connectTimeout(10)
                    ->retry(3, 1000);
                
                if ($this->apiKey) {
                    $request->withToken($this->apiKey);
                }

                $response = $request->get($this->apiUrl, [
                    'limit' => $limit,
                    'offset' => $offset
                ]);

                if ($response->failed()) {
                    Log::error('REST Countries API failed: ' . $response->status());
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => 'Failed to fetch data from API. HTTP Status: ' . $response->status()
                    ];
                }

                $body = $response->json();
                
                if (!isset($body['data']['objects']) || !is_array($body['data']['objects'])) {
                    Log::error('REST Countries API returned invalid format.');
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => 'API returned invalid format.'
                    ];
                }

                $countriesData = $body['data']['objects'];

                if (count($countriesData) === 0) {
                    $hasMore = false;
                    break;
                }
                
                foreach ($countriesData as $data) {
                    try {
                        $iso2 = $data['codes']['alpha_2'] ?? null;
                        $iso3 = $data['codes']['alpha_3'] ?? null;

                        if (!$iso2 || !$iso3) {
                            continue;
                        }

                        $countryName = $data['names']['common'] ?? 'Unknown';
                        
                        $capitalCity = 'Unknown';
                        if (!empty($data['capitals']) && is_array($data['capitals'])) {
                            if (isset($data['capitals'][0]['name'])) {
                                $capitalCity = $data['capitals'][0]['name'];
                            }
                        }
                        $region = $data['region'] ?? 'Unknown';
                        $subRegion = $data['subregion'] ?? null;
                        
                        $currencyCode = null;
                        $currencyName = null;
                        
                        if (!empty($data['currencies']) && is_array($data['currencies'])) {
                            if (isset($data['currencies'][0]['code'])) {
                                $currencyCode = $data['currencies'][0]['code'];
                                $currencyName = $data['currencies'][0]['name'] ?? null;
                            } else {
                                // fallback for associative array just in case
                                $firstKey = array_key_first($data['currencies']);
                                if (is_string($firstKey)) {
                                    $currencyCode = $firstKey;
                                    $currencyName = $data['currencies'][$firstKey]['name'] ?? null;
                                }
                            }
                        }

                        $latitude = null;
                        $longitude = null;
                        if (!empty($data['coordinates']) && is_array($data['coordinates'])) {
                            $latitude = $data['coordinates']['lat'] ?? null;
                            $longitude = $data['coordinates']['lng'] ?? null;
                        }

                        $flagUrl = $data['flags']['png'] ?? null;

                        $this->countryRepository->updateOrCreate(
                            ['iso3_code' => $iso3],
                            [
                                'country_name' => $countryName,
                                'iso2_code' => $iso2,
                                'capital_city' => $capitalCity,
                                'region' => $region,
                                'sub_region' => $subRegion,
                                'currency_code' => $currencyCode,
                                'currency_name' => $currencyName,
                                'latitude' => $latitude,
                                'longitude' => $longitude,
                                'flag_url' => $flagUrl,
                                'status' => 'Active',
                            ]
                        );

                        $syncedCount++;
                    } catch (Exception $e) {
                        Log::warning("Failed to sync country: {$iso3}. Error: " . $e->getMessage());
                    }
                }

                // Check pagination meta
                if (isset($body['data']['meta']['more']) && $body['data']['meta']['more'] === true) {
                    $offset += $limit;
                } else {
                    $hasMore = false;
                }

            } while ($hasMore);

            DB::commit();
            Log::info("Successfully synced {$syncedCount} countries from REST Countries API v5.");

            return [
                'success' => true,
                'message' => "Successfully synced {$syncedCount} countries.",
                'count' => $syncedCount
            ];

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Exception during REST Countries API sync: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred during synchronization: ' . $e->getMessage()
            ];
        }
    }
}
