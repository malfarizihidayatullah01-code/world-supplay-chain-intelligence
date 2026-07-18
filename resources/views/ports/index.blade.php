@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Ports</h2>
        <a href="{{ route('ports.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Port</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        <div class="card-header bg-white border-bottom p-4" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
            <form action="{{ route('ports.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, UN/LOCODE, or city" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="country_id" class="form-select">
                        <option value="">All Countries</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ request('country_id') == $country->id ? 'selected' : '' }}>{{ $country->country_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-secondary w-100"><i class="bi bi-search"></i> Filter</button>
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
                                <a href="?sort_by=port_code&sort_dir={{ request('sort_dir') == 'asc' ? 'desc' : 'asc' }}&search={{ request('search') }}&country_id={{ request('country_id') }}&status={{ request('status') }}" class="text-decoration-none text-dark">
                                    UN/LOCODE {!! request('sort_by') == 'port_code' ? (request('sort_dir') == 'asc' ? '<i class="bi bi-caret-up-fill"></i>' : '<i class="bi bi-caret-down-fill"></i>') : '' !!}
                                </a>
                            </th>
                            <th>
                                <a href="?sort_by=port_name&sort_dir={{ request('sort_dir') == 'asc' ? 'desc' : 'asc' }}&search={{ request('search') }}&country_id={{ request('country_id') }}&status={{ request('status') }}" class="text-decoration-none text-dark">
                                    Port Name {!! request('sort_by') == 'port_name' ? (request('sort_dir') == 'asc' ? '<i class="bi bi-caret-up-fill"></i>' : '<i class="bi bi-caret-down-fill"></i>') : '' !!}
                                </a>
                            </th>
                            <th>
                                <a href="?sort_by=country_id&sort_dir={{ request('sort_dir') == 'asc' ? 'desc' : 'asc' }}&search={{ request('search') }}&country_id={{ request('country_id') }}&status={{ request('status') }}" class="text-decoration-none text-dark">
                                    Country {!! request('sort_by') == 'country_id' ? (request('sort_dir') == 'asc' ? '<i class="bi bi-caret-up-fill"></i>' : '<i class="bi bi-caret-down-fill"></i>') : '' !!}
                                </a>
                            </th>
                            <th>
                                <a href="?sort_by=city&sort_dir={{ request('sort_dir') == 'asc' ? 'desc' : 'asc' }}&search={{ request('search') }}&country_id={{ request('country_id') }}&status={{ request('status') }}" class="text-decoration-none text-dark">
                                    City {!! request('sort_by') == 'city' ? (request('sort_dir') == 'asc' ? '<i class="bi bi-caret-up-fill"></i>' : '<i class="bi bi-caret-down-fill"></i>') : '' !!}
                                </a>
                            </th>
                            <th>Coordinates</th>
                            <th>Status</th>
                            <th class="pe-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ports as $index => $port)
                        <tr>
                            <td class="ps-4">{{ $ports->firstItem() + $index }}</td>
                            <td class="fw-bold">{{ $port->port_code }}</td>
                            <td>{{ $port->port_name }}</td>
                            <td>{{ $port->country->country_name ?? '-' }}</td>
                            <td>{{ $port->city }}</td>
                            <td>
                                <small class="text-muted">{{ $port->latitude }}, {{ $port->longitude }}</small>
                            </td>
                            <td>
                                @if($port->status == 'Active')
                                    <span class="badge bg-success-subtle text-success">Active</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="pe-4 text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('ports.edit', $port->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('ports.destroy', $port->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this port?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No ports found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white border-top p-4" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
            {{ $ports->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
