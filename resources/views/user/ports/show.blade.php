@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    @if(!$port)
        <div class="alert alert-warning border-0 rounded-4 shadow-sm p-4 d-flex align-items-center gap-3">
            <i class="bi bi-exclamation-triangle fs-3 text-warning"></i>
            <div>
                <h5 class="fw-bold mb-1">{{ __('Not Found') }}</h5>
                <p class="mb-0 text-muted">{{ __('Port information could not be found.') }}</p>
            </div>
            <a href="{{ url('/user/ports') }}" class="btn btn-secondary ms-auto">{{ __('Back to Ports') }}</a>
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 rounded-4 h-100" style="border: 1px solid var(--border-color) !important; box-shadow: var(--shadow-global);">
                    
                    <!-- Unified Header Inside Card -->
                    <div class="card-header bg-white border-0 p-4 pb-0">
                        <a href="{{ url('/user/ports') }}" class="text-decoration-none text-muted mb-2 d-inline-block" style="font-size: 0.85rem;">
                            <i class="bi bi-arrow-left me-1"></i> {{ __('Back to Ports') }}
                        </a>
                        <h2 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-water text-primary fs-3"></i> 
                            {{ $port->name }}
                        </h2>
                    </div>

                    <div class="card-body p-4">
                        
                        <h6 class="fw-bold mb-4 text-dark"><i class="bi bi-info-circle text-primary me-2"></i>{{ __('General Information') }}</h6>
                        
                        <div class="d-flex flex-column gap-4">
                            <!-- Country -->
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                                    <i class="bi bi-globe-americas fs-5"></i>
                                </div>
                                <div>
                                    <p class="text-muted small fw-semibold text-uppercase mb-1" style="letter-spacing: 0.5px;">{{ __('Country') }}</p>
                                    <h6 class="fw-bold text-dark mb-0">
                                        @if($port->country && $port->country->iso2_code)
                                            <img src="https://flagcdn.com/24x18/{{ strtolower($port->country->iso2_code) }}.png" alt="Flag" class="me-2 rounded shadow-sm">
                                        @endif
                                        {{ $port->country->name ?? '-' }}
                                    </h6>
                                </div>
                            </div>

                            <!-- Region -->
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                                    <i class="bi bi-map fs-5"></i>
                                </div>
                                <div>
                                    <p class="text-muted small fw-semibold text-uppercase mb-1" style="letter-spacing: 0.5px;">{{ __('Region') }}</p>
                                    <h6 class="fw-bold text-dark mb-0">{{ $port->country->region ?? '-' }}</h6>
                                </div>
                            </div>
                            
                            <hr class="text-muted opacity-25 my-1">

                            <!-- Coordinates -->
                            <h6 class="fw-bold mb-2 text-dark mt-2"><i class="bi bi-geo-alt text-danger me-2"></i>{{ __('Coordinates') }}</h6>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-3 text-center border">
                                        <p class="text-muted small fw-semibold mb-1">{{ __('Latitude') }}</p>
                                        <span class="fw-bold text-dark font-monospace" style="font-size: 0.9rem;">{{ $port->latitude ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-3 text-center border">
                                        <p class="text-muted small fw-semibold mb-1">{{ __('Longitude') }}</p>
                                        <span class="fw-bold text-dark font-monospace" style="font-size: 0.9rem;">{{ $port->longitude ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection