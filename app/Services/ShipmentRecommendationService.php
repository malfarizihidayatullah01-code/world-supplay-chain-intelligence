<?php

namespace App\Services;

use App\Models\ShipmentRecommendation;
use App\Repositories\ShipmentRecommendationRepositoryInterface;
use App\Repositories\ShipmentRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ShipmentRecommendationService
{
    protected ShipmentRecommendationRepositoryInterface $recommendationRepository;
    protected ShipmentRepositoryInterface               $shipmentRepository;
    protected ShipmentRiskAnalysisService               $shipmentRiskService;

    public function __construct(
        ShipmentRecommendationRepositoryInterface $recommendationRepository,
        ShipmentRepositoryInterface               $shipmentRepository,
        ShipmentRiskAnalysisService               $shipmentRiskService
    ) {
        $this->recommendationRepository = $recommendationRepository;
        $this->shipmentRepository       = $shipmentRepository;
        $this->shipmentRiskService      = $shipmentRiskService;
    }

    // ── Read ──────────────────────────────────────────────────────

    public function getPaginated(array $filters, int $perPage = 10)
    {
        return $this->recommendationRepository->getPaginated($filters, $perPage);
    }

    public function findById(int $id)
    {
        return $this->recommendationRepository->findById($id);
    }

    public function findByShipmentId(int $shipmentId)
    {
        return $this->recommendationRepository->findByShipmentId($shipmentId);
    }

    public function getStatusOptions(): array
    {
        return ['Approved', 'Monitoring', 'Attention Required'];
    }

    // ── Calculate & Persist ───────────────────────────────────────

    /**
     * Generate or update the shipment recommendation for a given shipment.
     *
     * Business Rules:
     *   LOW (0–30)
     *     Recommendation: Shipment can proceed.
     *     Action: No action required.
     *     Status: Approved
     *
     *   MEDIUM (31–70)
     *     Recommendation: Monitor shipment conditions.
     *     Action: Increase monitoring frequency.
     *     Status: Monitoring
     *
     *   HIGH (71–100)
     *     Recommendation: Consider alternative shipping route.
     *     Action: Delay shipment or reroute cargo.
     *     Status: Attention Required
     *
     * @return ShipmentRecommendation
     */
    public function generateRecommendation(int $shipmentId): ShipmentRecommendation
    {
        $shipment = $this->shipmentRepository->findById($shipmentId);

        // 1. Ensure Shipment Risk Analysis exists and is up to date
        //    This cascades down to Route Risk Analysis automatically.
        $shipmentRisk = $this->shipmentRiskService->analyseShipment($shipmentId);
        $score = (float) $shipmentRisk->shipment_risk_score;

        // 2. Apply Business Rules
        if ($score <= 30) {
            $recommendation = 'Shipment can proceed.';
            $action         = 'No action required.';
            $status         = 'Approved';
        } elseif ($score <= 70) {
            $recommendation = 'Monitor shipment conditions.';
            $action         = 'Increase monitoring frequency.';
            $status         = 'Monitoring';
        } else {
            $recommendation = 'Consider alternative shipping route.';
            $action         = 'Delay shipment or reroute cargo.';
            $status         = 'Attention Required';
        }

        Log::info("[ShipmentRecommendation] Shipment #{$shipmentId} ({$shipment->shipment_code}): "
            . "score={$score}, status={$status}");

        // 3. Upsert Recommendation
        return $this->recommendationRepository->updateOrCreateByShipment($shipmentId, [
            'shipment_risk_score'   => $score,
            'recommendation'        => $recommendation,
            'action_required'       => $action,
            'recommendation_status' => $status,
        ]);
    }

    /**
     * Generate recommendations for ALL shipments.
     */
    public function generateAllRecommendations(): array
    {
        $shipments = $this->shipmentRepository->getAll();
        $generated = 0;
        $errors    = 0;

        foreach ($shipments as $shipment) {
            try {
                $this->generateRecommendation($shipment->id);
                $generated++;
            } catch (\Exception $e) {
                Log::error("[ShipmentRecommendation] Failed for shipment #{$shipment->id}: " . $e->getMessage());
                $errors++;
            }
        }

        return [
            'success'   => true,
            'generated' => $generated,
            'errors'    => $errors,
            'message'   => "Generated {$generated} recommendation(s). Errors: {$errors}.",
        ];
    }
}
