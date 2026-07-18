@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Add Port</h2>
        <a href="{{ route('ports.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
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
            <form action="{{ route('ports.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Port Name <span class="text-danger">*</span></label>
                        <input type="text" name="port_name" class="form-control" value="{{ old('port_name') }}" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">UN/LOCODE (Port Code) <span class="text-danger">*</span></label>
                        <input type="text" name="port_code" class="form-control" maxlength="20" value="{{ old('port_code') }}" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Country <span class="text-danger">*</span></label>
                        <select name="country_id" class="form-select" required>
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                    {{ $country->country_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">City <span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Latitude <span class="text-danger">*</span></label>
                        <input type="number" step="any" name="latitude" class="form-control" value="{{ old('latitude') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Longitude <span class="text-danger">*</span></label>
                        <input type="number" step="any" name="longitude" class="form-control" value="{{ old('longitude') }}" required>
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
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save"></i> Save Port</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
