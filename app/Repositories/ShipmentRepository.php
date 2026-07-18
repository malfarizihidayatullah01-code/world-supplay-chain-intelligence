<?php

namespace App\Repositories;

use App\Models\Shipment;

class ShipmentRepository implements ShipmentRepositoryInterface
{
    public function getPaginated(array $filters, int $perPage = 10)
    {
        $query = Shipment::with([
            'originCountry',
            'destinationCountry',
            'originPort',
            'destinationPort',
        ]);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('shipment_code', 'like', "%{$search}%")
                  ->orWhere('cargo_type', 'like', "%{$search}%")
                  ->orWhere('cargo_description', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['shipment_status'])) {
            $query->where('shipment_status', $filters['shipment_status']);
        }

        if (!empty($filters['origin_country_id'])) {
            $query->where('origin_country_id', $filters['origin_country_id']);
        }

        if (!empty($filters['destination_country_id'])) {
            $query->where('destination_country_id', $filters['destination_country_id']);
        }

        $sortField     = $filters['sort_by']  ?? 'created_at';
        $sortDirection = $filters['sort_dir'] ?? 'desc';

        $validSortFields = ['shipment_code', 'departure_date', 'estimated_arrival', 'shipment_status', 'created_at'];
        if (in_array($sortField, $validSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        return $query->paginate($perPage)->appends($filters);
    }

    public function findById(int $id)
    {
        return Shipment::with([
            'originCountry',
            'destinationCountry',
            'originPort',
            'destinationPort',
        ])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Shipment::create($data);
    }

    public function update(int $id, array $data)
    {
        $shipment = $this->findById($id);
        $shipment->update($data);
        return $shipment;
    }

    public function delete(int $id)
    {
        $shipment = $this->findById($id);
        return $shipment->delete();
    }

    public function getAll()
    {
        return Shipment::with(['originCountry', 'destinationCountry', 'originPort', 'destinationPort'])->get();
    }

    /**
     * Generate the next shipment code by reading the current MAX id.
     * This ensures uniqueness even if rows were deleted.
     */
    public function nextCode(): string
    {
        return Shipment::generateCode();
    }
}
