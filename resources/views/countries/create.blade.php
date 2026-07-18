@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Add Country</h2>
        <a href="{{ route('countries.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
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
            <form action="{{ route('countries.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Country Name <span class="text-danger">*</span></label>
                        <input type="text" name="country_name" class="form-control" value="{{ old('country_name') }}" required>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label fw-bold">ISO2 Code <span class="text-danger">*</span></label>
                        <input type="text" name="iso2_code" class="form-control" maxlength="2" value="{{ old('iso2_code') }}" required>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label fw-bold">ISO3 Code <span class="text-danger">*</span></label>
                        <input type="text" name="iso3_code" class="form-control" maxlength="3" value="{{ old('iso3_code') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Capital City <span class="text-danger">*</span></label>
                        <input type="text" name="capital_city" class="form-control" value="{{ old('capital_city') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Region <span class="text-danger">*</span></label>
                        <input type="text" name="region" class="form-control" value="{{ old('region') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Sub Region</label>
                        <input type="text" name="sub_region" class="form-control" value="{{ old('sub_region') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Currency Code</label>
                        <input type="text" name="currency_code" class="form-control" maxlength="3" value="{{ old('currency_code') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Currency Name</label>
                        <input type="text" name="currency_name" class="form-control" value="{{ old('currency_name') }}">
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
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save"></i> Save Country</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
