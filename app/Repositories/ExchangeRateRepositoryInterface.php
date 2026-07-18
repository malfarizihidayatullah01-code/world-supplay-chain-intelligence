<?php

namespace App\Repositories;

interface ExchangeRateRepositoryInterface
{
    public function updateOrCreate(array $attributes, array $values);
    public function getPaginated(int $perPage = 10);
}

