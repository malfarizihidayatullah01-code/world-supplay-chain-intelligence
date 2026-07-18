<?php

namespace App\Repositories;

use App\Models\Shipment;

class ShipmentMonitoringRepository implements ShipmentMonitoringRepositoryInterface
{
    public function getPaginated(array $filters, int $perPage = 10)
    {
        $query = Shipment::with([
            'originCountry',
            'destinationCountry',
            'originPort',
            'destinationPort',
            'routeRiskAnalysis',
            'shipmentRiskAnalysis',
            'shipmentRecommendation',
        ]);

        // Search by shipment_code
        if (!empty($filters['shipment_code'])) {
            $query->where('shipment_code', 'like', '%' . $filters['shipment_code'] . '%');
        }

        // Search by origin country (using whereHas)
        if (!empty($filters['origin_country'])) {
            $query->whereHas('originCountry', function ($q) use ($filters) {
                $q->where('country_name', 'like', '%' . $filters['origin_country'] . '%');
            });
        }

        // Search by destination country
        if (!empty($filters['destination_country'])) {
            $query->whereHas('destinationCountry', function ($q) use ($filters) {
                $q->where('country_name', 'like', '%' . $filters['destination_country'] . '%');
            });
        }

        // Filter by shipment status
        if (!empty($filters['shipment_status'])) {
            $query->where('shipment_status', $filters['shipment_status']);
        }

        // Filter by risk level
        if (!empty($filters['risk_level'])) {
            $query->whereHas('shipmentRiskAnalysis', function ($q) use ($filters) {
                $q->where('risk_level', $filters['risk_level']);
            });
        }

        // Filter by recommendation status
        if (!empty($filters['recommendation_status'])) {
            $query->whereHas('shipmentRecommendation', function ($q) use ($filters) {
                $q->where('recommendation_status', $filters['recommendation_status']);
            });
        }

        // Sorting
        $sortField     = $filters['sort_by']  ?? 'updated_at';
        $sortDirection = $filters['sort_dir'] ?? 'desc';

        if ($sortField === 'shipment_risk_score') {
            // Need a join to sort by relation field effectively
            $query->leftJoin('shipment_risk_analyses', 'shipments.id', '=', 'shipment_risk_analyses.shipment_id')
                  ->orderBy('shipment_risk_analyses.shipment_risk_score', $sortDirection)
                  ->select('shipments.*');
        } elseif (in_array($sortField, ['shipment_code', 'departure_date', 'created_at', 'updated_at'])) {
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
            'routeRiskAnalysis',
            'shipmentRiskAnalysis',
            'shipmentRecommendation',
        ])->findOrFail($id);
    }

    public function getSummaryStats()
    {
        // Query to get totals
        $total = Shipment::count();
        $approved = Shipment::whereHas('shipmentRecommendation', function ($q) {
            $q->where('recommendation_status', 'Approved');
        })->count();
        $monitoring = Shipment::whereHas('shipmentRecommendation', function ($q) {
            $q->where('recommendation_status', 'Monitoring');
        })->count();
        $attention = Shipment::whereHas('shipmentRecommendation', function ($q) {
            $q->where('recommendation_status', 'Attention Required');
        })->count();

        return [
            'total' => $total,
            'approved' => $approved,
            'monitoring' => $monitoring,
            'attention_required' => $attention,
        ];
    }
}
