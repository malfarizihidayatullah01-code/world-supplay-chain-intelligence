<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RouteRiskAnalysis extends Model
{
    use HasFactory;

    protected $table = 'route_risk_analyses';

    protected $fillable = [
        'shipment_id',
        'origin_country_risk',
        'destination_country_risk',
        'weather_risk',
        'route_score',
        'risk_level',
        'analysis_notes',
    ];

    protected $casts = [
        'origin_country_risk'      => 'float',
        'destination_country_risk' => 'float',
        'weather_risk'             => 'float',
        'route_score'              => 'float',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    // ── Helpers ───────────────────────────────────────────────────

    /**
     * Derive the risk level label from a numeric score.
     * 0–30 → LOW, 31–70 → MEDIUM, 71–100 → HIGH
     */
    public static function scoreToLevel(float $score): string
    {
        if ($score <= 30) return 'LOW';
        if ($score <= 70) return 'MEDIUM';
        return 'HIGH';
    }
}
