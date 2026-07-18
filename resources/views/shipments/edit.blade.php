@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0"><i class="bi bi-pencil-square me-2"></i>Edit Shipment</h2>
        <a href="{{ route('shipments.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
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
        <div class="card-header bg-white border-bottom p-4" style="border-top-left-radius:16px;border-top-right-radius:16px;">
            <span class="fw-bold text-muted">Shipment Code:</span>
            <span class="font-monospace fw-bold ms-2">{{ $shipment->shipment_code }}</span>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('shipments.update', $shipment->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-4">

                    {{-- Origin --}}
                    <div class="col-12"><h6 class="text-muted text-uppercase fw-bold border-bottom pb-2">Origin</h6></div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Origin Country <span class="text-danger">*</span></label>
                        <select name="origin_country_id" class="form-select" required>
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}"
                                    {{ old('origin_country_id', $shipment->origin_country_id) == $country->id ? 'selected' : '' }}>
                                    {{ $country->country_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Origin Port <span class="text-danger">*</span></label>
                        <select name="origin_port_id" class="form-select" required>
                            <option value="">Select Port</option>
                            @foreach($ports as $port)
                                <option value="{{ $port->id }}"
                                    {{ old('origin_port_id', $shipment->origin_port_id) == $port->id ? 'selected' : '' }}>
                                    {{ $port->port_name }} ({{ $port->port_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Destination --}}
                    <div class="col-12"><h6 class="text-muted text-uppercase fw-bold border-bottom pb-2">Destination</h6></div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Destination Country <span class="text-danger">*</span></label>
                        <select name="destination_country_id" class="form-select" required>
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}"
                                    {{ old('destination_country_id', $shipment->destination_country_id) == $country->id ? 'selected' : '' }}>
                                    {{ $country->country_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Destination Port <span class="text-danger">*</span></label>
                        <select name="destination_port_id" class="form-select" required>
                            <option value="">Select Port</option>
                            @foreach($ports as $port)
                                <option value="{{ $port->id }}"
                                    {{ old('destination_port_id', $shipment->destination_port_id) == $port->id ? 'selected' : '' }}>
                                    {{ $port->port_name }} ({{ $port->port_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Cargo --}}
                    <div class="col-12"><h6 class="text-muted text-uppercase fw-bold border-bottom pb-2">Cargo</h6></div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Cargo Type <span class="text-danger">*</span></label>
                        <select name="cargo_type" class="form-select" required>
                            @foreach($cargoTypes as $type)
                                <option value="{{ $type }}"
                                    {{ old('cargo_type', $shipment->cargo_type) == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                        <select name="shipment_status" class="form-select" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}"
                                    {{ old('shipment_status', $shipment->shipment_status) == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Cargo Description</label>
                        <textarea name="cargo_description" class="form-control" rows="3">{{ old('cargo_description', $shipment->cargo_description) }}</textarea>
                    </div>

                    {{-- Schedule --}}
                    <div class="col-12"><h6 class="text-muted text-uppercase fw-bold border-bottom pb-2">Schedule</h6></div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Departure Date <span class="text-danger">*</span></label>
                        <input type="date" name="departure_date" class="form-control"
                               value="{{ old('departure_date', $shipment->departure_date?->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Estimated Arrival <span class="text-danger">*</span></label>
                        <input type="date" name="estimated_arrival" class="form-control"
                               value="{{ old('estimated_arrival', $shipment->estimated_arrival?->format('Y-m-d')) }}" required>
                    </div>

                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('shipments.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Update Shipment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
