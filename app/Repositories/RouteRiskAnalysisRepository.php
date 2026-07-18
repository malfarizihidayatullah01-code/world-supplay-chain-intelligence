<?php

namespace App\Repositories;

use App\Models\RouteRiskAnalysis;

class RouteRiskAnalysisRepository implements RouteRiskAnalysisRepositoryInterface
{
    public function getPaginated(array $filters, int $perPage = 10)
    {
        $query = RouteRiskAnalysis::with([
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

        $validSortFields = ['route_score', 'risk_level', 'created_at', 'updated_at'];
        if (in_array($sortField, $validSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        return $query->paginate($perPage)->appends($filters);
    }

    public function findById(int $id)
    {
        return RouteRiskAnalysis::with([
            'shipment',
            'shipment.originCountry',
            'shipment.destinationCountry',
            'shipment.originPort',
            'shipment.destinationPort',
        ])->findOrFail($id);
    }

    public function findByShipmentId(int $shipmentId)
    {
        return RouteRiskAnalysis::with([
            'shipment',
            'shipment.originCountry',
            'shipment.destinationCountry',
            'shipment.originPort',
            'shipment.destinationPort',
        ])->where('shipment_id', $shipmentId)->first();
    }

    public function create(array $data)
    {
        return RouteRiskAnalysis::create($data);
    }

    public function update(int $id, array $data)
    {
        $record = $this->findById($id);
        $record->update($data);
        return $record;
    }

    public function updateOrCreateByShipment(int $shipmentId, array $data)
    {
        return RouteRiskAnalysis::updateOrCreate(
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
        return RouteRiskAnalysis::with(['shipment'])->get();
    }
}
