<?php

namespace App\Repositories;

interface DashboardRepositoryInterface
{
    public function getCounts(): array;
    public function getLastSyncs(): array;
    public function getRecentData(): array;
}
