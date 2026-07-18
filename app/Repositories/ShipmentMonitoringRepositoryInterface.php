<?php

namespace App\Repositories;

interface ShipmentMonitoringRepositoryInterface
{
    public function getPaginated(array $filters, int $perPage = 10);
    
    public function findById(int $id);
    
    public function getSummaryStats();
}
