@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0"><i class="bi bi-shield-check me-2"></i>Shipment Risk Analysis Detail</h2>
        <div>
            <form action="{{ route('shipment-risk-analysis.analyse', $analysis->shipment_id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="bi bi-arrow-repeat"></i> Re-analyse
                </button>
            </form>
            <a href="{{ route('shipment-risk-analysis.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @php
        $badgeClass = match($analysis->risk_level) {
            'LOW'    => 'bg-success-subtle text-success',
            'MEDIUM' => 'bg-warning-subtle text-warning',
            'HIGH'   => 'bg-danger-subtle text-danger',
            default  => 'bg-secondary-subtle text-secondary',
        };
        $shipment = $analysis->shipment;
    @endphp

    <div class="row g-4">
        {{-- Left Column: Scores --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-4" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <h5 class="mb-0 fw-bold">Overall Shipment Risk</h5>
                </div>
                <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                    <div class="display-1 fw-bold mb-2">{{ number_format($analysis->shipment_risk_score, 1) }}</div>
                    <div>
                        <span class="badge {{ $badgeClass }} fs-5 px-4 py-2">{{ $analysis->risk_level }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Breakdown --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-4" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <h5 class="mb-0 fw-bold">Analysis Breakdown</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center h-100">
                                <div class="text-muted small mb-1">Route Risk Score</div>
                                <div class="fs-3 fw-semibold">{{ number_format($analysis->route_risk_score, 1) }}</div>
                                <div class="text-muted small">Based on Origin, Dest, & Weather</div>
                                <div class="mt-2">
                                    @if($shipment->routeRiskAnalysis)
                                        <a href="{{ route('route-risk-analysis.show', $shipment->routeRiskAnalysis->id) }}" class="btn btn-sm btn-outline-info">
                                            View Route Details
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center h-100 d-flex flex-column justify-content-center">
                                <div class="text-muted small mb-1">Overall Country Risk</div>
                                <div class="fs-3 fw-semibold text-muted">N/A</div>
                                <div class="text-muted small">(Upcoming in Sprint 6)</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border rounded p-3 bg-light">
                                <div class="fw-bold mb-2">Analysis Summary:</div>
                                <p class="mb-0 text-muted">{{ $analysis->analysis_summary ?: 'No specific notes.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Row: Shipment Info --}}
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-4" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Shipment Details</h5>
                        <a href="{{ route('shipments.show', $shipment->id) }}" class="btn btn-sm btn-outline-primary">
                            View Shipment
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="text-muted small">Shipment Code</div>
                            <div class="fw-semibold font-monospace">{{ $shipment->shipment_code }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">Status</div>
                            <div class="fw-semibold">{{ $shipment->shipment_status }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">Departure</div>
                            <div class="fw-semibold">{{ $shipment->departure_date?->format('d M Y') ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">Est. Arrival</div>
                            <div class="fw-semibold">{{ $shipment->estimated_arrival?->format('d M Y') ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Origin</div>
                            <div class="fw-semibold">{{ $shipment->originPort->port_name ?? '-' }} ({{ $shipment->originCountry->country_name ?? '-' }})</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Destination</div>
                            <div class="fw-semibold">{{ $shipment->destinationPort->port_name ?? '-' }} ({{ $shipment->destinationCountry->country_name ?? '-' }})</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top p-3 text-muted small" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                    Last analysed on: {{ $analysis->updated_at->format('d M Y, H:i:s') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
