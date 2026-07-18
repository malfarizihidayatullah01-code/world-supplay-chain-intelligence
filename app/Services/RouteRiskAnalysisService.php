<?php

namespace App\Services;

use App\Models\RouteRiskAnalysis;
use App\Repositories\RouteRiskAnalysisRepositoryInterface;
use App\Repositories\ShipmentRepositoryInterface;
use App\Models\WeatherData;
use Illuminate\Support\Facades\Log;

class RouteRiskAnalysisService
{
    protected RouteRiskAnalysisRepositoryInterface $riskRepository;
    protected ShipmentRepositoryInterface          $shipmentRepository;

    public function __construct(
        RouteRiskAnalysisRepositoryInterface $riskRepository,
        ShipmentRepositoryInterface          $shipmentRepository
    ) {
        $this->riskRepository     = $riskRepository;
        $this->shipmentRepository = $shipmentRepository;
    }

    // ── Read ──────────────────────────────────────────────────────

    public function getPaginated(array $filters, int $perPage = 10)
    {
        return $this->riskRepository->getPaginated($filters, $perPage);
    }

    public function findById(int $id)
    {
        return $this->riskRepository->findById($id);
    }

    public function findByShipmentId(int $shipmentId)
    {
        return $this->riskRepository->findByShipmentId($shipmentId);
    }

    public function getRiskLevelOptions(): array
    {
        return ['LOW', 'MEDIUM', 'HIGH'];
    }

    // ── Calculate & Persist ───────────────────────────────────────

    /**
     * Run the risk analysis for a given shipment and save (upsert) the result.
     *
     * Formula:
     *   route_score = (origin_country_risk × 0.40)
     *               + (destination_country_risk × 0.40)
     *               + (weather_risk × 0.20)
     *
     * Risk Level:
     *   0–30   → LOW
     *   31–70  → MEDIUM
     *   71–100 → HIGH
     *
     * @return RouteRiskAnalysis
     */
    public function analyseShipment(int $shipmentId): RouteRiskAnalysis
    {
        $shipment = $this->shipmentRepository->findById($shipmentId);

        // ── 1. Derive Origin Country Risk ─────────────────────────
        $originRisk = $this->calculateCountryRisk($shipment->origin_country_id);

        // ── 2. Derive Destination Country Risk ────────────────────
        $destinationRisk = $this->calculateCountryRisk($shipment->destination_country_id);

        // ── 3. Derive Weather Risk ────────────────────────────────
        // Use the average of origin and destination weather risk
        $weatherRisk = round(
            ($this->calculateWeatherRisk($shipment->origin_country_id)
             + $this->calculateWeatherRisk($shipment->destination_country_id)) / 2,
            2
        );

        // ── 4. Composite Route Score ──────────────────────────────
        $routeScore = round(
            ($originRisk * 0.40) + ($destinationRisk * 0.40) + ($weatherRisk * 0.20),
            2
        );

        $riskLevel = RouteRiskAnalysis::scoreToLevel($routeScore);

        // ── 5. Build human-readable notes ─────────────────────────
        $notes = implode('; ', array_filter([
            "Origin ({$shipment->originCountry->country_name}) risk: {$originRisk}",
            "Destination ({$shipment->destinationCountry->country_name}) risk: {$destinationRisk}",
            "Avg weather risk: {$weatherRisk}",
            "Composite score: {$routeScore} → {$riskLevel}",
        ]));

        Log::info("[RouteRiskAnalysis] Shipment #{$shipmentId} ({$shipment->shipment_code}): "
            . "score={$routeScore}, level={$riskLevel}");

        // ── 6. Upsert ─────────────────────────────────────────────
        return $this->riskRepository->updateOrCreateByShipment($shipmentId, [
            'origin_country_risk'      => $originRisk,
            'destination_country_risk' => $destinationRisk,
            'weather_risk'             => $weatherRisk,
            'route_score'              => $routeScore,
            'risk_level'               => $riskLevel,
            'analysis_notes'           => $notes,
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
                Log::error("[RouteRiskAnalysis] Failed for shipment #{$shipment->id}: " . $e->getMessage());
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

    // ── Private Scoring Algorithms ────────────────────────────────

    /**
     * Derive a 0–100 risk score for a country based on its latest weather data.
     *
     * Scoring heuristics (extensible — plug in real geopolitical data in Sprint 6):
     *  - Extreme temperature  → raises risk
     *  - High wind speed      → raises risk
     *  - Severe weather code  → raises risk
     *
     * Returns a value between 0 and 100.
     */
    private function calculateCountryRisk(int $countryId): float
    {
        $weather = WeatherData::where('country_id', $countryId)
            ->where('status', 'Active')
            ->latest('observation_time')
            ->first();

        if (!$weather) {
            // No data → treat as medium-unknown risk
            return 50.0;
        }

        $score = 0.0;

        // Temperature component (0–40 pts)
        // Very cold (<-10°C) or very hot (>40°C) → high contribution
        $temp = (float) ($weather->temperature ?? 20);
        if ($temp < -10 || $temp > 40) {
            $score += 40;
        } elseif ($temp < 0 || $temp > 35) {
            $score += 25;
        } elseif ($temp < 5 || $temp > 30) {
            $score += 10;
        }

        // Wind speed component (0–30 pts)
        $wind = (float) ($weather->wind_speed ?? 0);
        if ($wind > 80) {
            $score += 30;
        } elseif ($wind > 50) {
            $score += 20;
        } elseif ($wind > 30) {
            $score += 10;
        } elseif ($wind > 15) {
            $score += 5;
        }

        // Condition component (0–30 pts)
        $condition = strtolower($weather->weather_condition ?? '');
        $highRiskConditions   = ['thunderstorm', 'heavy rain', 'heavy snow', 'violent', 'blizzard'];
        $mediumRiskConditions = ['moderate rain', 'moderate snow', 'fog', 'drizzle', 'rain shower'];

        foreach ($highRiskConditions as $keyword) {
            if (str_contains($condition, $keyword)) {
                $score += 30;
                break;
            }
        }
        foreach ($mediumRiskConditions as $keyword) {
            if (str_contains($condition, $keyword)) {
                $score += 15;
                break;
            }
        }

        return min(round($score, 2), 100.0);
    }

    /**
     * Derive a weather-specific 0–100 risk score focused on wind and conditions
     * (these are the factors most relevant to maritime / air freight disruptions).
     */
    private function calculateWeatherRisk(int $countryId): float
    {
        $weather = WeatherData::where('country_id', $countryId)
            ->where('status', 'Active')
            ->latest('observation_time')
            ->first();

        if (!$weather) {
            return 40.0; // Unknown → moderate default
        }

        $score = 0.0;

        // Wind risk (0–60 pts)
        $wind = (float) ($weather->wind_speed ?? 0);
        if ($wind > 100) {
            $score += 60;
        } elseif ($wind > 60) {
            $score += 40;
        } elseif ($wind > 30) {
            $score += 20;
        } elseif ($wind > 15) {
            $score += 8;
        }

        // Condition risk (0–40 pts)
        $condition = strtolower($weather->weather_condition ?? '');
        $severeConditions    = ['thunderstorm', 'violent', 'heavy snow', 'blizzard'];
        $moderateConditions  = ['moderate rain', 'fog', 'moderate snow', 'rain shower', 'drizzle'];

        foreach ($severeConditions as $keyword) {
            if (str_contains($condition, $keyword)) {
                $score += 40;
                break;
            }
        }
        foreach ($moderateConditions as $keyword) {
            if (str_contains($condition, $keyword)) {
                $score += 20;
                break;
            }
        }

        return min(round($score, 2), 100.0);
    }
}
