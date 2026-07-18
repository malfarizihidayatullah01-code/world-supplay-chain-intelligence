<?php

namespace App\Repositories;

use App\Models\ShipmentRiskAnalysis;

class ShipmentRiskAnalysisRepository implements ShipmentRiskAnalysisRepositoryInterface
{
    public function getPaginated(array $filters, int $perPage = 10)
    {
        $query = ShipmentRiskAnalysis::with([
            'shipment',
            'shipment.originCountry',
            'shipment.destinationCountry',
            'shipment.originPort',
            'shipment.destinationPort',
        ]);

        if (!empty($filters['risk_level'])) {
            $query->where('risk_level', $filters['risk_level']);
        }

        if (!empty($filters['shipment_id'])) {
            $query->where('shipment_id', $filters['shipment_id']);
        }

        $sortField     = $filters['sort_by']  ?? 'created_at';
        $sortDirection = $filters['sort_dir'] ?? 'desc';

        $validSortFields = ['shipment_risk_score', 'risk_level', 'created_at', 'updated_at'];
        if (in_array($sortField, $validSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        return $query->paginate($perPage)->appends($filters);
    }

    public function findById(int $id)
    {
        return ShipmentRiskAnalysis::with([
            'shipment',
            'shipment.originCountry',
            'shipment.destinationCountry',
            'shipment.originPort',
            'shipment.destinationPort',
        ])->findOrFail($id);
    }

    public function findByShipmentId(int $shipmentId)
    {
        return ShipmentRiskAnalysis::with([
            'shipment',
            'shipment.originCountry',
            'shipment.destinationCountry',
            'shipment.originPort',
            'shipment.destinationPort',
        ])->where('shipment_id', $shipmentId)->first();
    }

    public function create(array $data)
    {
        return ShipmentRiskAnalysis::create($data);
    }

    public function update(int $id, array $data)
    {
        $record = $this->findById($id);
        $record->update($data);
        return $record;
    }

    public function updateOrCreateByShipment(int $shipmentId, array $data)
    {
        return ShipmentRiskAnalysis::updateOrCreate(
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
        return ShipmentRiskAnalysis::with(['shipment'])->get();
    }
}
