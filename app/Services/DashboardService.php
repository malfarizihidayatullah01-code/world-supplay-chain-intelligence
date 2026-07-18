<?php

namespace App\Services;

use App\Repositories\DashboardRepositoryInterface;

class DashboardService
{
    protected $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function getDashboardData(): array
    {
        return [
            'counts' => $this->dashboardRepository->getCounts(),
            'last_syncs' => $this->dashboardRepository->getLastSyncs(),
            'recent_data' => $this->dashboardRepository->getRecentData(),
        ];
    }
}
