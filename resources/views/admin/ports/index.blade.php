@extends('layouts.admin')

@push('styles')
<style>
    .admin-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        background-color: #fff;
    }
    .admin-card-title { font-size: 0.85rem; font-weight: 600; color: #666; margin-bottom: 5px; }
    .admin-card-value { font-size: 1.8rem; font-weight: 700; color: #333; margin-bottom: 0; }
    .admin-card-sub { font-size: 0.75rem; color: #888; font-weight: 500; }
    .admin-icon-box {
        width: 48px; height: 48px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }
    
    .toolbar-container {
        display: flex; gap: 15px; flex-wrap: wrap; align-items: center; justify-content: space-between;
    }
    .search-box { position: relative; flex-grow: 1; max-width: 350px; }
    .search-box input { padding-left: 35px; border-radius: 8px; border: 1px solid #ddd; }
    .search-box i { position: absolute; left: 12px; top: 10px; color: #888; }
    
    .admin-table { margin-bottom: 0; }
    .admin-table th { font-size: 0.7rem; text-transform: uppercase; color: #888; font-weight: 600; background-color: #fcfcfc; border-bottom: 1px solid #eee; padding: 10px 8px; }
    .admin-table td { font-size: 0.75rem; vertical-align: middle; border-bottom: 1px solid #f9f9f9; padding: 10px 8px; cursor: pointer; }
    .admin-table tbody tr:hover { background-color: #f9f9ff; }
    .admin-table th:first-child, .admin-table td:first-child { padding-left: 20px; }
    
    .action-btn {
        width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;
        border-radius: 6px; border: 1px solid #eee; color: #666; background: #fff; transition: 0.2s;
    }
    .action-btn:hover { background: #f4f4f4; color: #333; }
    
    .action-btn:hover { background: #f4f4f4; color: #333; }
    
    .btn-navy {
        background-color: #3E53A0;
        color: white;
        border: none;
    }
    .btn-navy:hover {
        background-color: #2c3e80;
        color: white;
    }
    
    /* Side Panel Styles */
    #portDetailPanel {
        transition: opacity 0.3s ease;
    }
    .port-icon {
        width: 60px; height: 60px; font-size: 1.5rem;
        border-radius: 12px; background-color: #e8eaf6; color: #3f51b5;
        display: flex; align-items: center; justify-content: center;
        margin-right: 15px;
    }
    .map-container {
        width: 100%; height: 200px; border-radius: 8px; overflow: hidden; border: 1px solid #eee;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-2">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">{{ __('Port Dataset Management') }}</h4>
            <div class="d-flex align-items-center gap-3 mt-2">
                <p class="text-muted mb-0" style="font-size: 0.9rem;">{{ __('Manage global port dataset used for mapping and risk analysis') }}</p>
                <div class="d-flex align-items-center gap-2 bg-white border rounded-pill px-3 py-1 shadow-sm">
                    <i data-lucide="anchor" style="width: 14px; color: #3E53A0;"></i>
                    <span class="fw-bold text-dark" style="font-size: 0.8rem;">{{ number_format($totalPorts) }}</span>
                    <span class="text-muted" style="font-size: 0.8rem;">Total Ports</span>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs -->


    <!-- Toolbar -->
    <div class="admin-card card p-3 mb-3">
        <form method="GET" action="{{ route('admin.ports.index') }}" class="d-flex flex-wrap gap-3 align-items-end justify-content-between">
            <div class="search-box" style="flex-grow: 1; max-width: 350px;">
                <i class="bi bi-search" style="left: 15px;"></i>
                <input type="text" name="search" class="form-control rounded-pill ps-5 pe-3" placeholder="{{ __('Search ports by name or code...') }}" value="{{ request('search') }}">
            </div>
            
            <div class="d-flex gap-3 align-items-end">
                <div class="d-flex flex-column">
                    <label style="font-size:0.7rem; color:#888; font-weight:600; margin-bottom:5px; padding-left:10px;">{{ __('COUNTRY') }}</label>
                    <select name="country_id" class="form-select rounded-pill px-3" style="min-width: 180px;">
                        <option value="all" {{ request('country_id') == 'all' ? 'selected' : '' }}>{{ __('All Countries') }}</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ request('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" class="btn btn-navy rounded-pill px-4" style="display: flex; align-items: center; gap: 5px;">
                    <i class="bi bi-funnel"></i>{{ __('Filter') }}</button>
            </div>

            <div class="ms-auto">
                <a href="{{ route('admin.ports.create') }}" class="btn btn-navy rounded-pill px-4">
                    <i class="bi bi-plus-lg me-1"></i>{{ __('Add New Port') }}</a>
            </div>
        </form>
    </div>

    <div class="row g-3">
        <!-- Main Table (100% Width) -->
        <div class="col-12">
            <div class="admin-card card p-0 h-100">
                <div class="card-header bg-transparent border-0 px-4 pt-4 pb-2 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold m-0">{{ __('Port List') }}</h6>
                    <span class="text-muted" style="font-size:0.75rem;">Showing {{ $ports->firstItem() ?? 0 }} to {{ $ports->lastItem() ?? 0 }} of {{ $ports->total() }} ports</span>
                </div>
        
                <div class="table-responsive">
                    <table class="table admin-table" id="portsTable">
                        <thead>
                            <tr>
                                <th style="width:30px;"><input class="form-check-input" type="checkbox"></th>
                                <th>{{ __('Port Name') }}</th>
                                <th>{{ __('Port Code') }}</th>
                                <th>{{ __('COUNTRY') }}</th>
                                <th>{{ __('LATITUDE') }}</th>
                                <th>{{ __('LONGITUDE') }}</th>
                                <th class="text-center">{{ __('ACTION') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ports as $port)
                            <tr>
                                <td><input class="form-check-input" type="checkbox"></td>
                                <td>
                                    <div class="fw-medium text-dark">{{ $port->name }}</div>
                                    <div style="font-size:0.65rem; color:#888;">{{ $port->country->region ?? 'Global' }}</div>
                                </td>
                                <td><span class="badge bg-light text-dark border px-2 py-1">{{ $port->unlocode }}</span></td>
                                <td>
                                    @if($port->country)
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="https://flagcdn.com/w20/{{ strtolower($port->country->iso2_code) }}.png" style="width:16px;" class="border rounded-1">
                                            <span>{{ $port->country->name }}</span>
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td><span class="text-muted" style="font-family: monospace;">{{ number_format($port->latitude, 4) }}</span></td>
                                <td><span class="text-muted" style="font-family: monospace;">{{ number_format($port->longitude, 4) }}</span></td>
                                <td class="text-center" style="white-space:nowrap;">
                                    <a href="{{ route('admin.ports.edit', $port->id) }}" class="action-btn me-1" title="Edit">
                                        <i data-lucide="edit-2" style="width: 16px;"></i>
                                    </a>
                                    <form action="{{ route('admin.ports.destroy', $port->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this port?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn text-danger" title="Delete" style="border-color: #fee2e2;">
                                            <i data-lucide="trash-2" style="width: 16px;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-light"></i>
                                    No ports found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($ports->hasPages())
                <div class="card-footer bg-transparent border-0 p-3 d-flex justify-content-center">
                    {{ $ports->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    const portsData = @json($ports->items());
</script>
<script>
    lucide.createIcons();
</script>
@endsection