<?php

namespace App\Http\Controllers;

use App\Services\ShipmentRecommendationService;
use App\Services\ShipmentService;
use Illuminate\Http\Request;

class ShipmentRecommendationController extends Controller
{
    protected ShipmentRecommendationService $recommendationService;
    protected ShipmentService               $shipmentService;

    public function __construct(
        ShipmentRecommendationService $recommendationService,
        ShipmentService               $shipmentService
    ) {
        $this->recommendationService = $recommendationService;
        $this->shipmentService       = $shipmentService;
    }

    /**
     * List all recommendations with filters.
     */
    public function index(Request $request)
    {
        $filters         = $request->only(['recommendation_status', 'shipment_id', 'sort_by', 'sort_dir']);
        $recommendations = $this->recommendationService->getPaginated($filters, 10);
        $statuses        = $this->recommendationService->getStatusOptions();

        return view('shipment_recommendations.index', compact('recommendations', 'filters', 'statuses'));
    }

    /**
     * Show a single recommendation record.
     */
    public function show(int $id)
    {
        $recommendation = $this->recommendationService->findById($id);

        return view('shipment_recommendations.show', compact('recommendation'));
    }

    /**
     * Trigger generation of recommendation for a specific shipment.
     */
    public function analyse(int $shipmentId)
    {
        try {
            $recommendation = $this->recommendationService->generateRecommendation($shipmentId);

            return redirect()->route('shipment-recommendations.show', $recommendation->id)
                ->with('success', "Recommendation generated for shipment {$recommendation->shipment->shipment_code}.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to generate recommendation: ' . $e->getMessage());
        }
    }

    /**
     * Trigger generation for ALL shipments.
     */
    public function analyseAll()
    {
        $result = $this->recommendationService->generateAllRecommendations();

        return redirect()->route('shipment-recommendations.index')
            ->with('success', $result['message']);
    }
}
