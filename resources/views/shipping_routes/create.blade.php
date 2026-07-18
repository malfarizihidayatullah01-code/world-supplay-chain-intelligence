@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Add Shipping Route</h2>
        <a href="{{ route('shipping-routes.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm" style="border-radius: 12px;">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        <div class="card-body p-4">
            <form action="{{ route('shipping-routes.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Route Code <span class="text-danger">*</span></label>
                        <input type="text" name="route_code" class="form-control" value="{{ old('route_code') }}" placeholder="e.g. IDTPP-SGSIN-01" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Origin Port <span class="text-danger">*</span></label>
                        <select name="origin_port_id" class="form-select" required>
                            <option value="">Select Origin Port</option>
                            @foreach($ports as $port)
                                <option value="{{ $port->id }}" {{ old('origin_port_id') == $port->id ? 'selected' : '' }}>
                                    {{ $port->port_name }} ({{ $port->port_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Destination Port <span class="text-danger">*</span></label>
                        <select name="destination_port_id" class="form-select" required>
                            <option value="">Select Destination Port</option>
                            @foreach($ports as $port)
                                <option value="{{ $port->id }}" {{ old('destination_port_id') == $port->id ? 'selected' : '' }}>
                                    {{ $port->port_name }} ({{ $port->port_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save"></i> Save Route</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
