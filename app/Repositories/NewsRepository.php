<?php

namespace App\Repositories;

use App\Models\NewsArticle;

class NewsRepository implements NewsRepositoryInterface
{
    public function updateOrCreate(array $attributes, array $values)
    {
        return NewsArticle::updateOrCreate($attributes, $values);
    }

    public function getPaginated(int $perPage = 10)
    {
        return NewsArticle::with('country')->paginate($perPage);
    }
}
