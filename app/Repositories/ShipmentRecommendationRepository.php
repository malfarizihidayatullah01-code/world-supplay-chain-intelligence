<?php

namespace App\Repositories;

use App\Models\ShipmentRecommendation;

class ShipmentRecommendationRepository implements ShipmentRecommendationRepositoryInterface
{
    public function getPaginated(array $filters, int $perPage = 10)
    {
        $query = ShipmentRecommendation::with([
            'shipment',
            'shipment.originCountry',
            'shipment.destinationCountry',
            'shipment.originPort',
            'shipment.destinationPort',
        ]);

        if (!empty($filters['recommendation_status'])) {
            $query->where('recommendation_status', $filters['recommendation_status']);
        }

        if (!empty($filters['shipment_id'])) {
            $query->where('shipment_id', $filters['shipment_id']);
        }

        $sortField     = $filters['sort_by']  ?? 'created_at';
        $sortDirection = $filters['sort_dir'] ?? 'desc';

        $validSortFields = ['shipment_risk_score', 'recommendation_status', 'created_at', 'updated_at'];
        if (in_array($sortField, $validSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        return $query->paginate($perPage)->appends($filters);
    }

    public function findById(int $id)
    {
        return ShipmentRecommendation::with([
            'shipment',
            'shipment.originCountry',
            'shipment.destinationCountry',
            'shipment.originPort',
            'shipment.destinationPort',
        ])->findOrFail($id);
    }

    public function findByShipmentId(int $shipmentId)
    {
        return ShipmentRecommendation::with([
            'shipment',
            'shipment.originCountry',
            'shipment.destinationCountry',
            'shipment.originPort',
            'shipment.destinationPort',
        ])->where('shipment_id', $shipmentId)->first();
    }

    public function create(array $data)
    {
        return ShipmentRecommendation::create($data);
    }

    public function update(int $id, array $data)
    {
        $record = $this->findById($id);
        $record->update($data);
        return $record;
    }

    public function updateOrCreateByShipment(int $shipmentId, array $data)
    {
        return ShipmentRecommendation::updateOrCreate(
            ['shipment_id' => $shipmentId],
            $data
        );
    }

    public function delete(int $id)
    {
        $record = $this->findById($id);
        return $record->delete();
    }

    public function getAll()
    {
        return ShipmentRecommendation::with(['shipment'])->get();
    }
}
