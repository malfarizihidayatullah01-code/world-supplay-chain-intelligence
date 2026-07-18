<?php

namespace App\Http\Controllers;

use App\Services\ShipmentRiskAnalysisService;
use App\Services\ShipmentService;
use Illuminate\Http\Request;

class ShipmentRiskAnalysisController extends Controller
{
    protected ShipmentRiskAnalysisService $shipmentRiskService;
    protected ShipmentService             $shipmentService;

    public function __construct(
        ShipmentRiskAnalysisService $shipmentRiskService,
        ShipmentService             $shipmentService
    ) {
        $this->shipmentRiskService = $shipmentRiskService;
        $this->shipmentService     = $shipmentService;
    }

    /**
     * List all shipment risk analysis records with filters.
     */
    public function index(Request $request)
    {
        $filters     = $request->only(['risk_level', 'shipment_id', 'sort_by', 'sort_dir']);
        $analyses    = $this->shipmentRiskService->getPaginated($filters, 10);
        $riskLevels  = $this->shipmentRiskService->getRiskLevelOptions();

        return view('shipment_risk_analysis.index', compact('analyses', 'filters', 'riskLevels'));
    }

    /**
     * Show a single analysis record.
     */
    public function show(int $id)
    {
        $analysis = $this->shipmentRiskService->findById($id);

        return view('shipment_risk_analysis.show', compact('analysis'));
    }

    /**
     * Trigger analysis for a specific shipment.
     */
    public function analyse(int $shipmentId)
    {
        try {
            $analysis = $this->shipmentRiskService->analyseShipment($shipmentId);

            return redirect()->route('shipment-risk-analysis.show', $analysis->id)
                ->with('success', "Shipment risk analysis completed for shipment {$analysis->shipment->shipment_code}. Risk Level: {$analysis->risk_level}.");
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
        $result = $this->shipmentRiskService->analyseAllShipments();

        return redirect()->route('shipment-risk-analysis.index')
            ->with('success', $result['message']);
    }
}
