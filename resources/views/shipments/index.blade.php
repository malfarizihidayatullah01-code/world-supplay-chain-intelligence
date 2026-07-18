@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0"><i class="bi bi-box-seam me-2"></i>Shipments</h2>
        <a href="{{ route('shipments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add Shipment
        </a>
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
        <div class="card-header bg-white border-bottom p-4" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
            <form action="{{ route('shipments.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control"
                           placeholder="Search code or cargo…" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="shipment_status" class="form-select">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('shipment_status') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="origin_country_id" class="form-select">
                        <option value="">All Origin Countries</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ request('origin_country_id') == $country->id ? 'selected' : '' }}>
                                {{ $country->country_name }}
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
                    <a href="{{ route('shipments.index') }}" class="btn btn-outline-danger w-100">
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
                            <th>Code</th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Cargo Type</th>
                            <th>Departure</th>
                            <th>Est. Arrival</th>
                            <th>Status</th>
                            <th class="pe-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shipments as $index => $shipment)
                        <tr>
                            <td class="ps-4">{{ $shipments->firstItem() + $index }}</td>
                            <td class="fw-bold font-monospace">{{ $shipment->shipment_code }}</td>
                            <td>
                                <div class="fw-semibold">{{ $shipment->originPort->port_name ?? '-' }}</div>
                                <small class="text-muted">{{ $shipment->originCountry->country_name ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $shipment->destinationPort->port_name ?? '-' }}</div>
                                <small class="text-muted">{{ $shipment->destinationCountry->country_name ?? '-' }}</small>
                            </td>
                            <td>{{ $shipment->cargo_type }}</td>
                            <td>{{ $shipment->departure_date?->format('d M Y') }}</td>
                            <td>{{ $shipment->estimated_arrival?->format('d M Y') }}</td>
                            <td>
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
                                <span class="badge {{ $badgeClass }}">{{ $shipment->shipment_status }}</span>
                            </td>
                            <td class="pe-4 text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('shipments.show', $shipment->id) }}" class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('shipments.edit', $shipment->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('shipments.destroy', $shipment->id) }}" method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete shipment {{ $shipment->shipment_code }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                No shipments found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top p-4"
             style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
            {{ $shipments->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
