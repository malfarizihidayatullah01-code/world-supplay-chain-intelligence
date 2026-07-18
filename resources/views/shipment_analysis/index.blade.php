@extends('layouts.app')

@section('content')
<div class="container-fluid pb-5">
    
    {{-- Header Section --}}
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
        <div class="card-body p-5 text-center">
            <h1 class="display-5 fw-bold text-dark mb-3"><i class="bi bi-diagram-3-fill text-primary me-3"></i>Shipment Analysis Engine</h1>
            <p class="lead text-muted mx-auto" style="max-width: 800px;">
                Shipment Analysis Engine merupakan pusat analisis pengiriman yang mengintegrasikan Shipment Management, Route Risk Analysis, Shipment Risk Analysis, Recommendation Engine, dan Shipment Monitoring sebagai sistem pendukung keputusan dalam Global Supply Chain Risk Intelligence Platform.
            </p>
        </div>
    </div>

    {{-- KPI Summary --}}
    <div class="row g-3 mb-5">
        <div class="col-6 col-md">
            <div class="card shadow-sm border-0 text-center h-100" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="text-muted small fw-bold mb-2">Total Shipments</div>
                    <div class="fs-2 fw-bold text-primary">{{ number_format($stats['total_shipments'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="card shadow-sm border-0 text-center h-100" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="text-muted small fw-bold mb-2">Route Analysis</div>
                    <div class="fs-2 fw-bold text-info">{{ number_format($stats['route_analysis'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="card shadow-sm border-0 text-center h-100" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="text-muted small fw-bold mb-2">Shipment Analysis</div>
                    <div class="fs-2 fw-bold text-success">{{ number_format($stats['shipment_risk_analysis'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="card shadow-sm border-0 text-center h-100" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="text-muted small fw-bold mb-2">Recommendations</div>
                    <div class="fs-2 fw-bold text-warning">{{ number_format($stats['recommendations'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="card shadow-sm border-0 text-center h-100" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="text-muted small fw-bold mb-2">Monitoring Records</div>
                    <div class="fs-2 fw-bold text-secondary">{{ number_format($stats['monitoring_records'] ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modules Cards --}}
    <h4 class="fw-bold mb-4">Engine Modules</h4>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
        
        {{-- 1. Shipment Management --}}
        <div class="col">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="mb-3">
                        <div class="d-inline-block bg-primary bg-opacity-10 text-primary p-3 rounded-circle mb-3">
                            <i class="bi bi-box-seam fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Shipment Management</h5>
                        <p class="text-muted small">
                            Mengelola data pengiriman, asal, tujuan, pelabuhan, jadwal, dan status shipment.
                        </p>
                    </div>
                    <div class="mt-auto pt-3 border-top">
                        <a href="{{ route('shipments.index') }}" class="btn btn-primary w-100">Open Module</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Route Risk Analysis --}}
        <div class="col">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="mb-3">
                        <div class="d-inline-block bg-info bg-opacity-10 text-info p-3 rounded-circle mb-3">
                            <i class="bi bi-graph-up-arrow fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Route Risk Analysis</h5>
                        <p class="text-muted small">
                            Melakukan analisis risiko berdasarkan rute pengiriman, kondisi cuaca, dan negara asal maupun tujuan.
                        </p>
                    </div>
                    <div class="mt-auto pt-3 border-top">
                        <a href="{{ route('route-risk-analysis.index') }}" class="btn btn-info text-white w-100">Open Module</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Shipment Risk Analysis --}}
        <div class="col">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="mb-3">
                        <div class="d-inline-block bg-success bg-opacity-10 text-success p-3 rounded-circle mb-3">
                            <i class="bi bi-shield-check fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Shipment Risk Analysis</h5>
                        <p class="text-muted small">
                            Menghitung tingkat risiko keseluruhan suatu pengiriman berdasarkan hasil analisis rute.
                        </p>
                    </div>
                    <div class="mt-auto pt-3 border-top">
                        <a href="{{ route('shipment-risk-analysis.index') }}" class="btn btn-success w-100">Open Module</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Shipment Recommendation --}}
        <div class="col">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="mb-3">
                        <div class="d-inline-block bg-warning bg-opacity-10 text-warning p-3 rounded-circle mb-3">
                            <i class="bi bi-lightbulb fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Shipment Recommendation</h5>
                        <p class="text-muted small">
                            Memberikan rekomendasi tindakan berdasarkan tingkat risiko pengiriman.
                        </p>
                    </div>
                    <div class="mt-auto pt-3 border-top">
                        <a href="{{ route('shipment-recommendations.index') }}" class="btn btn-warning text-dark w-100">Open Module</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5. Shipment Monitoring --}}
        <div class="col">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="mb-3">
                        <div class="d-inline-block bg-secondary bg-opacity-10 text-secondary p-3 rounded-circle mb-3">
                            <i class="bi bi-display fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Shipment Monitoring</h5>
                        <p class="text-muted small">
                            Menyediakan dashboard monitoring seluruh shipment beserta status, risiko, dan rekomendasi.
                        </p>
                    </div>
                    <div class="mt-auto pt-3 border-top">
                        <a href="{{ route('shipment-monitoring.index') }}" class="btn btn-secondary w-100">Open Module</a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    {{-- Footer --}}
    <footer class="text-center mt-5 mb-3 text-muted">
        <hr class="mb-4">
        <div class="fw-bold">Sprint 5</div>
        <div>Shipment Analysis Engine</div>
        <div class="small">Business Intelligence & Decision Support</div>
    </footer>

</div>
@endsection
