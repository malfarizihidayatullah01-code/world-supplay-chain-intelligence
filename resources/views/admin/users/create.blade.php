@extends('layouts.admin')

@push('styles')
<style>
    .saas-section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 8px;
    }
    .saas-section-desc {
        font-size: 0.9rem;
        color: #64748B;
        line-height: 1.5;
    }
    .saas-form-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    }
    .saas-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        display: block;
    }
    .saas-input {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        background: #F8FAFC;
        font-size: 0.95rem;
        color: #0F172A;
        transition: all 0.2s ease;
    }
    .saas-input:focus {
        background: #FFFFFF;
        border-color: #2962FF;
        outline: none;
        box-shadow: 0 0 0 4px rgba(41, 98, 255, 0.1);
    }
    .saas-input::placeholder {
        color: #94A3B8;
    }
    .saas-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 16px;
    }
    .saas-action-bar {
        border-top: 1px solid #E2E8F0;
        padding-top: 24px;
        margin-top: 40px;
        display: flex;
        justify-content: flex-end;
        gap: 16px;
    }
    .saas-btn-cancel {
        padding: 12px 24px;
        border-radius: 12px;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        color: #475569;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    .saas-btn-cancel:hover {
        background: #F1F5F9;
        color: #0F172A;
    }
    .saas-btn-submit {
        padding: 12px 32px;
        border-radius: 12px;
        background: #2962FF;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        border: none;
        box-shadow: 0 4px 12px rgba(41, 98, 255, 0.2);
        transition: all 0.3s ease;
    }
    .saas-btn-submit:hover {
        background: #1E4BD8;
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 px-xl-5 fade-in-up">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold text-dark mb-1">Create User</h2>
            <p class="text-secondary mb-0" style="font-size: 1rem;">Add a new member to the intelligence platform.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="saas-btn-cancel d-flex align-items-center gap-2">
            <i data-lucide="arrow-left" style="width: 16px;"></i> Back to Users
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="border-radius: 12px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #EF4444;">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        
        <!-- SECTION 1: Personal Info -->
        <div class="row mb-5">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="pe-lg-4">
                    <h3 class="saas-section-title">Personal Information</h3>
                    <p class="saas-section-desc">Basic details about the user. The email address will be used for login and notifications.</p>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="saas-form-card">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="saas-label">Full Name</label>
                            <input type="text" name="name" class="saas-input" value="{{ old('name') }}" placeholder="e.g. John Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label class="saas-label">Email Address</label>
                            <input type="email" name="email" class="saas-input" value="{{ old('email') }}" placeholder="e.g. john@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="saas-label">Password</label>
                            <input type="password" name="password" class="saas-input" placeholder="Minimum 8 characters" required>
                        </div>
                        <div class="col-md-6">
                            <label class="saas-label">Country (Origin)</label>
                            <select name="country_id" class="saas-input saas-select" required>
                                <option value="">-- Select Country --</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <hr style="border-color: #E2E8F0; margin: 2rem 0;">

        <!-- SECTION 2: Access & Permissions -->
        <div class="row mb-4">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="pe-lg-4">
                    <h3 class="saas-section-title">Access & Permissions</h3>
                    <p class="saas-section-desc">Define what this user can see and do within the application. Administrators have full system access.</p>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="saas-form-card">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="saas-label">System Role</label>
                            <select name="role" class="saas-input saas-select" required>
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User (Standard Access)</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator (Full Access)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="saas-label">Account Status</label>
                            <select name="status" class="saas-input saas-select" required>
                                <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active (Can Login)</option>
                                <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive (Suspended)</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="saas-action-bar">
                    <a href="{{ route('admin.users.index') }}" class="saas-btn-cancel">Cancel</a>
                    <button type="submit" class="saas-btn-submit">Create User</button>
                </div>
            </div>
        </div>
        
    </form>
</div>
@endsection
