<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\RouteRiskAnalysis;
use App\Models\ShipmentRiskAnalysis;
use App\Models\ShipmentRecommendation;
use Illuminate\Http\Request;

class ShipmentAnalysisController extends Controller
{
    /**
     * Display the Shipment Analysis Engine Dashboard
     */
    public function index()
    {
        // Fetch counts for KPI Summary
        $stats = [
            'total_shipments'         => Shipment::count(),
            'route_analysis'          => RouteRiskAnalysis::count(),
            'shipment_risk_analysis'  => ShipmentRiskAnalysis::count(),
            'recommendations'         => ShipmentRecommendation::count(),
            'monitoring_records'      => Shipment::count(), // Monitoring represents all shipments available for monitoring
        ];

        return view('shipment_analysis.index', compact('stats'));
    }
}
