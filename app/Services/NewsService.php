<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Repositories\NewsRepositoryInterface;
use App\Repositories\CountryRepositoryInterface;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;

class NewsService
{
    protected $newsRepository;
    protected $countryRepository;
    protected $apiUrl;
    protected $apiKey;

    public function __construct(NewsRepositoryInterface $newsRepository, CountryRepositoryInterface $countryRepository)
    {
        $this->newsRepository = $newsRepository;
        $this->countryRepository = $countryRepository;
        $this->apiUrl = config('services.news.base_url');
        $this->apiKey = config('services.news.key');
    }

    public function getPaginated(int $perPage = 10)
    {
        return $this->newsRepository->getPaginated($perPage);
    }

    public function syncNews()
    {
        Log::info('Started News API synchronization.');

        try {
            $countries = $this->countryRepository->getAll();
            $syncedCount = 0;

            foreach ($countries as $country) {
                if ($country->status !== 'Active') {
                    continue;
                }

                // Simplified integration logic for demo
                try {
                    DB::beginTransaction();

                    $this->newsRepository->updateOrCreate(
                        ['country_id' => $country->id],
                        [
                            'title' => 'Supply chain disruptions expected in ' . $country->country_name,
                            'summary' => 'Local authorities are monitoring the port congestion affecting shipping times in ' . $country->country_name,
                            'source' => 'Global Logistics News',
                            'url' => 'https://example.com/news/' . Str::slug($country->country_name),
                            'published_at' => now()->subHours(rand(1, 48)),
                            'status' => 'Active',
                        ]
                    );

                    DB::commit();
                    $syncedCount++;
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error("Exception syncing news for {$country->country_name}: " . $e->getMessage());
                }
            }

            Log::info("Successfully synced news for {$syncedCount} countries.");

            return [
                'success' => true,
                'message' => "Successfully synced news for {$syncedCount} countries.",
                'count' => $syncedCount
            ];

        } catch (Exception $e) {
            Log::error('Critical error during News API sync: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred during news synchronization: ' . $e->getMessage()
            ];
        }
    }
}
