<?php

namespace App\Repositories;

use App\Models\ShippingRoute;
use App\Models\Port;

class ShippingRouteRepository implements ShippingRouteRepositoryInterface
{
    public function getPaginated(array $filters, int $perPage = 10)
    {
        $query = ShippingRoute::with(['originPort', 'destinationPort']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('route_code', 'like', "%{$search}%")
                  ->orWhereHas('originPort', function($q) use ($search) {
                      $q->where('port_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('destinationPort', function($q) use ($search) {
                      $q->where('port_name', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['origin_port_id'])) {
            $query->where('origin_port_id', $filters['origin_port_id']);
        }

        if (!empty($filters['destination_port_id'])) {
            $query->where('destination_port_id', $filters['destination_port_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $sortField = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_dir'] ?? 'desc';
        
        $validSortFields = ['route_code', 'origin_port_id', 'destination_port_id', 'status', 'created_at'];
        if (in_array($sortField, $validSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        return $query->paginate($perPage)->appends($filters);
    }

    public function findById(int $id)
    {
        return ShippingRoute::findOrFail($id);
    }

    public function create(array $data)
    {
        return ShippingRoute::create($data);
    }

    public function update(int $id, array $data)
    {
        $shippingRoute = $this->findById($id);
        $shippingRoute->update($data);
        return $shippingRoute;
    }

    public function delete(int $id)
    {
        $shippingRoute = $this->findById($id);
        return $shippingRoute->delete();
    }

    public function getAllPorts()
    {
        return Port::orderBy('port_name', 'asc')->get();
    }
}
