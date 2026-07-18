<?php

namespace App\Http\Controllers;

use App\Services\ShipmentMonitoringService;
use Illuminate\Http\Request;

class ShipmentMonitoringController extends Controller
{
    protected ShipmentMonitoringService $monitoringService;

    public function __construct(ShipmentMonitoringService $monitoringService)
    {
        $this->monitoringService = $monitoringService;
    }

    public function index(Request $request)
    {
        $filters = $request->only([
            'shipment_code',
            'origin_country',
            'destination_country',
            'shipment_status',
            'risk_level',
            'recommendation_status',
            'sort_by',
            'sort_dir'
        ]);

        $shipments             = $this->monitoringService->getPaginated($filters, 10);
        $summary               = $this->monitoringService->getDashboardSummary();
        $shipmentStatuses      = $this->monitoringService->getShipmentStatusOptions();
        $riskLevels            = $this->monitoringService->getRiskLevelOptions();
        $recommendationStatuses= $this->monitoringService->getRecommendationStatusOptions();

        return view('shipment_monitoring.index', compact(
            'shipments',
            'summary',
            'filters',
            'shipmentStatuses',
            'riskLevels',
            'recommendationStatuses'
        ));
    }

    public function show(int $id)
    {
        $shipment = $this->monitoringService->findById($id);

        // Pre-fetch origin and destination country coordinates for Sprint 6 UI placeholder
        $mapData = [
            'origin' => [
                'name' => $shipment->originCountry->country_name ?? null,
                'lat'  => $shipment->originCountry->latitude ?? null,
                'lng'  => $shipment->originCountry->longitude ?? null,
            ],
            'destination' => [
                'name' => $shipment->destinationCountry->country_name ?? null,
                'lat'  => $shipment->destinationCountry->latitude ?? null,
                'lng'  => $shipment->destinationCountry->longitude ?? null,
            ]
        ];

        return view('shipment_monitoring.show', compact('shipment', 'mapData'));
    }
}
