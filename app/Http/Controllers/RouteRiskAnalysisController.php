<?php

namespace App\Http\Controllers;

use App\Services\RouteRiskAnalysisService;
use App\Services\ShipmentService;
use Illuminate\Http\Request;

class RouteRiskAnalysisController extends Controller
{
    protected RouteRiskAnalysisService $riskService;
    protected ShipmentService          $shipmentService;

    public function __construct(
        RouteRiskAnalysisService $riskService,
        ShipmentService          $shipmentService
    ) {
        $this->riskService      = $riskService;
        $this->shipmentService  = $shipmentService;
    }

    /**
     * List all route risk analysis records with filters.
     */
    public function index(Request $request)
    {
        $filters     = $request->only(['risk_level', 'shipment_id', 'sort_by', 'sort_dir']);
        $analyses    = $this->riskService->getPaginated($filters, 10);
        $riskLevels  = $this->riskService->getRiskLevelOptions();

        return view('route_risk_analysis.index', compact('analyses', 'filters', 'riskLevels'));
    }

    /**
     * Show a single analysis record.
     */
    public function show(int $id)
    {
        $analysis = $this->riskService->findById($id);

        return view('route_risk_analysis.show', compact('analysis'));
    }

    /**
     * Trigger analysis for a specific shipment.
     */
    public function analyse(int $shipmentId)
    {
        try {
            $analysis = $this->riskService->analyseShipment($shipmentId);

            return redirect()->route('route-risk-analysis.show', $analysis->id)
                ->with('success', "Route risk analysis completed for shipment {$analysis->shipment->shipment_code}. Risk Level: {$analysis->risk_level}.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Analysis failed: ' . $e->getMessage());
        }
    }

    /**
     * Trigger analysis for ALL shipments.
     */
    public function analyseAll()
    {
        $result = $this->riskService->analyseAllShipments();

        return redirect()->route('route-risk-analysis.index')
            ->with('success', $result['message']);
    }
}
