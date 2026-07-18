<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShipmentRiskAnalysis extends Model
{
    use HasFactory;

    protected $table = 'shipment_risk_analyses';

    protected $fillable = [
        'shipment_id',
        'route_risk_score',
        'shipment_risk_score',
        'risk_level',
        'analysis_summary',
    ];

    protected $casts = [
        'route_risk_score'    => 'float',
        'shipment_risk_score' => 'float',
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
