@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0"><i class="bi bi-lightbulb me-2"></i>Shipment Recommendation Detail</h2>
        <div>
            <form action="{{ route('shipment-recommendations.analyse', $recommendation->shipment_id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="bi bi-arrow-repeat"></i> Regenerate
                </button>
            </form>
            <a href="{{ route('shipment-recommendations.index') }}" class="btn btn-outline-secondary">
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
        $badgeClass = match($recommendation->recommendation_status) {
            'Approved'           => 'bg-success-subtle text-success border border-success',
            'Monitoring'         => 'bg-warning-subtle text-warning border border-warning',
            'Attention Required' => 'bg-danger-subtle text-danger border border-danger',
            default              => 'bg-secondary-subtle text-secondary',
        };
        $iconClass = match($recommendation->recommendation_status) {
            'Approved'           => 'bi-check-circle-fill text-success',
            'Monitoring'         => 'bi-exclamation-triangle-fill text-warning',
            'Attention Required' => 'bi-x-octagon-fill text-danger',
            default              => 'bi-info-circle-fill text-secondary',
        };
        $shipment = $recommendation->shipment;
    @endphp

    <div class="row g-4">
        {{-- Recommendation Highlight --}}
        <div class="col-12">
            <div class="card shadow-sm border-0 {{ $badgeClass }}" style="border-radius: 16px;">
                <div class="card-body p-5 text-center">
                    <i class="bi {{ $iconClass }} display-1 mb-3"></i>
                    <h1 class="fw-bold mb-2">{{ $recommendation->recommendation_status }}</h1>
                    <div class="fs-4">{{ $recommendation->recommendation }}</div>
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-4" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <h5 class="mb-0 fw-bold">Action Plan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <div class="text-muted small mb-1">Required Action</div>
                        <div class="fs-5 fw-semibold">{{ $recommendation->action_required }}</div>
                    </div>
                    <div>
                        <div class="text-muted small mb-1">Underlying Risk Score</div>
                        <div class="fs-2 fw-bold">{{ number_format($recommendation->shipment_risk_score, 1) }}</div>
                        <a href="{{ route('shipment-risk-analysis.show', $shipment->shipmentRiskAnalysis->id ?? 0) }}" class="btn btn-sm btn-outline-info mt-2">
                            View Risk Analysis Details
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Shipment Info --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-4" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Shipment Details</h5>
                        <a href="{{ route('shipments.show', $shipment->id) }}" class="btn btn-sm btn-outline-primary">
                            View Shipment
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-muted small">Shipment Code</div>
                            <div class="fw-semibold font-monospace">{{ $shipment->shipment_code }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Status</div>
                            <div class="fw-semibold">{{ $shipment->shipment_status }}</div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted small">Route</div>
                            <div class="fw-semibold">
                                {{ $shipment->originPort->port_name ?? '-' }}
                                <i class="bi bi-arrow-right mx-2 text-muted"></i>
                                {{ $shipment->destinationPort->port_name ?? '-' }}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Departure</div>
                            <div class="fw-semibold">{{ $shipment->departure_date?->format('d M Y') ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Est. Arrival</div>
                            <div class="fw-semibold">{{ $shipment->estimated_arrival?->format('d M Y') ?? '-' }}</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top p-3 text-muted small" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                    Generated on: {{ $recommendation->updated_at->format('d M Y, H:i:s') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
