@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0"><i class="bi bi-display me-2"></i>Shipment Monitoring Dashboard</h2>
    </div>

    {{-- KPI Summary --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-primary border-4" style="border-radius: 12px;">
                <div class="card-body px-4 py-3">
                    <div class="text-muted small fw-bold mb-1">TOTAL SHIPMENTS</div>
                    <div class="fs-3 fw-bold">{{ number_format($summary['total']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-success border-4" style="border-radius: 12px;">
                <div class="card-body px-4 py-3">
                    <div class="text-muted small fw-bold mb-1">APPROVED</div>
                    <div class="fs-3 fw-bold">{{ number_format($summary['approved']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-warning border-4" style="border-radius: 12px;">
                <div class="card-body px-4 py-3">
                    <div class="text-muted small fw-bold mb-1">MONITORING</div>
                    <div class="fs-3 fw-bold">{{ number_format($summary['monitoring']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-danger border-4" style="border-radius: 12px;">
                <div class="card-body px-4 py-3">
                    <div class="text-muted small fw-bold mb-1">ATTENTION REQUIRED</div>
                    <div class="fs-3 fw-bold">{{ number_format($summary['attention_required']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        {{-- Filters & Search --}}
        <div class="card-header bg-white border-bottom p-4" style="border-top-left-radius:16px;border-top-right-radius:16px;">
            <form action="{{ route('shipment-monitoring.index') }}" method="GET" class="row g-3">
                <div class="col-md-2">
                    <input type="text" name="shipment_code" class="form-control" placeholder="Shipment Code..." value="{{ request('shipment_code') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="origin_country" class="form-control" placeholder="Origin Country..." value="{{ request('origin_country') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="destination_country" class="form-control" placeholder="Dest Country..." value="{{ request('destination_country') }}">
                </div>
                
                <div class="col-md-2">
                    <select name="shipment_status" class="form-select">
                        <option value="">Status...</option>
                        @foreach($shipmentStatuses as $status)
                            <option value="{{ $status }}" {{ request('shipment_status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="risk_level" class="form-select">
                        <option value="">Risk Level...</option>
                        @foreach($riskLevels as $level)
                            <option value="{{ $level }}" {{ request('risk_level') == $level ? 'selected' : '' }}>{{ $level }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="recommendation_status" class="form-select">
                        <option value="">Recommendation...</option>
                        @foreach($recommendationStatuses as $recStatus)
                            <option value="{{ $recStatus }}" {{ request('recommendation_status') == $recStatus ? 'selected' : '' }}>{{ $recStatus }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-10 text-end">
                    <button type="submit" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-search me-1"></i> Search & Filter
                    </button>
                </div>
                <div class="col-md-2 text-end">
                    <a href="{{ route('shipment-monitoring.index') }}" class="btn btn-outline-danger w-100">
                        <i class="bi bi-x-circle me-1"></i> Clear
                    </a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="min-width: 1200px;">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">
                                <a href="?sort_by=shipment_code&sort_dir={{ request('sort_dir') == 'asc' ? 'desc' : 'asc' }}&{{ http_build_query(request()->except(['sort_by', 'sort_dir'])) }}" class="text-decoration-none text-dark">
                                    Shipment Code {!! request('sort_by') == 'shipment_code' ? (request('sort_dir') == 'asc' ? '<i class="bi bi-caret-up-fill"></i>' : '<i class="bi bi-caret-down-fill"></i>') : '' !!}
                                </a>
                            </th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Status</th>
                            <th>
                                <a href="?sort_by=departure_date&sort_dir={{ request('sort_dir') == 'asc' ? 'desc' : 'asc' }}&{{ http_build_query(request()->except(['sort_by', 'sort_dir'])) }}" class="text-decoration-none text-dark">
                                    Departure {!! request('sort_by') == 'departure_date' ? (request('sort_dir') == 'asc' ? '<i class="bi bi-caret-up-fill"></i>' : '<i class="bi bi-caret-down-fill"></i>') : '' !!}
                                </a>
                            </th>
                            <th class="text-center">
                                <a href="?sort_by=shipment_risk_score&sort_dir={{ request('sort_dir') == 'asc' ? 'desc' : 'asc' }}&{{ http_build_query(request()->except(['sort_by', 'sort_dir'])) }}" class="text-decoration-none text-dark">
                                    Risk Score {!! request('sort_by') == 'shipment_risk_score' ? (request('sort_dir') == 'asc' ? '<i class="bi bi-caret-up-fill"></i>' : '<i class="bi bi-caret-down-fill"></i>') : '' !!}
                                </a>
                            </th>
                            <th class="text-center">Rec. Status</th>
                            <th class="pe-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shipments as $shipment)
                        @php
                            $recStatus = $shipment->shipmentRecommendation->recommendation_status ?? 'N/A';
                            $recBadgeClass = match($recStatus) {
                                'Approved'           => 'bg-success-subtle text-success',
                                'Monitoring'         => 'bg-warning-subtle text-warning',
                                'Attention Required' => 'bg-danger-subtle text-danger',
                                default              => 'bg-secondary-subtle text-secondary',
                            };
                            $riskScore = $shipment->shipmentRiskAnalysis->shipment_risk_score ?? 0;
                        @endphp
                        <tr>
                            <td class="ps-4 font-monospace fw-bold">{{ $shipment->shipment_code }}</td>
                            <td>
                                <div class="fw-semibold">{{ $shipment->originCountry->country_name ?? '-' }}</div>
                                <div class="text-muted small">{{ $shipment->originPort->port_name ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $shipment->destinationCountry->country_name ?? '-' }}</div>
                                <div class="text-muted small">{{ $shipment->destinationPort->port_name ?? '-' }}</div>
                            </td>
                            <td>{{ $shipment->shipment_status }}</td>
                            <td>{{ $shipment->departure_date?->format('d M Y') ?? '-' }}</td>
                            <td class="text-center fw-bold">{{ number_format($riskScore, 1) }}</td>
                            <td class="text-center">
                                <span class="badge {{ $recBadgeClass }}">{{ $recStatus }}</span>
                            </td>
                            <td class="pe-4 text-center">
                                <a href="{{ route('shipment-monitoring.show', $shipment->id) }}" class="btn btn-sm btn-primary shadow-sm">
                                    <i class="bi bi-display"></i> View Monitoring
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-display fs-2 d-block mb-2"></i>
                                No shipments match the monitoring criteria.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top p-4" style="border-bottom-left-radius:16px;border-bottom-right-radius:16px;">
            {{ $shipments->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
