<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShipmentRecommendation extends Model
{
    use HasFactory;

    protected $table = 'shipment_recommendations';

    protected $fillable = [
        'shipment_id',
        'shipment_risk_score',
        'recommendation',
        'action_required',
        'recommendation_status',
    ];

    protected $casts = [
        'shipment_risk_score' => 'float',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}
