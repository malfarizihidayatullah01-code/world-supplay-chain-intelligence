@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #2563EB;
        --primary-hover: #1D4ED8;
        --text-main: #0F172A;
        --radius: 12px;
    }
    .txt-title { font-family: 'Inter', sans-serif; font-size: 28px; font-weight: 700; color: var(--text-main); letter-spacing: -0.5px; }
    .btn-ent-primary {
        background-color: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius);
        padding: 10px 24px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
        text-decoration: none;
    }
    .btn-ent-primary:hover {
        background-color: var(--primary-hover);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
    }
    
    .btn-action {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        border: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        background: transparent;
        font-size: 15px;
    }
    .btn-action-text {
        width: auto;
        padding: 0 16px;
        font-size: 13px;
        font-weight: 600;
        gap: 6px;
    }
    .btn-action.action-view {
        color: #2563EB;
        background: #EFF6FF;
    }
    .btn-action.action-view:hover {
        background: #2563EB;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
    }
    .btn-action.action-edit {
        color: #D97706;
        background: #FEF3C7;
    }
    .btn-action.action-edit:hover {
        background: #D97706;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(217, 119, 6, 0.2);
    }
    .btn-action.action-delete {
        color: #DC2626;
        background: #FEE2E2;
    }
    .btn-action.action-delete:hover {
        background: #DC2626;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.2);
    }
</style>
@endpush

@section('content')
<div class="row mb-4 align-items-center mt-2">
    <div class="col d-flex justify-content-between align-items-center">
        <h2 class="txt-title m-0">{{ __('My Shipments') }}</h2>
        <a href="{{ route('user.shipments.create') }}" class="btn-ent-primary d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i> {{ __('Create Shipment') }}
        </a>
    </div>
</div>


<!-- Search & Filter Card -->
<div class="card border-0 mb-4 rounded-4" style="background-color: var(--surface-color); border: 1px solid var(--border-color) !important; box-shadow: var(--shadow-global);">
    <div class="card-body p-4">
        <form action="{{ route('user.shipments.index') }}" method="GET" id="filterForm">
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label text-muted small fw-bold">{{ __('Search') }}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 bg-light" placeholder="{{ __('Code, Country, Port...') }}" value="{{ $filters['search'] ?? '' }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold">{{ __('Status') }}</label>
                    <select name="status" class="form-select bg-light">
                        <option value="All" {{ ($filters['status'] ?? '') == 'All' ? 'selected' : '' }}>{{ __('All Status') }}</option>
                        <option value="Pending" {{ ($filters['status'] ?? '') == 'Pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                        <option value="In Transit" {{ ($filters['status'] ?? '') == 'In Transit' ? 'selected' : '' }}>{{ __('In Transit') }}</option>
                        <option value="Delayed" {{ ($filters['status'] ?? '') == 'Delayed' ? 'selected' : '' }}>{{ __('Delayed') }}</option>
                        <option value="Delivered" {{ ($filters['status'] ?? '') == 'Delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                        <option value="Cancelled" {{ ($filters['status'] ?? '') == 'Cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold">{{ __('Risk Level') }}</label>
                    <select name="risk_level" class="form-select bg-light">
                        <option value="All" {{ ($filters['risk_level'] ?? '') == 'All' ? 'selected' : '' }}>{{ __('All Risks') }}</option>
                        <option value="Low" {{ ($filters['risk_level'] ?? '') == 'Low' ? 'selected' : '' }}>{{ __('Low') }}</option>
                        <option value="Medium" {{ ($filters['risk_level'] ?? '') == 'Medium' ? 'selected' : '' }}>{{ __('Medium') }}</option>
                        <option value="High" {{ ($filters['risk_level'] ?? '') == 'High' ? 'selected' : '' }}>{{ __('High') }}</option>
                        <option value="Critical" {{ ($filters['risk_level'] ?? '') == 'Critical' ? 'selected' : '' }}>{{ __('Critical') }}</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn w-100 fw-bold" id="btnFilter" style="background-color: var(--primary); color: white; border: none; border-radius: var(--radius-button); transition: all var(--transition-speed);">
                        <span class="spinner-border spinner-border-sm d-none" id="filterSpinner" role="status" aria-hidden="true"></span>
                        <span id="filterText">{{ __('Filter') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 rounded-4 overflow-hidden" style="border: 1px solid var(--border-color) !important; box-shadow: var(--shadow-global);">
    <div class="card-body p-0" id="tableContainer">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-sm mb-0">
                <style>
                    .custom-thead th {
                        background-color: var(--surface-color) !important;
                        color: var(--text-primary) !important;
                        border-bottom: 2px solid var(--border-color) !important;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                        font-size: 0.75rem;
                        padding-top: 16px !important;
                        padding-bottom: 16px !important;
                    }
                    .table-hover tbody tr:hover {
                        background-color: rgba(37, 99, 235, 0.02) !important;
                    }
                </style>
                <thead class="custom-thead" style="font-size: 0.85rem;">
                    <tr>
                        <th class="ps-4 py-3 border-bottom-0">{{ __('Shipment Code') }}</th>
                        <th class="py-3 border-bottom-0">{{ __('Goods') }}</th>
                        <th class="py-3 border-bottom-0">{{ __('Route') }}</th>
                        <th class="py-3 border-bottom-0">{{ __('Current Stage') }}</th>
                        <th class="py-3 border-bottom-0">{{ __('Risk Level') }}</th>
                        <th class="py-3 border-bottom-0">{{ __('ETA') }}</th>
                        <th class="py-3 border-bottom-0">{{ __('Status') }}</th>
                        <th class="py-3 border-bottom-0">{{ __('Last Updated') }}</th>
                        <th class="pe-4 py-3 border-bottom-0 text-end">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @if($shipments->isEmpty())
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="empty-state d-flex flex-column align-items-center justify-content-center text-muted">
                                    <div class="rounded-circle bg-light p-4 mb-3 d-inline-flex">
                                        <i class="bi bi-box-seam fs-1 text-secondary opacity-50"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-1">{{ __('No Shipment Available') }}</h5>
                                    <p class="small mb-0">{{ __('We couldn\'t find any shipments matching your criteria.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @else
                        @foreach($shipments as $shipment)
                            @php
                                $mon = $shipment->monitoring;
                                $statusColor = $mon['current_status'] === 'Delayed' ? 'warning' : ($mon['current_status'] === 'At Risk' ? 'danger' : 'success');
                                $levelColor = $mon['risk_level'] === 'Critical' ? 'danger' : ($mon['risk_level'] === 'High' ? 'warning' : ($mon['risk_level'] === 'Medium' ? 'info' : 'success'));
                                $eta = $shipment->estimated_arrival ? \Carbon\Carbon::parse($shipment->estimated_arrival)->format('d M Y') : 'N/A';
                            @endphp
                            <tr>
                                <td class="ps-4 py-3 fw-bold text-primary">{{ $shipment->shipment_code }}</td>
                                <td class="py-3 text-dark fw-medium">{{ $shipment->goods ?? '-' }}</td>
                                <td class="py-3">
                                    <div class="d-flex flex-row align-items-center gap-2" style="font-size:0.85rem">
                                        <span class="text-dark">{{ $mon['origin']['country'] }}</span>
                                        <i class="bi bi-arrow-right text-muted" style="font-size: 0.7rem;"></i>
                                        <span class="text-dark">{{ $mon['destination']['country'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-dark">{{ $mon['destination']['port'] }}</td>
                                <td class="py-3"><span class="badge bg-{{ $levelColor }} px-2 py-1">{{ __($mon['risk_level']) }}</span></td>
                                <td class="py-3 text-dark">{{ $eta }}</td>
                                <td class="py-3"><span class="badge bg-light-{{ $statusColor }} text-{{ $statusColor }} px-2 py-1">{{ __($mon['current_status']) }}</span></td>
                                <td class="py-3 text-muted" style="font-size:0.8rem">{{ \Carbon\Carbon::parse($mon['last_updated'])->format('d M Y H:i') }}</td>
                                <td class="pe-4 py-3 text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('user.shipments.show', $shipment->id) }}" class="btn-action btn-action-text action-view" title="Monitor">
                                            <i class="bi bi-eye"></i> {{ __('Monitor') }}
                                        </a>
                                        <a href="{{ route('user.shipments.edit', $shipment->id) }}" class="btn-action action-edit" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('user.shipments.destroy', $shipment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this shipment?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action action-delete" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($shipments->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-end">
            {{ $shipments->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.getElementById('filterForm').addEventListener('submit', function() {
        document.getElementById('filterText').classList.add('d-none');
        document.getElementById('filterSpinner').classList.remove('d-none');
        document.getElementById('tableContainer').style.opacity = '0.6';
    });
</script>
@endpush
@endsection