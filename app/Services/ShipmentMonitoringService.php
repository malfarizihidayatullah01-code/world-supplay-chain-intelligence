<?php

namespace App\Services;

use App\Repositories\ShipmentMonitoringRepositoryInterface;

class ShipmentMonitoringService
{
    protected ShipmentMonitoringRepositoryInterface $monitoringRepository;

    public function __construct(ShipmentMonitoringRepositoryInterface $monitoringRepository)
    {
        $this->monitoringRepository = $monitoringRepository;
    }

    public function getPaginated(array $filters, int $perPage = 10)
    {
        return $this->monitoringRepository->getPaginated($filters, $perPage);
    }

    public function findById(int $id)
    {
        return $this->monitoringRepository->findById($id);
    }

    public function getDashboardSummary()
    {
        return $this->monitoringRepository->getSummaryStats();
    }

    // Helper functions for dropdown options in the filter
    public function getShipmentStatusOptions(): array
    {
        return ['Planning', 'In Transit', 'Delivered', 'Delayed', 'Cancelled'];
    }

    public function getRiskLevelOptions(): array
    {
        return ['LOW', 'MEDIUM', 'HIGH'];
    }

    public function getRecommendationStatusOptions(): array
    {
        return ['Approved', 'Monitoring', 'Attention Required'];
    }
}
