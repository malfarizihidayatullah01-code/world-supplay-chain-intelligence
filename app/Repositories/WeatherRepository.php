<?php

namespace App\Repositories;

use App\Models\WeatherData;

class WeatherRepository implements WeatherRepositoryInterface
{
    public function updateOrCreate(array $attributes, array $values)
    {
        return WeatherData::updateOrCreate($attributes, $values);
    }

    public function getPaginated(int $perPage = 10)
    {
        return WeatherData::with('country')->paginate($perPage);
    }
}
