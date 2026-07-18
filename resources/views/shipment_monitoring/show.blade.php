@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0"><i class="bi bi-display me-2"></i>Shipment Monitoring Detail</h2>
        <div>
            <a href="{{ route('shipment-monitoring.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    {{-- Sprint 6: Interactive Shipment Map --}}
    <div id="shipment-map-placeholder" class="d-none"></div>
    
    {{-- Hidden data for Sprint 6 --}}
    <div id="map-data" class="d-none" data-map="{{ json_encode($mapData) }}"></div>

    <div class="row g-4">
        {{-- 1. Shipment Information --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-4" style="border-top-left-radius:16px;border-top-right-radius:16px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-box-seam text-primary me-2"></i>Shipment Information</h5>
                        <span class="badge bg-secondary">{{ $shipment->shipment_status }}</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-muted small">Shipment Code</div>
                            <div class="fw-bold font-monospace">{{ $shipment->shipment_code }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Cargo</div>
                            <div class="fw-semibold">{{ $shipment->cargo_type }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Origin</div>
                            <div class="fw-semibold">{{ $shipment->originCountry->country_name ?? '-' }}</div>
                            <div class="text-muted small">{{ $shipment->originPort->port_name ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Destination</div>
                            <div class="fw-semibold">{{ $shipment->destinationCountry->country_name ?? '-' }}</div>
                            <div class="text-muted small">{{ $shipment->destinationPort->port_name ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Departure Date</div>
                            <div class="fw-semibold">{{ $shipment->departure_date?->format('d M Y') ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Estimated Arrival</div>
                            <div class="fw-semibold">{{ $shipment->estimated_arrival?->format('d M Y') ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Recommendation & Action Required --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-4" style="border-top-left-radius:16px;border-top-right-radius:16px;">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-lightbulb text-warning me-2"></i>Recommendation Strategy</h5>
                </div>
                <div class="card-body p-4">
                    @if($shipment->shipmentRecommendation)
                        @php
                            $rec = $shipment->shipmentRecommendation;
                            $recBadgeClass = match($rec->recommendation_status) {
                                'Approved'           => 'bg-success-subtle text-success border border-success',
                                'Monitoring'         => 'bg-warning-subtle text-warning border border-warning',
                                'Attention Required' => 'bg-danger-subtle text-danger border border-danger',
                                default              => 'bg-secondary-subtle text-secondary',
                            };
                            $iconClass = match($rec->recommendation_status) {
                                'Approved'           => 'bi-check-circle-fill text-success',
                                'Monitoring'         => 'bi-exclamation-triangle-fill text-warning',
                                'Attention Required' => 'bi-x-octagon-fill text-danger',
                                default              => 'bi-info-circle-fill text-secondary',
                            };
                        @endphp
                        
                        <div class="p-4 rounded mb-4 {{ $recBadgeClass }} text-center">
                            <i class="bi {{ $iconClass }} fs-1 mb-2 d-block"></i>
                            <h4 class="fw-bold mb-1">{{ $rec->recommendation_status }}</h4>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted small mb-1">Recommendation</div>
                            <div class="fs-5 fw-semibold">{{ $rec->recommendation }}</div>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Action Required</div>
                            <div class="fs-5 fw-semibold text-danger">{{ $rec->action_required }}</div>
                        </div>
                        <div class="mt-3 text-end text-muted small">
                            Last Updated: {{ $rec->updated_at->format('d M Y, H:i') }}
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-gear-wide-connected fs-1 d-block mb-2"></i>
                            No recommendation generated yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- 3. Route Risk --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-4" style="border-top-left-radius:16px;border-top-right-radius:16px;">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-graph-up-arrow text-info me-2"></i>Route Risk Analysis</h5>
                </div>
                <div class="card-body p-4">
                    @if($shipment->routeRiskAnalysis)
                        @php
                            $route = $shipment->routeRiskAnalysis;
                            $routeBadge = match($route->risk_level) {
                                'LOW'    => 'bg-success-subtle text-success',
                                'MEDIUM' => 'bg-warning-subtle text-warning',
                                'HIGH'   => 'bg-danger-subtle text-danger',
                                default  => 'bg-secondary-subtle text-secondary',
                            };
                        @endphp
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <div class="text-muted small">Route Score</div>
                                <div class="display-4 fw-bold">{{ number_format($route->route_score, 1) }}</div>
                            </div>
                            <span class="badge {{ $routeBadge }} fs-5 px-4 py-2">{{ $route->risk_level }}</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-4">
                                <div class="border rounded p-2 text-center">
                                    <div class="text-muted small">Origin Risk</div>
                                    <div class="fw-bold fs-5">{{ number_format($route->origin_country_risk, 1) }}</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-2 text-center">
                                    <div class="text-muted small">Dest Risk</div>
                                    <div class="fw-bold fs-5">{{ number_format($route->destination_country_risk, 1) }}</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-2 text-center">
                                    <div class="text-muted small">Weather</div>
                                    <div class="fw-bold fs-5">{{ number_format($route->weather_risk, 1) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-end text-muted small">
                            Last Updated: {{ $route->updated_at->format('d M Y, H:i') }}
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-clock-history fs-1 d-block mb-2"></i>
                            Route analysis pending.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- 4. Shipment Risk --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-4" style="border-top-left-radius:16px;border-top-right-radius:16px;">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-shield-check text-success me-2"></i>Shipment Risk Analysis</h5>
                </div>
                <div class="card-body p-4">
                    @if($shipment->shipmentRiskAnalysis)
                        @php
                            $sra = $shipment->shipmentRiskAnalysis;
                            $sraBadge = match($sra->risk_level) {
                                'LOW'    => 'bg-success-subtle text-success',
                                'MEDIUM' => 'bg-warning-subtle text-warning',
                                'HIGH'   => 'bg-danger-subtle text-danger',
                                default  => 'bg-secondary-subtle text-secondary',
                            };
                        @endphp
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <div class="text-muted small">Overall Score</div>
                                <div class="display-4 fw-bold">{{ number_format($sra->shipment_risk_score, 1) }}</div>
                            </div>
                            <span class="badge {{ $sraBadge }} fs-5 px-4 py-2">{{ $sra->risk_level }}</span>
                        </div>
                        <div class="border rounded p-3 bg-light">
                            <div class="text-muted small fw-bold mb-1">Analysis Summary</div>
                            <p class="mb-0">{{ $sra->analysis_summary ?: 'No summary available.' }}</p>
                        </div>
                        <div class="mt-4 text-end text-muted small">
                            Last Updated: {{ $sra->updated_at->format('d M Y, H:i') }}
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-clock-history fs-1 d-block mb-2"></i>
                            Shipment analysis pending.
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
