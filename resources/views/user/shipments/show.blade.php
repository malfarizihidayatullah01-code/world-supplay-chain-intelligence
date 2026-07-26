@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    :root {
        --bg-body: #F8FAFC;
        --card-bg: #FFFFFF;
        --border-color: #E2E8F0;
        --text-main: #0F172A;
        --text-muted: #64748B;
        --primary: #2563EB;
        --success: #10B981;
        --warning: #F59E0B;
        --danger: #EF4444;
        --info: #3B82F6;
        --radius: 16px;
        --shadow-soft: 0 8px 30px rgba(15,23,42,0.06);
        --shadow-hover: 0 12px 40px rgba(15,23,42,0.1);
        --font-family: 'Inter', sans-serif;
    }

    body {
        background-color: var(--bg-body);
        font-family: var(--font-family);
    }

    .ent-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        box-shadow: var(--shadow-soft);
        padding: 32px;
        transition: transform 200ms ease, box-shadow 200ms ease;
        margin-bottom: 32px;
    }

    .ent-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
    }

    .ent-card-p0 {
        padding: 0;
        overflow: hidden;
    }

    /* Typography */
    .txt-title { font-size: 32px; font-weight: 700; color: var(--text-main); letter-spacing: -0.5px; }
    .txt-section { font-size: 20px; font-weight: 600; color: var(--text-main); margin-bottom: 24px; }
    .txt-card { font-size: 16px; font-weight: 600; color: var(--text-main); }
    .txt-body { font-size: 14px; font-weight: 400; color: var(--text-main); }
    .txt-small { font-size: 12px; font-weight: 500; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }

    /* Map & Floating Panel */
    .map-wrapper {
        position: relative;
        height: 520px;
        border-radius: var(--radius);
    }
    
    #shipmentMap {
        width: 100%;
        height: 100%;
        border-radius: var(--radius);
        z-index: 1;
    }

    .map-floating-panel {
        position: absolute;
        top: 24px;
        left: 24px;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
        width: 320px;
        box-shadow: 0 10px 25px rgba(15,23,42,0.1);
    }

    .map-live-indicator {
        position: absolute;
        top: 24px;
        right: 24px;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid var(--border-color);
        border-radius: 50px;
        padding: 8px 16px;
        box-shadow: 0 4px 12px rgba(15,23,42,0.08);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .live-dot {
        width: 8px;
        height: 8px;
        background-color: var(--success);
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        animation: pulse-dot 2s infinite;
    }
    @keyframes pulse-dot {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    /* Progress & Badges */
    .progress-ent {
        height: 12px;
        border-radius: 12px;
        background-color: var(--border-color);
        overflow: hidden;
    }
    .progress-ent .progress-bar { border-radius: 12px; }
    
    .badge-pill {
        padding: 6px 16px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Timeline */
    .timeline-light {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        position: relative;
        padding: 24px 0;
    }
    .timeline-light::before {
        content: '';
        position: absolute;
        top: 32px;
        left: 40px;
        right: 40px;
        height: 2px;
        background-color: var(--border-color);
        z-index: 1;
    }
    .tl-step {
        position: relative;
        z-index: 2;
        text-align: center;
        background: var(--card-bg);
        padding: 0 16px;
        min-width: 140px;
    }
    .tl-marker {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background-color: var(--border-color);
        margin: 0 auto 16px;
        border: 3px solid var(--card-bg);
        box-shadow: 0 0 0 1px var(--border-color);
    }
    .tl-step.completed .tl-marker { background-color: var(--success); box-shadow: 0 0 0 1px var(--success); }
    .tl-step.current .tl-marker { background-color: var(--primary); box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15); animation: pulse-blue 2s infinite; }
    
    @keyframes pulse-blue {
        0% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.3); }
        70% { box-shadow: 0 0 0 8px rgba(37, 99, 235, 0); }
        100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0); }
    }

    .blinking-marker div { animation: blinker 1.5s ease-in-out infinite; }
    @keyframes blinker {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { box-shadow: 0 0 0 12px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .btn-outline-ent {
        background-color: white;
        border: 1px solid var(--border-color);
        color: var(--text-main);
        font-weight: 500;
        padding: 10px 20px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .btn-outline-ent:hover {
        background-color: #F8FAFC;
        color: var(--primary);
    }

    .ai-assistant-panel {
        background: linear-gradient(180deg, #F8FAFC 0%, #FFFFFF 100%);
        border-top: 4px solid var(--primary);
    }
</style>
@endpush

@section('content')
@php
    $mon = $monitorObj;
    
    // Status color
    $statusColor = 'primary';
    if($mon['current_status'] === 'Delayed' || $mon['current_status'] === 'At Risk') $statusColor = 'warning';
    if($mon['current_status'] === 'Delivered') $statusColor = 'success';
    if($mon['current_status'] === 'Cancelled') $statusColor = 'danger';

    // Risk color
    $levelColor = 'success';
    if($mon['risk_level'] === 'Medium') $levelColor = 'warning';
    if($mon['risk_level'] === 'High') $levelColor = 'danger';
    if($mon['risk_level'] === 'Critical') $levelColor = 'danger';

    $eta = $shipment->estimated_arrival ? \Carbon\Carbon::parse($shipment->estimated_arrival)->format('d M Y') : 'N/A';
@endphp

<div class="container-fluid px-0 pb-5" style="background-color: var(--bg-body);">
    
    <div class="d-flex align-items-center mb-4 gap-3 px-3 px-md-0 pt-3">
        <a href="{{ route('user.shipments.index') }}" class="btn btn-light rounded-circle shadow-sm border bg-white" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <i class="bi bi-arrow-left text-dark"></i>
        </a>
        <div class="txt-small">
            <span class="text-muted">{{ __('Monitoring') }} / </span>
            <span class="fw-bold" style="color: var(--primary);">{{ $shipment->shipment_code }}</span>
        </div>
    </div>

    <!-- 1. SHIPMENT HEADER -->
    <div class="ent-card" style="border-top: 4px solid var(--primary);">
        <div class="row align-items-center mb-5">
            <div class="col-md-7">
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <h1 class="txt-title mb-0">{{ $shipment->shipment_code }}</h1>
                        <span class="badge-pill bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} border border-{{ $statusColor }} border-opacity-25">
                            <i class="bi bi-record-circle-fill"></i> {{ __($mon['current_status']) }}
                        </span>
                    </div>
                    <div class="txt-card text-muted fw-normal">{{ $shipment->shipment_name ?? $shipment->goods ?? 'General Cargo' }}</div>
                </div>
            </div>
            <div class="col-md-5 text-md-end mt-4 mt-md-0">
                <a href="{{ route('user.shipments.edit', $shipment->id) }}" class="btn-outline-ent text-decoration-none d-inline-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square"></i> {{ __('Manage Shipment') }}
                </a>
            </div>
        </div>

        <div class="row pt-2 align-items-center">
            <!-- Origin & Destination -->
            <div class="col-lg-5 col-md-12 mb-4 mb-lg-0 pe-lg-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="txt-small mb-2"><i class="bi bi-geo-alt text-muted me-1"></i> {{ __('Origin') }}</div>
                        <div class="txt-card fs-5">{{ $mon['origin']['country'] }}</div>
                        <div class="txt-body text-muted mt-1">{{ $mon['origin']['port'] }}</div>
                    </div>
                    <div class="text-center px-4">
                        <i class="bi bi-arrow-right fs-3 text-muted opacity-50"></i>
                    </div>
                    <div class="text-end">
                        <div class="txt-small mb-2"><i class="bi bi-geo-fill text-muted me-1"></i> {{ __('Destination') }}</div>
                        <div class="txt-card fs-5">{{ $mon['destination']['country'] }}</div>
                        <div class="txt-body text-muted mt-1">{{ $mon['destination']['port'] }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Progress -->
            <div class="col-lg-4 col-md-7 border-start border-end px-lg-5 px-md-4 mb-4 mb-md-0">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div class="txt-small">{{ __('Journey Progress') }}</div>
                    <div class="txt-card fs-5" style="color: var(--primary);">{{ $mon['progress_percentage'] }}%</div>
                </div>
                <div class="progress progress-ent mb-3">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $mon['progress_percentage'] }}%"></div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="txt-small">{{ __('ETA') }}</div>
                    <div class="txt-body fw-bold">{{ $eta }}</div>
                </div>
            </div>

            <!-- Risk Score -->
            <div class="col-lg-3 col-md-5 ps-lg-5 ps-md-4 text-center text-md-start">
                <div class="txt-small mb-3">{{ __('Risk Assessment') }}</div>
                <div class="d-flex align-items-baseline gap-2 justify-content-center justify-content-md-start mb-2">
                    <span class="txt-title" style="color: var(--{{ $levelColor }});">{{ $mon['risk_score'] }}</span>
                    <span class="txt-body text-muted">/ 100</span>
                </div>
                <div class="txt-small fw-bold text-{{ $levelColor }}">{{ __(strtoupper($mon['risk_level'])) }} RISK</div>
            </div>
        </div>
    </div>

    <!-- 2. LIVE ROUTE MAP -->
    <div class="ent-card ent-card-p0 map-wrapper mb-5">
        <div id="shipmentMap"></div>
        
        <!-- Floating Panel -->
        <div class="map-floating-panel">
            <h3 class="txt-card mb-4 border-bottom pb-3"><i class="bi bi-radar text-primary me-2"></i>{{ __('Live Tracking') }}</h3>
            
            <div class="mb-3">
                <div class="txt-small mb-1">{{ __('Current Position') }}</div>
                <div class="txt-card" id="map-current-loc">{{ $mon['current_location'] }}</div>
            </div>
            <div class="mb-3">
                <div class="txt-small mb-1">{{ __('Next Port') }}</div>
                <div class="txt-body fw-medium">{{ $mon['destination']['port'] }}</div>
            </div>
            <div class="mb-3">
                <div class="txt-small mb-1">{{ __('Distance Remaining') }}</div>
                <div class="txt-body fw-medium">{{ 100 - $mon['progress_percentage'] }}% Route</div>
            </div>
            <div class="mb-0">
                <div class="txt-small mb-1">{{ __('ETA') }}</div>
                <div class="txt-body fw-bold text-primary">{{ $eta }}</div>
            </div>
        </div>

        <!-- 3. MAP STATUS INDICATOR -->
        <div class="map-live-indicator">
            <div class="live-dot"></div>
            <span class="txt-small fw-bold text-dark m-0" style="letter-spacing: 0;">LIVE</span>
            <span class="txt-small text-muted m-0 ms-2 text-lowercase" style="letter-spacing: 0;">{{ __('Upd. 1m ago') }}</span>
        </div>
    </div>

    <!-- 4. SHIPMENT TIMELINE -->
    <div class="ent-card">
        <h2 class="txt-section">{{ __('Shipment Timeline') }}</h2>
        <div class="timeline-light mt-4 overflow-auto pb-2 px-md-4">
            @php
                $histories = $shipment->histories->sortBy('timestamp')->values();
                $steps = [
                    ['label' => 'Shipment Created', 'date' => $shipment->created_at->format('d M Y'), 'status' => 'completed'],
                ];
                
                foreach($histories as $h) {
                    $steps[] = [
                        'label' => $h->status . ' ' . $h->location_desc,
                        'date' => \Carbon\Carbon::parse($h->timestamp)->format('d M Y'),
                        'status' => 'completed'
                    ];
                }
                
                $statusLower = strtolower($mon['current_status']);
                if($statusLower !== 'delivered' && $statusLower !== 'completed' && $statusLower !== 'terkirim') {
                    $steps[count($steps)-1]['status'] = 'current';
                    
                    $steps[] = [
                        'label' => 'Final Destination',
                        'date' => $eta,
                        'status' => 'pending'
                    ];
                } else {
                    // Jika sudah terkirim, jadikan titik terakhir sebagai titik saat ini (current/selesai)
                    $steps[count($steps)-1]['status'] = 'current';
                }
            @endphp
            
            @foreach($steps as $step)
                <div class="tl-step {{ $step['status'] }}">
                    <div class="tl-marker"></div>
                    <div class="txt-body fw-bold mt-3 lh-sm mb-1 text-{{ $step['status'] === 'current' ? 'primary' : ($step['status'] === 'pending' ? 'muted' : 'dark') }}">{{ $step['label'] }}</div>
                    <div class="txt-small {{ $step['status'] === 'pending' ? 'text-muted opacity-50' : '' }}">{{ $step['date'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="row g-4 mb-4">
        
        <!-- 5. AI RISK SUMMARY -->
        <div class="col-lg-4 d-flex">
            <div class="ent-card w-100 d-flex flex-column">
                <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
                    <i class="bi bi-cpu fs-5" style="color: var(--primary);"></i>
                    <h2 class="txt-card mb-0">{{ __('AI Insight') }}</h2>
                </div>
                
                <div class="mb-4 flex-grow-1">
                    <div class="txt-small mb-2">{{ __('AI Assessment') }}</div>
                    <div class="txt-body fw-medium lh-lg text-dark">
                        @if($mon['risk_level'] === 'Low')
                            {{ __('Current shipment is stable. No immediate threats detected.') }}
                        @else
                            {{ __('Shipment requires attention due to elevated risk factors.') }}
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <div class="txt-small mb-1">{{ __('Overall Risk') }}</div>
                        <div class="txt-card text-{{ $levelColor }}">{{ __($mon['risk_level']) }}</div>
                    </div>
                    <div class="text-end">
                        <div class="txt-small mb-1">{{ __('Confidence') }}</div>
                        <div class="txt-card text-dark">94%</div>
                    </div>
                </div>

                <div class="pt-4 border-top bg-light rounded-3 p-3 mt-auto">
                    <div class="txt-small mb-3">{{ __('Main Contributors') }}</div>
                    <ul class="list-unstyled mb-0 ms-1 text-body">
                        @php $hasRisk = false; @endphp
                        @if($mon['currency']['level'] === 'High' || $mon['currency']['level'] === 'Medium') <li class="mb-2"><i class="bi bi-dot text-primary me-1 fs-5"></i>{{ __('Currency Fluctuation') }}</li> @php $hasRisk = true; @endphp @endif
                        @if($mon['news']['level'] === 'High' || $mon['news']['level'] === 'Medium') <li class="mb-2"><i class="bi bi-dot text-primary me-1 fs-5"></i>{{ __('Negative News Sentiment') }}</li> @php $hasRisk = true; @endphp @endif
                        @if($mon['economic']['level'] === 'High' || $mon['economic']['level'] === 'Medium') <li class="mb-2"><i class="bi bi-dot text-primary me-1 fs-5"></i>{{ __('Economic Instability') }}</li> @php $hasRisk = true; @endphp @endif
                        @if($mon['weather']['level'] === 'High' || $mon['weather']['level'] === 'Medium') <li class="mb-2"><i class="bi bi-dot text-primary me-1 fs-5"></i>{{ __('Adverse Weather') }}</li> @php $hasRisk = true; @endphp @endif
                        @if(!$hasRisk)
                            <li><i class="bi bi-dot text-success me-1 fs-5"></i>{{ __('None detected') }}</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- 6. RISK BREAKDOWN -->
        <div class="col-lg-4 d-flex">
            <div class="ent-card w-100 d-flex flex-column">
                <h2 class="txt-card mb-4 pb-3 border-bottom">{{ __('Risk Breakdown') }}</h2>
                
                <div class="d-flex flex-column justify-content-between flex-grow-1 gap-4 mt-2">
                    <!-- Weather (Orange) -->
                    <div>
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <div class="txt-body fw-medium"><i class="bi bi-cloud-rain text-warning me-2"></i>{{ __('Weather') }}</div>
                            <div class="txt-small text-dark fw-bold">{{ $mon['weather']['level'] }}</div>
                        </div>
                        <div class="progress progress-ent" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: {{ $mon['weather']['level'] === 'High' ? '80%' : ($mon['weather']['level'] === 'Medium' ? '50%' : '20%') }}"></div>
                        </div>
                        <div class="txt-small text-muted mt-2 text-end" id="val-weather-rain">{{ $mon['weather']['rain'] }} • {{ $mon['weather']['wind'] }}</div>
                    </div>

                    <!-- News (Red) -->
                    <div>
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <div class="txt-body fw-medium"><i class="bi bi-newspaper text-danger me-2"></i>{{ __('News') }}</div>
                            <div class="txt-small text-dark fw-bold">{{ $mon['news']['level'] }}</div>
                        </div>
                        <div class="progress progress-ent" style="height: 8px;">
                            <div class="progress-bar bg-danger" style="width: {{ $mon['news']['level'] === 'High' ? '75%' : ($mon['news']['level'] === 'Medium' ? '45%' : '15%') }}"></div>
                        </div>
                        <div class="txt-small text-muted mt-2 text-end" id="val-news-neg">{{ $mon['news']['negative'] }} Neg. News</div>
                    </div>

                    <!-- Economy (Blue) -->
                    <div>
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <div class="txt-body fw-medium"><i class="bi bi-graph-down-arrow text-primary me-2"></i>{{ __('Economy') }}</div>
                            <div class="txt-small text-dark fw-bold">{{ $mon['economic']['level'] }}</div>
                        </div>
                        <div class="progress progress-ent" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: {{ $mon['economic']['level'] === 'High' ? '70%' : ($mon['economic']['level'] === 'Medium' ? '40%' : '10%') }}"></div>
                        </div>
                        <div class="txt-small text-muted mt-2 text-end" id="val-economic-val">{{ $mon['economic']['inflation'] }} Inflation</div>
                    </div>

                    <!-- Currency (Green) -->
                    <div>
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <div class="txt-body fw-medium"><i class="bi bi-currency-exchange text-success me-2"></i>{{ __('Currency') }}</div>
                            <div class="txt-small text-dark fw-bold">{{ $mon['currency']['level'] }}</div>
                        </div>
                        <div class="progress progress-ent" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $mon['currency']['level'] === 'High' ? '60%' : ($mon['currency']['level'] === 'Medium' ? '30%' : '10%') }}"></div>
                        </div>
                        <div class="txt-small text-muted mt-2 text-end" id="val-currency-rate">{{ $mon['currency']['rate'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 7. AI RECOMMENDATION -->
        <div class="col-lg-4 d-flex">
            <div class="ent-card w-100 d-flex flex-column ai-assistant-panel">
                <div class="d-flex justify-content-between align-items-start mb-4 pb-3 border-bottom">
                    <h2 class="txt-card mb-0" style="color: var(--primary);">{{ __('Intelligent Assistant') }}</h2>
                    <i class="bi bi-robot fs-4" style="color: var(--primary);"></i>
                </div>
                
                <p class="txt-body fw-medium lh-base mb-4 text-dark" id="ai-rec-text">{{ $mon['recommendation'] }}</p>
                
                <div class="mb-4 flex-grow-1 bg-white p-4 rounded-3 border">
                    <div class="txt-small mb-3">{{ __('Recommended Actions') }}</div>
                    <ul class="list-unstyled mb-0 text-body" id="ai-bullets">
                        @foreach($mon['recommendation_bullets'] as $bullet)
                            <li class="mb-3 d-flex align-items-start last:mb-0">
                                <i class="bi bi-check-circle-fill text-primary me-2 mt-1"></i> 
                                <span class="lh-sm fw-medium">{{ $bullet }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-auto d-flex justify-content-between bg-white p-3 rounded-3 border">
                    <div>
                        <div class="txt-small">{{ __('Est. Delay') }}</div>
                        <div class="txt-card text-danger mt-1" id="ai-delay">{{ $mon['estimated_delay'] }}</div>
                    </div>
                    <div class="text-end">
                        <div class="txt-small">{{ __('Priority') }}</div>
                        <div class="txt-card text-{{ $levelColor }} mt-1">{{ __($mon['risk_level']) }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- 8. SHIPMENT INFORMATION -->
    <div class="ent-card mb-0">
        <h2 class="txt-section mb-4 pb-3 border-bottom">{{ __('Shipment Information') }}</h2>
        <div class="row g-5">
            
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <i class="bi bi-box-seam text-muted fs-5"></i>
                    <h3 class="txt-card mb-0">{{ __('General Details') }}</h3>
                </div>
                <div class="info-grid" style="grid-template-columns: 1fr 1fr; gap: 24px;">
                    <div class="info-item">
                        <span class="txt-small">{{ __('Company') }}</span>
                        <span class="txt-body fw-bold">Global Line Logistics</span>
                    </div>
                    <div class="info-item">
                        <span class="txt-small">{{ __('Container') }}</span>
                        <span class="txt-body fw-bold">CONT-{{ substr(md5($shipment->id), 0, 8) }}</span>
                    </div>
                    <div class="info-item">
                        <span class="txt-small">{{ __('Cargo') }}</span>
                        <span class="txt-body fw-bold">{{ $shipment->goods ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="txt-small">{{ __('Weight') }}</span>
                        <span class="txt-body fw-bold">14.5 Tons (Est.)</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6 border-start ps-md-5">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <i class="bi bi-signpost-split text-muted fs-5"></i>
                    <h3 class="txt-card mb-0">{{ __('Journey Details') }}</h3>
                </div>
                <div class="info-grid" style="grid-template-columns: 1fr 1fr; gap: 24px;">
                    <div class="info-item">
                        <span class="txt-small">{{ __('Origin') }}</span>
                        <span class="txt-body fw-bold">{{ $mon['origin']['country'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="txt-small">{{ __('Destination') }}</span>
                        <span class="txt-body fw-bold">{{ $mon['destination']['country'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="txt-small">{{ __('Current Port') }}</span>
                        <span class="txt-body fw-bold">{{ $mon['current_location'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="txt-small">{{ __('ETA') }}</span>
                        <span class="txt-body fw-bold text-primary">{{ $eta }}</span>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const mapData = @json($mapData);
    const currentLocation = "{{ $mon['current_location'] }}";
    
    // Init Map
    const map = L.map('shipmentMap', { zoomControl: false });
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    // Using CartoDB Positron for cleaner B2B map look
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
    }).addTo(map);

    const latlngs = [];
    const bounds = L.latLngBounds();
    
    const createIcon = (color, isCurrent) => {
        let className = 'custom-div-icon';
        if(isCurrent) className += ' blinking-marker';
        
        return L.divIcon({
            className: className,
            html: `<div style="background-color: ${color}; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.3);"></div>`,
            iconSize: [14, 14],
            iconAnchor: [7, 7]
        });
    };

    mapData.forEach(point => {
        const pLatlng = [parseFloat(point.lat), parseFloat(point.lng)];
        latlngs.push(pLatlng);
        bounds.extend(pLatlng);
        
        let color = 'var(--text-muted)';
        if(point.type === 'Origin') color = 'var(--text-main)';
        if(point.type === 'Destination') color = 'var(--text-main)';
        
        const isCurrent = (point.name === currentLocation);
        if(isCurrent) color = 'var(--success)';
        
        L.marker(pLatlng, {icon: createIcon(color, isCurrent)})
         .bindTooltip(`<div class="txt-small text-muted mb-1">${point.type}</div><div class="fw-bold text-dark">${point.name}</div>`, {direction: 'top', offset: [0,-10], className: 'shadow-sm border-0 rounded-3 p-2'})
         .addTo(map);
    });

    function getBezierCurve(start, end, tension = 0.2) {
        const points = [];
        const midLat = (start[0] + end[0]) / 2;
        const midLng = (start[1] + end[1]) / 2;
        
        const dLng = end[1] - start[1];
        const dLat = end[0] - start[0];
        
        const ctrlLat = midLat - (dLng * tension);
        const ctrlLng = midLng + (dLat * tension);
        
        for (let i = 0; i <= 100; i++) {
            const t = i / 100;
            const lat = Math.pow(1 - t, 2) * start[0] + 2 * (1 - t) * t * ctrlLat + Math.pow(t, 2) * end[0];
            const lng = Math.pow(1 - t, 2) * start[1] + 2 * (1 - t) * t * ctrlLng + Math.pow(t, 2) * end[1];
            points.push([lat, lng]);
        }
        return points;
    }

    if (latlngs.length > 1) {
        let allCurvePoints = [];
        for (let i = 0; i < latlngs.length - 1; i++) {
            const curve = getBezierCurve(latlngs[i], latlngs[i+1], -0.15);
            allCurvePoints = allCurvePoints.concat(curve);
        }
        
        const style = document.createElement('style');
        style.innerHTML = `
            .ship-route-path {
                animation: dash 10s linear infinite;
                filter: drop-shadow(0px 2px 4px rgba(37, 99, 235, 0.3));
            }
            @keyframes dash {
                to { stroke-dashoffset: -200; }
            }
        `;
        document.head.appendChild(style);

        L.polyline(allCurvePoints, {
            color: 'var(--primary)',
            weight: 3,
            dashArray: '8, 12',
            className: 'ship-route-path',
            opacity: 0.8
        }).addTo(map);
    }
    
    if (latlngs.length > 0) {
        map.fitBounds(bounds, {padding: [80, 80]});
    }

    // Auto Refresh Logic (Every 60 Seconds) - Keeps original functionality
    setInterval(() => {
        fetch(window.location.href, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            const mon = data.monitorObj;
            
            // Map Panel Updates
            if(document.getElementById('map-current-loc')) document.getElementById('map-current-loc').innerText = mon.current_location;

            // Risk Updates
            if(document.getElementById('val-weather-rain')) document.getElementById('val-weather-rain').innerText = mon.weather.rain + ' • ' + mon.weather.wind;
            if(document.getElementById('val-news-neg')) document.getElementById('val-news-neg').innerText = mon.news.negative + ' Neg. News';
            if(document.getElementById('val-economic-val')) document.getElementById('val-economic-val').innerText = mon.economic.inflation + ' Inflation';
            if(document.getElementById('val-currency-rate')) document.getElementById('val-currency-rate').innerText = mon.currency.rate;
            
            // AI Updates
            if(document.getElementById('ai-rec-text')) document.getElementById('ai-rec-text').innerText = mon.recommendation;
            if(document.getElementById('ai-delay')) document.getElementById('ai-delay').innerText = mon.estimated_delay;
            
            if(document.getElementById('ai-bullets')) {
                const ul = document.getElementById('ai-bullets');
                ul.innerHTML = '';
                mon.recommendation_bullets.forEach(b => {
                    ul.innerHTML += `<li class="mb-3 d-flex align-items-start last:mb-0"><i class="bi bi-check-circle-fill text-primary me-2 mt-1"></i> <span class="lh-sm fw-medium">${b}</span></li>`;
                });
            }
        });
    }, 60000);
</script>
@endpush
@endsection