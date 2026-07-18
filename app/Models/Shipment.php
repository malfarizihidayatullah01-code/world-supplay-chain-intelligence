<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_code',
        'origin_country_id',
        'origin_port_id',
        'destination_country_id',
        'destination_port_id',
        'cargo_type',
        'cargo_description',
        'departure_date',
        'estimated_arrival',
        'shipment_status',
    ];

    protected $casts = [
        'departure_date'    => 'date',
        'estimated_arrival' => 'date',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function originCountry()
    {
        return $this->belongsTo(Country::class, 'origin_country_id');
    }

    public function destinationCountry()
    {
        return $this->belongsTo(Country::class, 'destination_country_id');
    }

    public function originPort()
    {
        return $this->belongsTo(Port::class, 'origin_port_id');
    }

    public function destinationPort()
    {
        return $this->belongsTo(Port::class, 'destination_port_id');
    }

    public function routeRiskAnalysis()
    {
        return $this->hasOne(RouteRiskAnalysis::class);
    }

    public function shipmentRiskAnalysis()
    {
        return $this->hasOne(ShipmentRiskAnalysis::class);
    }

    public function shipmentRecommendation()
    {
        return $this->hasOne(ShipmentRecommendation::class);
    }

    // ── Helpers ───────────────────────────────────────────────────

    /**
     * Generate the next sequential shipment code: SHP-000001, SHP-000002, …
     */
    public static function generateCode(): string
    {
        $latest = static::max('id') ?? 0;
        return 'SHP-' . str_pad($latest + 1, 6, '0', STR_PAD_LEFT);
    }
}
