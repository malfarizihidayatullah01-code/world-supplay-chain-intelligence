@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0"><i class="bi bi-box-seam me-2"></i>Shipment Detail</h2>
        <div>
            @if($shipment->routeRiskAnalysis)
                <a href="{{ route('route-risk-analysis.show', $shipment->routeRiskAnalysis->id) }}" class="btn btn-outline-info me-2">
                    <i class="bi bi-graph-up-arrow"></i> View Route Risk
                </a>
            @else
                <form action="{{ route('route-risk-analysis.analyse', $shipment->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-warning me-2">
                        <i class="bi bi-lightning-charge"></i> Analyse Route Risk
                    </button>
                </form>
            @endif

            @if($shipment->shipmentRiskAnalysis)
                <a href="{{ route('shipment-risk-analysis.show', $shipment->shipmentRiskAnalysis->id) }}" class="btn btn-outline-info me-2">
                    <i class="bi bi-shield-check"></i> View Overall Risk
                </a>
            @else
                <form action="{{ route('shipment-risk-analysis.analyse', $shipment->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-warning me-2">
                        <i class="bi bi-shield-exclamation"></i> Analyse Overall Risk
                    </button>
                </form>
            @endif
            @if($shipment->shipmentRecommendation)
                <a href="{{ route('shipment-recommendations.show', $shipment->shipmentRecommendation->id) }}" class="btn btn-outline-success me-2">
                    <i class="bi bi-lightbulb"></i> View Recommendation
                </a>
            @else
                <form action="{{ route('shipment-recommendations.analyse', $shipment->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="bi bi-gear-wide-connected"></i> Generate Recommendation
                    </button>
                </form>
            @endif

            <a href="{{ route('shipments.edit', $shipment->id) }}" class="btn btn-outline-primary me-2">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('shipments.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @php
        $badgeClass = match($shipment->shipment_status) {
            'Planning'   => 'bg-info-subtle text-info',
            'In Transit' => 'bg-primary-subtle text-primary',
            'Delivered'  => 'bg-success-subtle text-success',
            'Delayed'    => 'bg-warning-subtle text-warning',
            'Cancelled'  => 'bg-danger-subtle text-danger',
            default      => 'bg-secondary-subtle text-secondary',
        };
    @endphp

    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center"
             style="border-top-left-radius:16px;border-top-right-radius:16px;">
            <div>
                <span class="text-muted me-2">Shipment Code</span>
                <span class="font-monospace fw-bold fs-5">{{ $shipment->shipment_code }}</span>
            </div>
            <span class="badge {{ $badgeClass }} fs-6 px-3 py-2">{{ $shipment->shipment_status }}</span>
        </div>

        <div class="card-body p-4">
            <div class="row g-4">

                {{-- Origin --}}
                <div class="col-12"><h6 class="text-muted text-uppercase fw-bold border-bottom pb-2">Origin</h6></div>
                <div class="col-md-6">
                    <div class="text-muted small">Country</div>
                    <div class="fw-semibold">{{ $shipment->originCountry->country_name ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Port</div>
                    <div class="fw-semibold">
                        {{ $shipment->originPort->port_name ?? '-' }}
                        @if($shipment->originPort)
                            <span class="text-muted">({{ $shipment->originPort->port_code }})</span>
                        @endif
                    </div>
                </div>

                {{-- Destination --}}
                <div class="col-12"><h6 class="text-muted text-uppercase fw-bold border-bottom pb-2">Destination</h6></div>
                <div class="col-md-6">
                    <div class="text-muted small">Country</div>
                    <div class="fw-semibold">{{ $shipment->destinationCountry->country_name ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Port</div>
                    <div class="fw-semibold">
                        {{ $shipment->destinationPort->port_name ?? '-' }}
                        @if($shipment->destinationPort)
                            <span class="text-muted">({{ $shipment->destinationPort->port_code }})</span>
                        @endif
                    </div>
                </div>

                {{-- Cargo --}}
                <div class="col-12"><h6 class="text-muted text-uppercase fw-bold border-bottom pb-2">Cargo</h6></div>
                <div class="col-md-6">
                    <div class="text-muted small">Cargo Type</div>
                    <div class="fw-semibold">{{ $shipment->cargo_type }}</div>
                </div>
                <div class="col-12">
                    <div class="text-muted small">Description</div>
                    <div>{{ $shipment->cargo_description ?: '-' }}</div>
                </div>

                {{-- Schedule --}}
                <div class="col-12"><h6 class="text-muted text-uppercase fw-bold border-bottom pb-2">Schedule</h6></div>
                <div class="col-md-6">
                    <div class="text-muted small">Departure Date</div>
                    <div class="fw-semibold">{{ $shipment->departure_date?->format('d M Y') }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Estimated Arrival</div>
                    <div class="fw-semibold">{{ $shipment->estimated_arrival?->format('d M Y') }}</div>
                </div>

                {{-- Meta --}}
                <div class="col-12"><h6 class="text-muted text-uppercase fw-bold border-bottom pb-2">Record</h6></div>
                <div class="col-md-6">
                    <div class="text-muted small">Created</div>
                    <div>{{ $shipment->created_at->format('d M Y, H:i') }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Last Updated</div>
                    <div>{{ $shipment->updated_at->format('d M Y, H:i') }}</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
