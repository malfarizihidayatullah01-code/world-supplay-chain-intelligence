<?php

namespace App\Repositories;

use App\Models\Country;

class CountryRepository implements CountryRepositoryInterface
{
    public function getPaginated(array $filters, int $perPage = 10)
    {
        $query = Country::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('country_name', 'like', "%{$search}%")
                  ->orWhere('iso2_code', 'like', "%{$search}%")
                  ->orWhere('iso3_code', 'like', "%{$search}%")
                  ->orWhere('region', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['region'])) {
            $query->where('region', $filters['region']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $sortField = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_dir'] ?? 'desc';
        
        // ensure valid sort fields to prevent SQL injection
        $validSortFields = ['country_name', 'iso2_code', 'iso3_code', 'region', 'status', 'created_at'];
        if (in_array($sortField, $validSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        return $query->paginate($perPage)->appends($filters);
    }

    public function findById(int $id)
    {
        return Country::findOrFail($id);
    }

    public function create(array $data)
    {
        return Country::create($data);
    }

    public function update(int $id, array $data)
    {
        $country = $this->findById($id);
        $country->update($data);
        return $country;
    }

    public function delete(int $id)
    {
        $country = $this->findById($id);
        return $country->delete();
    }

    public function updateOrCreate(array $attributes, array $values)
    {
        return Country::updateOrCreate($attributes, $values);
    }

    public function getAll()
    {
        return Country::all();
    }
}
