<?php

namespace App\Services;

use App\Models\ShipmentRiskAnalysis;
use App\Repositories\ShipmentRiskAnalysisRepositoryInterface;
use App\Repositories\ShipmentRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ShipmentRiskAnalysisService
{
    protected ShipmentRiskAnalysisRepositoryInterface $shipmentRiskRepository;
    protected ShipmentRepositoryInterface             $shipmentRepository;
    protected RouteRiskAnalysisService                $routeRiskService;

    public function __construct(
        ShipmentRiskAnalysisRepositoryInterface $shipmentRiskRepository,
        ShipmentRepositoryInterface             $shipmentRepository,
        RouteRiskAnalysisService                $routeRiskService
    ) {
        $this->shipmentRiskRepository = $shipmentRiskRepository;
        $this->shipmentRepository     = $shipmentRepository;
        $this->routeRiskService       = $routeRiskService;
    }

    // ── Read ──────────────────────────────────────────────────────

    public function getPaginated(array $filters, int $perPage = 10)
    {
        return $this->shipmentRiskRepository->getPaginated($filters, $perPage);
    }

    public function findById(int $id)
    {
        return $this->shipmentRiskRepository->findById($id);
    }

    public function findByShipmentId(int $shipmentId)
    {
        return $this->shipmentRiskRepository->findByShipmentId($shipmentId);
    }

    public function getRiskLevelOptions(): array
    {
        return ['LOW', 'MEDIUM', 'HIGH'];
    }

    // ── Calculate & Persist ───────────────────────────────────────

    /**
     * Run the shipment risk analysis for a given shipment and save (upsert) the result.
     *
     * Current Formula (Sprint 5):
     *   shipment_risk_score = route_risk_score
     *
     * Flexible for Sprint 6 to include overall country risk.
     *
     * Risk Level:
     *   0–30   → LOW
     *   31–70  → MEDIUM
     *   71–100 → HIGH
     *
     * @return ShipmentRiskAnalysis
     */
    public function analyseShipment(int $shipmentId): ShipmentRiskAnalysis
    {
        $shipment = $this->shipmentRepository->findById($shipmentId);

        // 1. Ensure Route Risk Analysis exists and is up to date
        //    We trigger the route risk analysis first to get the latest route score.
        $routeRisk = $this->routeRiskService->analyseShipment($shipmentId);
        $routeScore = (float) $routeRisk->route_score;

        // 2. Placeholder for Overall Country Risk (Sprint 6)
        //    $overallCountryRisk = $this->overallRiskService->getRisk($shipment->destination_country_id);
        
        // 3. Composite Shipment Risk Score
        //    For Sprint 5, the shipment risk score is based entirely on the route risk score.
        $shipmentScore = round($routeScore, 2);

        // 4. Derive Risk Level
        $riskLevel = ShipmentRiskAnalysis::scoreToLevel($shipmentScore);

        // 5. Build Analysis Summary
        $notes = [];
        if ($shipmentScore <= 30) {
            $notes[] = "Route conditions are favorable.";
            $notes[] = "Shipment can proceed normally.";
        } elseif ($shipmentScore <= 70) {
            $notes[] = "Route has moderate risks (weather or country-specific).";
            $notes[] = "Shipment should be monitored.";
        } else {
            $notes[] = "High risk detected on the route.";
            $notes[] = "Consider alternative routes or delay shipment.";
        }
        
        // Include route risk details in the summary for context
        $summary = implode(' ', $notes) . " (Route Risk Score: {$routeScore})";

        Log::info("[ShipmentRiskAnalysis] Shipment #{$shipmentId} ({$shipment->shipment_code}): "
            . "score={$shipmentScore}, level={$riskLevel}");

        // 6. Upsert
        return $this->shipmentRiskRepository->updateOrCreateByShipment($shipmentId, [
            'route_risk_score'    => $routeScore,
            'shipment_risk_score' => $shipmentScore,
            'risk_level'          => $riskLevel,
            'analysis_summary'    => $summary,
        ]);
    }

    /**
     * Run analysis for every shipment and return an aggregate result summary.
     */
    public function analyseAllShipments(): array
    {
        $shipments   = $this->shipmentRepository->getAll();
        $analysed    = 0;
        $errors      = 0;

        foreach ($shipments as $shipment) {
            try {
                $this->analyseShipment($shipment->id);
                $analysed++;
            } catch (\Exception $e) {
                Log::error("[ShipmentRiskAnalysis] Failed for shipment #{$shipment->id}: " . $e->getMessage());
                $errors++;
            }
        }

        return [
            'success'  => true,
            'analysed' => $analysed,
            'errors'   => $errors,
            'message'  => "Analysed {$analysed} shipment(s). Errors: {$errors}.",
        ];
    }
}
