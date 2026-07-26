@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/css/flag-icons.min.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />


@section('content')
@php
    $wea      = $country ? $country->weatherCaches->first() : null;
    $rawData  = $wea ? $wea->raw_data : null;
    $current  = $rawData['current'] ?? null;
    $condText = $wea ? $wea->condition : 'Unknown';
    $lc       = strtolower($condText);
    
    if(str_contains($lc, 'snow')) { $condIcon = 'bi-snow'; $condHex = '#60A5FA'; $condBgHex = 'rgba(96,165,250,0.1)'; }
    elseif(str_contains($lc, 'fog')) { $condIcon = 'bi-cloud-fog2-fill'; $condHex = '#CBD5E1'; $condBgHex = 'rgba(203,213,225,0.15)'; }
    elseif(str_contains($lc, 'rain')) { $condIcon = 'bi-cloud-rain-fill'; $condHex = '#3B82F6'; $condBgHex = 'rgba(59,130,246,0.1)'; }
    elseif(str_contains($lc, 'storm') || str_contains($lc, 'thunder')) { $condIcon = 'bi-cloud-lightning-rain-fill'; $condHex = '#EF4444'; $condBgHex = 'rgba(239,68,68,0.1)'; }
    elseif(str_contains($lc, 'cloud')) { $condIcon = 'bi-cloud-fill'; $condHex = '#94A3B8'; $condBgHex = 'rgba(148,163,184,0.1)'; }
    else { $condIcon = 'bi-brightness-high-fill'; $condHex = '#F59E0B'; $condBgHex = 'rgba(245,158,11,0.1)'; }
@endphp
<style>
    /* ===== Weather Page Base ===== */
    .weather-page { background: transparent; min-height: calc(100vh - 60px); animation: fadeIn 0.4s ease-out; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ===== SIDEBAR ===== */
    .weather-sidebar {
        width: 280px;
        min-width: 260px;
        max-width: 320px;
        flex-shrink: 0;
        height: calc(100vh - 120px);
        position: static;
        display: flex;
        flex-direction: column;
    }
    .sidebar-card {
        background: var(--surface-color);
        border-radius: var(--radius-card);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-global);
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
        transition: all var(--transition-speed) ease;
    }
    .sidebar-card:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .country-list {
        overflow-y: auto;
        flex-grow: 1;
        padding: 0 16px 16px;
    }
    .country-list::-webkit-scrollbar { width: 4px; }
    .country-list::-webkit-scrollbar-track { background: transparent; }
    .country-list::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 99px; }

    .country-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        border-radius: 12px;
        border: 1px solid transparent;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        margin-bottom: 6px;
        cursor: pointer;
        border-left: 3px solid transparent;
    }
    .country-item:hover {
        background: #F8FAFC;
        transform: translateX(4px);
        color: inherit;
        border-color: #F1F5F9;
    }
    .country-item.active {
        background: #EFF6FF;
        border-color: #DBEAFE;
        border-left: 4px solid var(--primary);
        color: inherit;
    }
    .country-item.active .country-name { color: var(--primary-hover); font-weight: 700; }

    /* ===== MAIN CONTENT ===== */
    .weather-main { flex-grow: 1; min-width: 0; }

    /* ===== RIGHT PANEL ===== */
    .weather-right {
        width: 300px;
        min-width: 280px;
        max-width: 340px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* ===== CARDS ===== */
    .w-card {
        background: var(--surface-color);
        border-radius: var(--radius-card);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-global);
        transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    .w-card:hover {
        transform: translateY(-2px);
    }
    .w-card-body { padding: 24px; }
    .w-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* ===== MAP ===== */
    #weatherMap {
        height: 420px;
        width: 100%;
        z-index: 1;
    }

    /* ===== MINI STAT CARDS ===== */
    .mini-stat {
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-card);
        padding: 20px 16px;
        text-align: center;
        transition: all var(--transition-speed) ease;
        cursor: default;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        box-shadow: var(--shadow-global);
    }
    .mini-stat:hover {
        transform: translateY(-2px);
    }
    .mini-stat-icon {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 12px;
        font-size: 1.4rem;
    }

    /* ===== WEATHER ICON HERO ===== */
    .weather-hero-icon {
        font-size: 6.5rem;
        line-height: 1;
        animation: float 4s ease-in-out infinite;
        filter: drop-shadow(0 12px 24px rgba(0,0,0,0.1));
        display: inline-block;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-12px); }
    }

    /* ===== SEARCH ===== */
    .search-wrap { position: relative; }
    .search-wrap input {
        padding-left: 40px;
        border-radius: 14px;
        border: 1px solid var(--border-color);
        background: #F8FAFC;
        font-size: 0.9rem;
        height: 44px;
        width: 100%;
        outline: none;
        transition: all 0.2s;
    }
    .search-wrap input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(37,99,235,0.1);
        background: var(--surface-color);
    }
    .search-wrap .search-icon {
        position: absolute;
        left: 14px; top: 50%;
        transform: translateY(-50%);
        color: #9CA3AF;
        pointer-events: none;
        font-size: 1rem;
    }

    /* ===== ALERT CARD ===== */
    .alert-item {
        border-radius: var(--radius-card);
        padding: 16px 20px;
        border: 1px solid;
        transition: all var(--transition-speed);
        box-shadow: var(--shadow-global);
    }
    .alert-item:hover { transform: translateX(3px); }

    /* ===== REFRESH BUTTON ===== */
    .btn-refresh {
        background: var(--primary);
        border: 1px solid var(--primary);
        color: #ffffff;
        border-radius: var(--radius-button);
        padding: 4px 10px;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        text-decoration: none;
    }
    .btn-refresh:hover {
        background: var(--primary-hover);
        border-color: var(--primary-hover);
        transform: translateY(-2px);
        color: #ffffff;
    }
    .btn-refresh:active {
        transform: translateY(1px);
    }
    .btn-refresh:hover .btn-refresh-icon { transform: rotate(180deg); }
    .btn-refresh-icon { transition: transform 0.5s ease; }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        background: #F8FAFC;
        border-radius: var(--radius-card);
        border: 1px dashed var(--border-color);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        padding: 2rem;
        text-align: center;
    }
    .empty-state-icon {
        font-size: 2.5rem;
        color: #94A3B8;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1199px) {
        .weather-sidebar { width: 240px; min-width: 220px; }
        .weather-right { width: 260px; min-width: 240px; }
        .weather-hero-icon { font-size: 5rem; }
    }
    @media (max-width: 991px) {
        .weather-sidebar { width: 100%; max-width: 100%; height: auto; position: static; margin-bottom: 20px; }
        .weather-right { width: 100%; max-width: 100%; margin-top: 20px; }
        .weather-layout { flex-direction: column; gap: 0; }
        #weatherMap { height: 350px; }
        .sidebar-card { height: auto; max-height: 400px; }
        .weather-hero-icon { font-size: 4rem; }
    }

    /* ===== MODERN WEATHER MARKERS ===== */
    .premium-marker {
        width: 44px;
        height: 44px;
        background-color: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 999px;
        box-shadow: 0 8px 24px rgba(15,23,42,0.10);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 200ms ease;
        cursor: pointer;
        position: relative;
    }
    .premium-marker i {
        font-size: 20px;
        transition: transform 200ms ease;
    }
    .premium-marker:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 32px rgba(15,23,42,0.15);
        border-color: #CBD5E1;
        z-index: 1000 !important;
    }
    .premium-marker.active-marker {
        border: 2px solid #3B82F6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15), 0 8px 24px rgba(15,23,42,0.15);
        animation: markerBounce 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        z-index: 1000 !important;
    }
    @keyframes markerBounce {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    /* ===== CLUSTER STYLING ===== */
    .premium-cluster {
        width: 44px;
        height: 44px;
        background-color: #FFFFFF;
        border: 2px solid #3B82F6;
        border-radius: 999px;
        box-shadow: 0 8px 24px rgba(15,23,42,0.10);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        color: #3B82F6;
        font-size: 15px;
        transition: all 200ms ease;
    }
    .premium-cluster:hover {
        transform: scale(1.1);
        background-color: #F8FAFC;
    }

    /* ===== MODERN POPUP ===== */
    .leaflet-popup-content-wrapper {
        background: #FFFFFF;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(15,23,42,0.12);
        padding: 0;
        border: 1px solid #E2E8F0;
        overflow: hidden;
    }
    .leaflet-popup-content {
        margin: 0;
        width: 240px !important;
    }
    .leaflet-popup-tip-container {
        display: none;
    }
    .modern-popup {
        padding: 20px;
        font-family: 'Inter', sans-serif;
    }
    .modern-popup-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid #F1F5F9;
    }
    .modern-popup-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    .modern-popup-temp {
        font-size: 26px;
        font-weight: 800;
        color: #0F172A;
        line-height: 1;
        letter-spacing: -0.5px;
    }
    .modern-popup-desc {
        font-size: 13px;
        color: #64748B;
        text-transform: capitalize;
        font-weight: 500;
        margin-top: 6px;
    }
    .modern-popup-location {
        font-weight: 700;
        color: #1E293B;
        font-size: 15px;
        margin-bottom: 4px;
    }
    .modern-popup-country {
        color: #94A3B8;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    .modern-popup-stats {
        display: flex;
        justify-content: space-between;
        margin-top: 16px;
        background: #F8FAFC;
        padding: 10px 12px;
        border-radius: 10px;
    }
    .modern-popup-stat {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #475569;
        font-weight: 600;
    }
    .modern-popup-stat i {
        color: #94A3B8;
        font-size: 14px;
    }
    .leaflet-container a.leaflet-popup-close-button {
        color: #94A3B8;
        padding: 12px;
        width: 32px;
        height: 32px;
        font-size: 18px;
    }
    .leaflet-container a.leaflet-popup-close-button:hover {
        color: #0F172A;
        background: transparent;
    }
</style>

<div class="weather-page px-2 px-md-0">

    {{-- ===== PAGE HEADER (Floating Toolbar) ===== --}}
    <div class="d-flex justify-content-end mt-3 mb-4">
        <div class="d-flex align-items-center gap-3 bg-white border rounded-pill px-2 py-1 shadow-sm" style="border-color: #E5E7EB;">
            @if($country && $country->weatherCaches->isNotEmpty())
            <div class="text-secondary d-flex align-items-center gap-2 ps-3">
                <i class="bi bi-clock-history text-primary"></i>
                <span style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.3px;">
                    {{ $country->weatherCaches->first()->updated_at->format('d M Y, H:i') }}
                </span>
            </div>
            @endif
            <button class="btn-refresh rounded-pill m-0" onclick="location.reload()" style="padding: 6px 16px; font-size: 0.75rem;">
                <i class="bi bi-arrow-clockwise btn-refresh-icon"></i> {{ __('Refresh') }}
            </button>
        </div>
    </div>

    {{-- ===== 3-COLUMN LAYOUT ===== --}}
    <div class="d-flex gap-4 weather-layout">


        {{-- ===== CENTER: MAIN CONTENT ===== --}}
        <div class="weather-main d-flex flex-column gap-4">

            {{-- World Weather Map --}}
            <div class="w-card position-relative">
                <!-- Search Box Overlay -->
                <div style="position: absolute; top: 18px; left: 18px; z-index: 1000; width: 240px;">
                    <div style="background: #fff; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.12); padding: 6px 10px; display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-search" style="color: #9CA3AF; font-size: 0.85rem; flex-shrink:0;"></i>
                        <input type="text" id="mapCountrySearch" placeholder="{{ __('Cari negara...') }}"
                            style="border: none; outline: none; background: transparent; font-size: 0.875rem; color: #374151; width: 100%; font-family: 'Inter', sans-serif;"
                            autocomplete="off">
                    </div>
                    <div id="mapSearchResults" style="display:none; background:#fff; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); margin-top: 6px; max-height: 280px; overflow-y: auto;"></div>
                </div>

                <div class="w-card-header" style="padding-left: 280px;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary bg-opacity-10 text-primary rounded d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="bi bi-map-fill"></i>
                        </div>
                        <span class="fw-bold text-dark fs-6">{{ __('World Weather Map') }}</span>
                    </div>
                    <div class="d-flex gap-3" style="font-size:0.8rem;font-weight:600;color:#6B7280;">
                        <span class="d-flex align-items-center gap-1"><span style="width:10px;height:10px;background:#F59E0B;border-radius:50%;display:inline-block;"></span> {{ __('Clear') }}</span>
                        <span class="d-flex align-items-center gap-1"><span style="width:10px;height:10px;background:#94A3B8;border-radius:50%;display:inline-block;"></span> {{ __('Cloudy') }}</span>
                        <span class="d-flex align-items-center gap-1"><span style="width:10px;height:10px;background:#0EA5E9;border-radius:50%;display:inline-block;"></span> {{ __('Rain') }}</span>
                        <span class="d-flex align-items-center gap-1"><span style="width:10px;height:10px;background:#EF4444;border-radius:50%;display:inline-block;"></span> {{ __('Storm') }}</span>
                        <span class="d-flex align-items-center gap-1"><span style="width:10px;height:10px;background:#60A5FA;border-radius:50%;display:inline-block;"></span> {{ __('Snow') }}</span>
                    </div>
                </div>
                @if(!$country)
                <div class="w-card-body p-4 p-md-5">
                    <div class="empty-state">
                        <i class="bi bi-map empty-state-icon"></i>
                        <div class="fw-semibold text-dark fs-5 mb-1">{{ __('Select a Country') }}</div>
                        <div class="text-secondary">{{ __('Please select a country from the sidebar to view weather map.') }}</div>
                    </div>
                </div>
                @else
                <div id="weatherMap"></div>
                @endif
            </div>

            {{-- 24 Hour Trend Chart --}}
            <div class="w-card">
                <div class="w-card-header">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-info bg-opacity-10 text-info rounded d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <span class="fw-bold text-dark fs-6">{{ __('24 Hours Weather Trend') }}</span>
                    </div>
                </div>
                <div class="w-card-body pt-4 pb-4">
                    @if($country && isset($rawData['hourly']['temperature_2m']) && count($rawData['hourly']['temperature_2m']) > 0)
                    <div style="height:220px;position:relative;">
                        <canvas id="trendChart"></canvas>
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="bi bi-bar-chart-line empty-state-icon"></i>
                        <div class="fw-semibold text-dark fs-5 mb-1">{{ __('No Trend Data') }}</div>
                        <div class="text-secondary">{{ __('Historical trend data is currently unavailable for this location.') }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Weather Details Mini Cards --}}
            @if($country)
            <div class="row g-4">
                <div class="col-6">
                    <div class="w-card h-100">
                        <div class="w-card-body d-flex align-items-center gap-4">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-4 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; flex-shrink: 0;">
                                <i class="bi bi-speedometer2 fs-4"></i>
                            </div>
                            <div>
                                <div class="text-secondary fw-semibold mb-1" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">{{ __('Pressure') }}</div>
                                <div class="fw-bold text-dark fs-4 lh-1">{{ $current['surface_pressure'] ?? '-' }}<span class="fs-6 text-muted ms-1 fw-normal">hPa</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="w-card h-100">
                        <div class="w-card-body d-flex align-items-center gap-4">
                            <div class="bg-secondary bg-opacity-10 text-secondary rounded-4 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; flex-shrink: 0;">
                                <i class="bi bi-clouds fs-4"></i>
                            </div>
                            <div>
                                <div class="text-secondary fw-semibold mb-1" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">{{ __('Cloud Cover') }}</div>
                                <div class="fw-bold text-dark fs-4 lh-1">{{ $current['cloud_cover'] ?? '-' }}<span class="fs-6 text-muted ms-1 fw-normal">%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>{{-- end center --}}

        {{-- ===== RIGHT: SUMMARY + ALERTS ===== --}}
        <div class="weather-right">

            {{-- Weather Summary Card --}}
            <div class="w-card flex-grow-0">
                @if(!$country)
                <div class="w-card-body py-5">
                    <div class="empty-state border-0 bg-transparent px-0">
                        <i class="bi bi-cloud-sun empty-state-icon"></i>
                        <div class="fw-semibold text-dark fs-5 mb-1">{{ __('No Data') }}</div>
                        <div class="text-secondary">{{ __('Select a country to view summary') }}</div>
                    </div>
                </div>
                @else
                {{-- Country Header --}}
                <div class="w-card-header bg-light">
                    <div class="d-flex align-items-center gap-3">
                        <span class="fi fi-{{ strtolower($country->iso2_code ?? 'un') }}" style="width:32px;height:24px;border-radius:4px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,0.15);"></span>
                        <div>
                            <div class="fw-bold text-dark fs-6 lh-1 mb-1">{{ $country->name }}</div>
                            <div class="text-secondary" style="font-size:0.75rem;">{{ now()->format('l, d M Y') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Big Weather --}}
                <div class="w-card-body text-center py-5">
                    <div class="weather-hero-icon mb-4" style="color: {{ $condHex ?? '#F59E0B' }};">
                        <i class="bi {{ $condIcon ?? 'bi-brightness-high-fill' }}"></i>
                    </div>
                    <div class="fw-bold text-dark lh-1" style="font-size:4.5rem; letter-spacing:-2px; margin-bottom: 0.5rem;">
                        {{ $wea ? round($wea->temperature) : '-' }}°
                    </div>
                    <div class="d-inline-flex align-items-center justify-content-center px-4 py-2 rounded-pill fw-bold" style="background-color: {{ $condBgHex ?? 'rgba(245,158,11,0.1)' }}; color: {{ $condHex ?? '#F59E0B' }}; font-size:0.9rem; letter-spacing:1px; text-transform: uppercase;">
                        {{ __($condText ?? 'Unknown') }}
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="px-4 pb-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 rounded-4 bg-light border border-white shadow-sm h-100 transition-all hover-lift">
                                <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                    <i class="bi bi-droplet-fill text-primary"></i>
                                    <span class="text-secondary fw-semibold" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:.5px;">Humidity</span>
                                </div>
                                <div class="fw-bold text-dark fs-5">{{ $current['relative_humidity_2m'] ?? '-' }}%</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 rounded-4 bg-light border border-white shadow-sm h-100 transition-all hover-lift">
                                <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                    <i class="bi bi-wind text-success"></i>
                                    <span class="text-secondary fw-semibold" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:.5px;">Wind</span>
                                </div>
                                <div class="fw-bold text-dark fs-5">{{ $wea ? round($wea->wind_speed) : '-' }}<span style="font-size:0.75rem;color:#6B7280;font-weight:normal;" class="ms-1">km/h</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Weather Alerts Card --}}
            <div class="w-card flex-grow-1 d-flex flex-column">
                <div class="w-card-header pb-3 pt-4 border-0">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-warning bg-opacity-10 text-warning rounded d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="bi bi-bell-fill"></i>
                        </div>
                        <span class="fw-bold text-dark fs-6">{{ __('Weather Alerts') }}</span>
                    </div>
                </div>
                <div class="w-card-body pt-0 d-flex flex-column flex-grow-1">
                    @if($country && isset($weatherAlert))
                    @php
                        $aType = $weatherAlert['type'] ?? 'success';
                        $aColors = [
                            'danger'  => ['bg'=>'#FEF2F2','border'=>'#FECDD3','text'=>'#EF4444','ico'=>'bi-exclamation-octagon-fill'],
                            'warning' => ['bg'=>'#FFFBEB','border'=>'#FDE68A','text'=>'#F59E0B','ico'=>'bi-exclamation-triangle-fill'],
                            'success' => ['bg'=>'#F0FDF4','border'=>'#BBF7D0','text'=>'#22C55E','ico'=>'bi-check-circle-fill'],
                            'info'    => ['bg'=>'#F0F9FF','border'=>'#BAE6FD','text'=>'#0EA5E9','ico'=>'bi-info-circle-fill'],
                        ];
                        // Force success if title is 'No Alert'
                        if($weatherAlert['title'] == 'No Alert' || $weatherAlert['title'] == 'Tidak Ada Peringatan') {
                            $aType = 'success';
                            $weatherAlert['title'] = 'No Active Alert';
                            $weatherAlert['message'] = 'Weather conditions are stable.';
                        }
                        $aC = $aColors[$aType] ?? $aColors['success'];
                    @endphp
                    <div class="alert-item flex-grow-1 d-flex align-items-start gap-3" style="background:{{ $aC['bg'] }};border-color:{{ $aC['border'] }};">
                        <div style="width:40px;height:40px;background:{{ $aC['text'] }}15;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi {{ $aC['ico'] }}" style="color:{{ $aC['text'] }};font-size:1.2rem;"></i>
                        </div>
                        <div>
                            <div class="fw-bold mb-1" style="color:{{ $aC['text'] }};font-size:0.95rem;">{{ __($weatherAlert['title']) }}</div>
                            <div class="text-secondary" style="font-size:0.85rem;line-height:1.5;">{{ __($weatherAlert['message']) }}</div>
                        </div>
                    </div>
                    @else
                    <div class="alert-item flex-grow-1 d-flex align-items-center gap-3" style="background:#F0FDF4;border-color:#BBF7D0;">
                        <div style="width:40px;height:40px;background:#22C55E15;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-check-circle-fill" style="color:#22C55E;font-size:1.2rem;"></i>
                        </div>
                        <div class="fw-bold" style="color:#22C55E;font-size:0.95rem;">{{ __('No Active Alert') }}</div>
                    </div>
                    @endif

                    {{-- Data source --}}
                    <div class="mt-4 pt-3 border-top border-light d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2 text-secondary fw-semibold" style="font-size:0.75rem;">
                            <i class="bi bi-database-fill-check text-primary"></i>
                            <span>{{ __('Open-Meteo API') }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- end right --}}

    </div>{{-- end 3-col layout --}}

</div>
@endsection


@push('scripts')
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
@php
    $mapSearchDataWeather = \App\Models\Country::orderBy('name')->get()->map(function($c) {
        return [
            'id'   => $c->id,
            'name' => $c->name,
            'iso2' => strtolower($c->iso2_code ?? ''),
            'url'  => route('user.weather', ['country_id' => $c->id]),
        ];
    })->values()->all();
@endphp
<script>
    const MAP_COUNTRIES_WEATHER = @json($mapSearchDataWeather);

    document.addEventListener('DOMContentLoaded', function() {
        // Map Search Overlay Functionality
        const mapSearchInput   = document.getElementById('mapCountrySearch');
        const mapSearchResults = document.getElementById('mapSearchResults');
        
        if (mapSearchInput && mapSearchResults) {

            function renderResultsWeather(term) {
                mapSearchResults.innerHTML = '';
                if (term.length === 0) {
                    mapSearchResults.style.display = 'none';
                    return;
                }
                const filtered = MAP_COUNTRIES_WEATHER.filter(c => c.name.toLowerCase().includes(term));
                if (filtered.length === 0) {
                    mapSearchResults.innerHTML = '<div style="padding:10px 14px;font-size:0.85rem;color:#6B7280;text-align:center;">Tidak ada negara ditemukan</div>';
                } else {
                    filtered.slice(0, 30).forEach(function(c) {
                        const item = document.createElement('a');
                        item.href = c.url;
                        item.style.cssText = 'display:flex;align-items:center;gap:10px;padding:10px 14px;text-decoration:none;color:#1F2937;border-bottom:1px solid #F3F4F6;transition:background 0.15s;';
                        item.onmouseover = function(){ this.style.background = '#F0F4FF'; };
                        item.onmouseout  = function(){ this.style.background = 'transparent'; };
                        const flagSpan = c.iso2
                            ? `<span class="fi fi-${c.iso2}" style="border-radius:3px;font-size:1.1em;flex-shrink:0;"></span>`
                            : `<i class="bi bi-globe" style="font-size:1rem;color:#9CA3AF;flex-shrink:0;"></i>`;
                        item.innerHTML = flagSpan + ` <span style="font-size:0.85rem;font-weight:500;">${c.name}</span>`;
                        mapSearchResults.appendChild(item);
                    });
                    if (filtered.length > 30) {
                        mapSearchResults.innerHTML += `<div style="padding:8px 14px;font-size:0.78rem;color:#9CA3AF;text-align:center;">+${filtered.length-30} negara lainnya, ketik lebih spesifik</div>`;
                    }
                }
                mapSearchResults.style.display = 'block';
            }

            mapSearchInput.addEventListener('input', function(e) {
                renderResultsWeather(e.target.value.trim().toLowerCase());
            });

            mapSearchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    mapSearchResults.style.display = 'none';
                    mapSearchInput.value = '';
                }
            });

            document.addEventListener('click', function(e) {
                if (!mapSearchInput.contains(e.target) && !mapSearchResults.contains(e.target)) {
                    mapSearchResults.style.display = 'none';
                }
            });
            
            mapSearchInput.addEventListener('focus', function() {
                if (this.value.trim().length > 0) renderResultsWeather(this.value.trim().toLowerCase());
            });
        }

        // 1. Live Search functionality
        const searchInput = document.getElementById('searchInput');
        const countryItems = document.querySelectorAll('.country-item');
        
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase();
                countryItems.forEach(item => {
                    const countryName = item.querySelector('.country-name').textContent.toLowerCase();
                    if (countryName.includes(term)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }

        @if($country && isset($country->latitude) && isset($country->longitude))
        // 2. Leaflet Map
        const mapElement = document.getElementById('weatherMap');
        if (mapElement && typeof L !== 'undefined') {
            const lat = {{ $country->latitude }};
            const lng = {{ $country->longitude }};
            const map = L.map('weatherMap', { zoomControl: false }).setView([20, 0], 2);

            L.tileLayer('http://mt0.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                attribution: '&copy; Google Maps',
                maxZoom: 20
            }).addTo(map);

            L.control.zoom({ position: 'bottomright' }).addTo(map);

            setTimeout(() => {
                map.flyTo([lat, lng], 5, {
                    duration: 2.0,
                    easeLinearity: 0.25
                });
            }, 400);

            @php
                function getMarkerStyle($cond) {
                    $cond = strtolower($cond);
                    if(str_contains($cond, 'snow')) return ['icon' => 'bi-snow', 'color' => '#60A5FA', 'bg' => 'rgba(96,165,250,0.1)'];
                    if(str_contains($cond, 'fog')) return ['icon' => 'bi-cloud-fog2-fill', 'color' => '#CBD5E1', 'bg' => 'rgba(203,213,225,0.15)'];
                    if(str_contains($cond, 'rain')) return ['icon' => 'bi-cloud-rain-fill', 'color' => '#3B82F6', 'bg' => 'rgba(59,130,246,0.1)'];
                    if(str_contains($cond, 'storm') || str_contains($cond, 'thunder')) return ['icon' => 'bi-cloud-lightning-rain-fill', 'color' => '#EF4444', 'bg' => 'rgba(239,68,68,0.1)'];
                    if(str_contains($cond, 'cloud')) return ['icon' => 'bi-cloud-fill', 'color' => '#94A3B8', 'bg' => 'rgba(148,163,184,0.1)'];
                    return ['icon' => 'bi-brightness-high-fill', 'color' => '#F59E0B', 'bg' => 'rgba(245,158,11,0.1)'];
                }
            @endphp

            // Main Active Marker
            @php $mStyle = getMarkerStyle($wea->condition ?? ''); @endphp

            const mainMarkerHtml = `
                <div class="premium-marker active-marker">
                    <i class="bi {{ $mStyle['icon'] }}" style="color: {{ $mStyle['color'] }};"></i>
                </div>
            `;
            const mainPopupHtml = `
                <div class="modern-popup">
                    <div class="modern-popup-header">
                        <div class="modern-popup-icon" style="background: {{ $mStyle['bg'] }}; color: {{ $mStyle['color'] }};">
                            <i class="bi {{ $mStyle['icon'] }}"></i>
                        </div>
                        <div>
                            <div class="modern-popup-temp">{{ round($wea->temperature ?? 0) }}°</div>
                            <div class="modern-popup-desc">{{ $wea->condition ?? 'Unknown' }}</div>
                        </div>
                    </div>
                    <div class="modern-popup-location">{{ $country->name }}</div>
                    <div class="modern-popup-country">{{ $country->region ?? 'Global' }}</div>
                    <div class="modern-popup-stats">
                        <div class="modern-popup-stat">
                            <i class="bi bi-droplet-fill"></i> {{ $current['relative_humidity_2m'] ?? '-' }}%
                        </div>
                        <div class="modern-popup-stat">
                            <i class="bi bi-wind"></i> {{ round($wea->wind_speed ?? 0) }} km/h
                        </div>
                    </div>
                </div>
            `;
            
            const mainMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    html: mainMarkerHtml,
                    className: '',
                    iconSize: [44, 44],
                    iconAnchor: [22, 22],
                    popupAnchor: [0, -26]
                })
            }).bindPopup(mainPopupHtml).addTo(map);

            // Other Countries
            @foreach($countries as $other)
                @if($other->id !== $country->id && $other->weatherCaches->isNotEmpty())
                    @php 
                        $ow = $other->weatherCaches->first();
                        $oStyle = getMarkerStyle($ow->condition ?? '');
                        $oRaw = is_string($ow->raw_data) ? json_decode($ow->raw_data, true) : $ow->raw_data;
                        $oHum = $oRaw['current']['relative_humidity_2m'] ?? '-';
                    @endphp
                    
                    const markerHtml_{{ $other->id }} = `
                        <div class="premium-marker">
                            <i class="bi {{ $oStyle['icon'] }}" style="color: {{ $oStyle['color'] }};"></i>
                        </div>
                    `;
                    const popupHtml_{{ $other->id }} = `
                        <div class="modern-popup">
                            <div class="modern-popup-header">
                                <div class="modern-popup-icon" style="background: {{ $oStyle['bg'] }}; color: {{ $oStyle['color'] }};">
                                    <i class="bi {{ $oStyle['icon'] }}"></i>
                                </div>
                                <div>
                                    <div class="modern-popup-temp">{{ round($ow->temperature ?? 0) }}°</div>
                                    <div class="modern-popup-desc">{{ $ow->condition ?? 'Unknown' }}</div>
                                </div>
                            </div>
                            <div class="modern-popup-location">{{ addslashes($other->name) }}</div>
                            <div class="modern-popup-country">{{ addslashes($other->region ?? 'Global') }}</div>
                            <div class="modern-popup-stats">
                                <div class="modern-popup-stat">
                                    <i class="bi bi-droplet-fill"></i> {{ $oHum }}%
                                </div>
                                <div class="modern-popup-stat">
                                    <i class="bi bi-wind"></i> {{ round($ow->wind_speed ?? 0) }} km/h
                                </div>
                            </div>
                        </div>
                    `;

                    const m_{{ $other->id }} = L.marker([{{ $other->latitude }}, {{ $other->longitude }}], {
                        icon: L.divIcon({
                            html: markerHtml_{{ $other->id }},
                            className: '',
                            iconSize: [44, 44],
                            iconAnchor: [22, 22],
                            popupAnchor: [0, -26]
                        })
                    }).bindPopup(popupHtml_{{ $other->id }}).addTo(map);
                @endif
            @endforeach
        }
        @endif

        @if($country && isset($rawData['hourly']['temperature_2m']))
        // 3. Trend Chart
        const trendCtx = document.getElementById('trendChart');
        if (trendCtx) {
            const times = {!! json_encode(array_slice($rawData['hourly']['time'] ?? [], 0, 24, true)) !!};
            const temps = {!! json_encode(array_slice($rawData['hourly']['temperature_2m'] ?? [], 0, 24, true)) !!};
            
            const labels = times.map(t => {
                const d = new Date(t);
                return d.getHours().toString().padStart(2, '0') + ':00';
            });

            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Temperature (°C)',
                        data: temps,
                        borderColor: '#2563EB',
                        backgroundColor: 'rgba(37, 99, 235, 0.07)',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#2563EB',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#111827',
                            bodyColor: '#6B7280',
                            borderColor: '#E5E7EB',
                            borderWidth: 1,
                            cornerRadius: 10,
                            padding: 10,
                            callbacks: { label: (ctx) => ctx.parsed.y + '°C' }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#9CA3AF', font: { size: 11 }, maxTicksLimit: 8, maxRotation: 0 }
                        },
                        y: {
                            display: true,
                            grid: { color: 'rgba(0,0,0,0.04)' },
                            ticks: { color: '#9CA3AF', font: { size: 11 }, callback: (val) => val + '°' },
                            min: Math.min(...temps) - 2,
                            max: Math.max(...temps) + 2
                        }
                    }
                }
            });
        }
        @endif
    });
</script>
@endpush
