<?php

namespace App\Repositories;

use App\Models\ExchangeRate;

class ExchangeRateRepository implements ExchangeRateRepositoryInterface
{
    public function updateOrCreate(array $attributes, array $values)
    {
        return ExchangeRate::updateOrCreate($attributes, $values);
    }

    public function getPaginated(int $perPage = 10)
    {
        return ExchangeRate::with('country')->paginate($perPage);
    }
}
