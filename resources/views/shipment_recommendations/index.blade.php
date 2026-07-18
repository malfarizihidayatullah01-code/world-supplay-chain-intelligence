@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0"><i class="bi bi-lightbulb me-2"></i>Shipment Recommendations</h2>
        <form action="{{ route('shipment-recommendations.analyse-all') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-warning"
                    onclick="return confirm('Generate recommendations for ALL shipments? This will also update all underlying risk analyses.')">
                <i class="bi bi-gear-wide-connected me-1"></i> Generate All Recommendations
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
            <form action="{{ route('shipment-recommendations.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="recommendation_status" class="form-select">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('recommendation_status') == $status ? 'selected' : '' }}>
                                {{ $status }}
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
                    <a href="{{ route('shipment-recommendations.index') }}" class="btn btn-outline-danger w-100">
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
                            <th class="text-center">Risk Score</th>
                            <th>Recommendation</th>
                            <th class="text-center">
                                <a href="?sort_by=recommendation_status&sort_dir={{ request('sort_dir') == 'asc' ? 'desc' : 'asc' }}&recommendation_status={{ request('recommendation_status') }}"
                                   class="text-decoration-none text-dark">
                                    Status {!! request('sort_by') == 'recommendation_status' ? (request('sort_dir') == 'asc' ? '<i class="bi bi-caret-up-fill"></i>' : '<i class="bi bi-caret-down-fill"></i>') : '' !!}
                                </a>
                            </th>
                            <th class="pe-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recommendations as $index => $rec)
                        @php
                            $badgeClass = match($rec->recommendation_status) {
                                'Approved'           => 'bg-success-subtle text-success',
                                'Monitoring'         => 'bg-warning-subtle text-warning',
                                'Attention Required' => 'bg-danger-subtle text-danger',
                                default              => 'bg-secondary-subtle text-secondary',
                            };
                        @endphp
                        <tr>
                            <td class="ps-4">{{ $recommendations->firstItem() + $index }}</td>
                            <td class="font-monospace fw-bold">{{ $rec->shipment->shipment_code ?? '-' }}</td>
                            <td>
                                <small>
                                    {{ $rec->shipment->originPort->port_name ?? '-' }}
                                    <i class="bi bi-arrow-right text-muted"></i>
                                    {{ $rec->shipment->destinationPort->port_name ?? '-' }}
                                </small>
                            </td>
                            <td class="text-center fw-bold">{{ number_format($rec->shipment_risk_score, 1) }}</td>
                            <td>{{ $rec->recommendation }}</td>
                            <td class="text-center">
                                <span class="badge {{ $badgeClass }}">{{ $rec->recommendation_status }}</span>
                            </td>
                            <td class="pe-4 text-center">
                                <a href="{{ route('shipment-recommendations.show', $rec->id) }}"
                                   class="btn btn-sm btn-outline-info" title="View Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-lightbulb-off fs-2 d-block mb-2"></i>
                                No recommendations found. Generate recommendations from the Shipments page.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top p-4"
             style="border-bottom-left-radius:16px;border-bottom-right-radius:16px;">
            {{ $recommendations->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
