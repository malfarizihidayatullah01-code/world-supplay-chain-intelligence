<?php

namespace App\Repositories;

interface NewsRepositoryInterface
{
    public function updateOrCreate(array $attributes, array $values);
    public function getPaginated(int $perPage = 10);
}

