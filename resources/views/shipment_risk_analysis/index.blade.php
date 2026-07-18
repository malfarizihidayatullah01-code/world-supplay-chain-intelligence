@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0"><i class="bi bi-shield-check me-2"></i>Shipment Risk Analysis</h2>
        <form action="{{ route('shipment-risk-analysis.analyse-all') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-warning"
                    onclick="return confirm('Run analysis for ALL shipments? This will also update Route Risks.')">
                <i class="bi bi-lightning-charge me-1"></i> Analyse All Shipments
            </button>
        </form>
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

    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        {{-- Filters --}}
        <div class="card-header bg-white border-bottom p-4" style="border-top-left-radius:16px;border-top-right-radius:16px;">
            <form action="{{ route('shipment-risk-analysis.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="risk_level" class="form-select">
                        <option value="">All Risk Levels</option>
                        @foreach($riskLevels as $level)
                            <option value="{{ $level }}" {{ request('risk_level') == $level ? 'selected' : '' }}>
                                {{ $level }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('shipment-risk-analysis.index') }}" class="btn btn-outline-danger w-100">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Shipment</th>
                            <th>Route</th>
                            <th class="text-center">Route Score</th>
                            <th class="text-center">
                                <a href="?sort_by=shipment_risk_score&sort_dir={{ request('sort_dir') == 'asc' ? 'desc' : 'asc' }}&risk_level={{ request('risk_level') }}"
                                   class="text-decoration-none text-dark">
                                    Overall Score {!! request('sort_by') == 'shipment_risk_score' ? (request('sort_dir') == 'asc' ? '<i class="bi bi-caret-up-fill"></i>' : '<i class="bi bi-caret-down-fill"></i>') : '' !!}
                                </a>
                            </th>
                            <th class="text-center">Risk Level</th>
                            <th class="pe-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($analyses as $index => $analysis)
                        @php
                            $badgeClass = match($analysis->risk_level) {
                                'LOW'    => 'bg-success-subtle text-success',
                                'MEDIUM' => 'bg-warning-subtle text-warning',
                                'HIGH'   => 'bg-danger-subtle text-danger',
                                default  => 'bg-secondary-subtle text-secondary',
                            };
                        @endphp
                        <tr>
                            <td class="ps-4">{{ $analyses->firstItem() + $index }}</td>
                            <td class="font-monospace fw-bold">{{ $analysis->shipment->shipment_code ?? '-' }}</td>
                            <td>
                                <small>
                                    {{ $analysis->shipment->originPort->port_name ?? '-' }}
                                    <i class="bi bi-arrow-right text-muted"></i>
                                    {{ $analysis->shipment->destinationPort->port_name ?? '-' }}
                                </small>
                            </td>
                            <td class="text-center">{{ number_format($analysis->route_risk_score, 1) }}</td>
                            <td class="text-center fw-bold">{{ number_format($analysis->shipment_risk_score, 1) }}</td>
                            <td class="text-center">
                                <span class="badge {{ $badgeClass }}">{{ $analysis->risk_level }}</span>
                            </td>
                            <td class="pe-4 text-center">
                                <a href="{{ route('shipment-risk-analysis.show', $analysis->id) }}"
                                   class="btn btn-sm btn-outline-info" title="View Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-shield-x fs-2 d-block mb-2"></i>
                                No shipment risk analyses found. Run analysis from the Shipments page or click "Analyse All Shipments".
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top p-4"
             style="border-bottom-left-radius:16px;border-bottom-right-radius:16px;">
            {{ $analyses->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
