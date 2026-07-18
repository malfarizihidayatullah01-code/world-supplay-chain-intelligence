<?php

namespace App\Repositories;

use App\Models\Port;
use App\Models\Country;

class PortRepository implements PortRepositoryInterface
{
    public function getPaginated(array $filters, int $perPage = 10)
    {
        $query = Port::with('country');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('port_name', 'like', "%{$search}%")
                  ->orWhere('port_code', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['country_id'])) {
            $query->where('country_id', $filters['country_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $sortField = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_dir'] ?? 'desc';
        
        $validSortFields = ['port_name', 'port_code', 'city', 'country_id', 'status', 'created_at'];
        if (in_array($sortField, $validSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        return $query->paginate($perPage)->appends($filters);
    }

    public function findById(int $id)
    {
        return Port::findOrFail($id);
    }

    public function create(array $data)
    {
        return Port::create($data);
    }

    public function update(int $id, array $data)
    {
        $port = $this->findById($id);
        $port->update($data);
        return $port;
    }

    public function delete(int $id)
    {
        $port = $this->findById($id);
        return $port->delete();
    }

    public function getAllCountries()
    {
        return Country::orderBy('country_name', 'asc')->get();
    }
}
