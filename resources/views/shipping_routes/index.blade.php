@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Shipping Routes</h2>
        <a href="{{ route('shipping-routes.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Route</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        <div class="card-header bg-white border-bottom p-4" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
            <form action="{{ route('shipping-routes.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search Route Code..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="origin_port_id" class="form-select">
                        <option value="">All Origin Ports</option>
                        @foreach($ports as $port)
                            <option value="{{ $port->id }}" {{ request('origin_port_id') == $port->id ? 'selected' : '' }}>{{ $port->port_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="destination_port_id" class="form-select">
                        <option value="">All Destination Ports</option>
                        @foreach($ports as $port)
                            <option value="{{ $port->id }}" {{ request('destination_port_id') == $port->id ? 'selected' : '' }}>{{ $port->port_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-outline-secondary w-100"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>
                                <a href="?sort_by=route_code&sort_dir={{ request('sort_dir') == 'asc' ? 'desc' : 'asc' }}&search={{ request('search') }}&origin_port_id={{ request('origin_port_id') }}&destination_port_id={{ request('destination_port_id') }}&status={{ request('status') }}" class="text-decoration-none text-dark">
                                    Route Code {!! request('sort_by') == 'route_code' ? (request('sort_dir') == 'asc' ? '<i class="bi bi-caret-up-fill"></i>' : '<i class="bi bi-caret-down-fill"></i>') : '' !!}
                                </a>
                            </th>
                            <th>
                                <a href="?sort_by=origin_port_id&sort_dir={{ request('sort_dir') == 'asc' ? 'desc' : 'asc' }}&search={{ request('search') }}&origin_port_id={{ request('origin_port_id') }}&destination_port_id={{ request('destination_port_id') }}&status={{ request('status') }}" class="text-decoration-none text-dark">
                                    Origin Port {!! request('sort_by') == 'origin_port_id' ? (request('sort_dir') == 'asc' ? '<i class="bi bi-caret-up-fill"></i>' : '<i class="bi bi-caret-down-fill"></i>') : '' !!}
                                </a>
                            </th>
                            <th>
                                <a href="?sort_by=destination_port_id&sort_dir={{ request('sort_dir') == 'asc' ? 'desc' : 'asc' }}&search={{ request('search') }}&origin_port_id={{ request('origin_port_id') }}&destination_port_id={{ request('destination_port_id') }}&status={{ request('status') }}" class="text-decoration-none text-dark">
                                    Destination Port {!! request('sort_by') == 'destination_port_id' ? (request('sort_dir') == 'asc' ? '<i class="bi bi-caret-up-fill"></i>' : '<i class="bi bi-caret-down-fill"></i>') : '' !!}
                                </a>
                            </th>
                            <th>Status</th>
                            <th class="pe-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shippingRoutes as $index => $route)
                        <tr>
                            <td class="ps-4">{{ $shippingRoutes->firstItem() + $index }}</td>
                            <td class="fw-bold text-primary">{{ $route->route_code }}</td>
                            <td>
                                {{ $route->originPort->port_name ?? '-' }}
                                <br><small class="text-muted">{{ $route->originPort->port_code ?? '' }}</small>
                            </td>
                            <td>
                                {{ $route->destinationPort->port_name ?? '-' }}
                                <br><small class="text-muted">{{ $route->destinationPort->port_code ?? '' }}</small>
                            </td>
                            <td>
                                @if($route->status == 'Active')
                                    <span class="badge bg-success-subtle text-success">Active</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="pe-4 text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('shipping-routes.edit', $route->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('shipping-routes.destroy', $route->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this route?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No shipping routes found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white border-top p-4" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
            {{ $shippingRoutes->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
