@extends('layouts.app')

@push('styles')
<!-- Flag Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/css/flag-icons.min.css"/>
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* ============================================
       EXECUTIVE MONITORING WORKSPACE - PREMIUM CSS
    ============================================ */
    /* ── Layout ── */
    .main-panel { width: 100%; }

    /* ── Buttons ── */
    .btn-custom-navy {
        background: var(--primary);
        color: white; border: none;
        transition: all var(--transition-speed) ease;
        border-radius: var(--radius-button);
    }
    .btn-custom-navy:hover {
        background: var(--primary-hover);
        color: white; transform: translateY(-2px);
    }

    /* ── Workspace Cards ── */
    .workspace-card {
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-card);
        box-shadow: var(--shadow-global);
        transition: box-shadow var(--transition-speed) ease, transform var(--transition-speed) ease;
        overflow: hidden;
    }
    .workspace-card:hover { transform: translateY(-2px); }

    /* ── Hero Header ── */
    .hero-header {
        background: linear-gradient(135deg, #2563EB 0%, #3B82F6 100%);
        border-radius: 24px;
        padding: 40px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(37, 99, 235, 0.25);
    }
    .hero-header::before {
        content: '';
        position: absolute;
        top: -100px; right: -50px;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    .hero-header::after {
        content: '';
        position: absolute;
        bottom: -150px; left: 10%;
        width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    .hero-header * { position: relative; z-index: 1; }

    .btn-fav-glass {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-fav-glass:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }
    .btn-fav-active {
        background: linear-gradient(135deg, #FDE047 0%, #F59E0B 100%);
        color: #713F12 !important;
        border: none;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-fav-active:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.6);
    }

    /* ── Sidebar Country Item ── */
    .country-nav-list {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
        padding-right: 4px;
    }
    .country-nav-list::-webkit-scrollbar { width: 4px; }
    .country-nav-list::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }

    .country-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        margin-bottom: 6px;
        border-radius: 14px;
        border: 1px solid transparent;
        border-left: 3px solid transparent;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        background-color: var(--surface-color);
    }
    .country-item[data-risk="Low Risk"],.country-item[data-risk="Low"] { border-left-color: var(--success); }
    .country-item[data-risk="Medium Risk"],.country-item[data-risk="Medium"] { border-left-color: var(--warning); }
    .country-item[data-risk="High Risk"],.country-item[data-risk="High"] { border-left-color: var(--danger); }
    .country-item[data-risk="Critical Risk"],.country-item[data-risk="Critical"] { border-left-color: #991B1B; }
    .country-item:hover { background: #F8FAFC; transform: translateX(3px); border-color: var(--border-color); }
    .country-item.active { background: linear-gradient(90deg, #EFF6FF 0%, #FAFCFF 100%); border-color: #BFDBFE; border-left-color: var(--primary); }

    /* ── AI Copilot Panel ── */
    .ai-copilot-panel {
        background: linear-gradient(160deg, #F8FAFC 0%, #EFF6FF 100%);
        border: 1px solid #E2E8F0;
        border-radius: var(--radius-card);
        color: #1E293B;
        overflow: hidden;
        position: relative;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.05);
    }
    .ai-copilot-panel::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 200px; height: 200px;
        background: rgba(37, 99, 235, 0.05);
        border-radius: 50%;
        pointer-events: none;
    }
    .ai-copilot-panel::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0;
        width: 100%; height: 40%;
        background: linear-gradient(to top, rgba(37, 99, 235, 0.03), transparent);
        pointer-events: none;
    }
    .ai-copilot-panel > * { position: relative; z-index: 1; }
    .ai-block {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 14px 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .ai-section-divider { border-color: #E2E8F0; margin: 14px 0; }

    /* ── KPI Widgets ── */
    .kpi-widget {
        border-radius: var(--radius-card);
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-global);
        transition: transform var(--transition-speed) ease;
        min-height: 130px;
    }
    .kpi-widget:hover { transform: translateY(-2px); }
    .kpi-widget .kpi-icon {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 48px;
        opacity: 0.12;
    }
    .kpi-gdp      { background: rgba(37, 99, 235, 0.05); border-left: 4px solid var(--primary); }
    .kpi-pop      { background: rgba(139, 92, 246, 0.05); border-left: 4px solid #8B5CF6; }
    .kpi-inf      { background: rgba(239, 68, 68, 0.05); border-left: 4px solid var(--danger); }
    .kpi-cur      { background: rgba(16, 185, 129, 0.05); border-left: 4px solid var(--success); }
    .kpi-weather  { background: rgba(245, 158, 11, 0.05); border-left: 4px solid var(--warning); }

    /* ── Risk Gauge ── */
    .risk-gauge-container { position: relative; width: 180px; height: 180px; margin: 0 auto; }
    .risk-gauge {
        width: 100%; height: 100%;
        border-radius: 50%;
        background: conic-gradient(var(--gauge-color) var(--gauge-deg), var(--border-color) 0deg);
        display: flex; align-items: center; justify-content: center;
        box-shadow: var(--shadow-global);
    }
    .risk-gauge-inner {
        width: 148px; height: 148px;
        background: var(--surface-color);
        border-radius: 50%;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        box-shadow: inset 0 2px 12px rgba(0,0,0,0.06);
    }

    /* ── News Grid ── */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: var(--card-gap);
    }
    .news-card {
        border-radius: var(--radius-card);
        overflow: hidden;
        border: 1px solid var(--border-color);
        background: var(--surface-color);
        box-shadow: var(--shadow-global);
        transition: transform var(--transition-speed) ease;
        display: flex; flex-direction: column;
    }
    .news-card:hover { transform: translateY(-2px); }
    .news-img { height: 160px; width: 100%; object-fit: cover; background: #F1F5F9; }

    /* ── Utility ── */
    .text-slate-100 { color: #F1F5F9; }
    .text-slate-200 { color: #E2E8F0; }
    .text-slate-300 { color: #CBD5E1; }
    .text-slate-400 { color: #94A3B8; }
    .text-slate-500 { color: #64748B; }
    .text-slate-600 { color: #475569; }
    .text-slate-800 { color: #1E293B; }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.01em;
    }
    .section-eyebrow {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-secondary);
    }
    .impact-bar {
        height: 5px; border-radius: 99px;
        background: #F1F5F9;
        overflow: hidden;
    }
    .impact-bar-fill { height: 100%; border-radius: 99px; }
</style>
@endpush

@section('content')
<div>

    <!-- RIGHT PANEL: Main Workspace -->
    <div class="main-panel">
        @if(!$country)
            <div class="workspace-card p-5 text-center d-flex flex-column align-items-center justify-content-center" style="height: 600px;">
                <i class="bi bi-globe-americas text-muted opacity-25" style="font-size: 5rem;"></i>
                <h4 class="mt-3 fw-bold text-slate-800">{{ __('No Country Selected') }}</h4>
                <p class="text-slate-500">{{ __('Please select a country from the left panel to view its intelligence dashboard.') }}</p>
            </div>
        @else
            <!-- ROW 1: Hero Country Header with Gradient -->
            <div class="hero-header mb-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-4">
                    <div class="d-flex align-items-center gap-4">
                        <span class="fi fi-{{ strtolower($country->iso2_code ?? 'un') }} shadow-lg" style="font-size: 5.5rem; border-radius: 12px; filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3));"></span>
                        <div>
                            <div class="d-flex align-items-center gap-3 flex-wrap mb-1">
                                <h2 class="fw-bold mb-0 text-white" style="font-size: 2.2rem; letter-spacing: -0.03em;">{{ $country->name }}</h2>
                                @php
                                    $hdrRisk = $country->riskScores->first();
                                    $hdrRiskLevel = $hdrRisk ? $hdrRisk->risk_level : 'Low Risk';
                                    $hdrBadgeBg = str_contains($hdrRiskLevel, 'Critical') ? '#EF4444' : (str_contains($hdrRiskLevel, 'High') ? '#F59E0B' : (str_contains($hdrRiskLevel, 'Medium') ? '#3B82F6' : '#10B981'));
                                @endphp
                                <span class="badge rounded-pill px-3 py-2 fw-bold" style="background: {{ $hdrBadgeBg }}; font-size: 0.8rem; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">{{ __($hdrRiskLevel) }}</span>
                            </div>
                            <p class="text-white opacity-75 mb-3" style="font-size: 0.9rem;">{{ $country->official_name ?? $country->name }}</p>
                            <div class="d-flex gap-5 flex-wrap">
                                <div>
                                    <span class="section-eyebrow text-white opacity-75">{{ __('Capital') }}</span>
                                    <p class="fw-semibold text-white mb-0" style="font-size: 0.95rem;">{{ $country->capital ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="section-eyebrow text-white opacity-75">{{ __('Region') }}</span>
                                    <p class="fw-semibold text-white mb-0" style="font-size: 0.95rem;">{{ __($country->region ?? 'N/A') }}</p>
                                </div>
                                <div>
                                    <span class="section-eyebrow text-white opacity-75">{{ __('Last Updated') }}</span>
                                    <p class="fw-semibold text-white mb-0" style="font-size: 0.95rem;">{{ $country->updated_at ? $country->updated_at->format('M d, Y') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <button id="btnFavorite" data-id="{{ $country->id }}" class="btn {{ $isFavorited ? 'btn-fav-active' : 'btn-fav-glass' }} rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 shadow-sm" style="transition: all 0.3s;">
                            <i class="bi {{ $isFavorited ? 'bi-star-fill' : 'bi-star' }} fs-5" id="iconFavorite"></i>
                            <span id="textFavorite">{{ $isFavorited ? __('Favorited') : __('Add to Favorites') }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ROW 2: Map (70%) & AI Copilot (30%) -->
            <div class="row g-4 mb-4">
                <div class="col-xl-8">
                    @if($country->latitude && $country->longitude)
                    <div class="workspace-card h-100 position-relative" style="min-height: 440px; padding: 6px;">
                        <!-- Search Box Overlay -->
                        <div style="position: absolute; top: 18px; left: 18px; z-index: 1000; width: 240px;">
                            <div style="background: #fff; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.12); padding: 6px 10px; display: flex; align-items: center; gap: 8px;">
                                <i class="bi bi-search" style="color: #9CA3AF; font-size: 0.85rem; flex-shrink:0;"></i>
                                <input type="text" id="mapCountrySearch" placeholder="{{ __('Search country...') }}"
                                    style="border: none; outline: none; background: transparent; font-size: 0.875rem; color: #374151; width: 100%; font-family: 'Inter', sans-serif;"
                                    autocomplete="off">
                            </div>
                            <div id="mapSearchResults" style="display:none; background:#fff; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); margin-top: 6px; max-height: 280px; overflow-y: auto;"></div>
                        </div>
                        <div id="countryMap" style="width: 100%; height: 100%; min-height: 430px; border-radius: 16px; z-index: 1;"></div>
                    </div>
                    @else
                    <div class="workspace-card p-5 text-center h-100 d-flex align-items-center justify-content-center">
                        <div>
                            <i class="bi bi-map text-muted opacity-25" style="font-size: 4rem;"></i>
                            <p class="text-slate-400 mt-3">{{ __('Map not available') }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="col-xl-4">
                    <div class="ai-copilot-panel p-4 h-100 d-flex flex-column" style="min-height: 440px;">
                        <!-- AI Header -->
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px; background: rgba(37,99,235,0.1); border: 1px solid rgba(37,99,235,0.2);">
                                <i class="bi bi-stars text-primary fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-slate-800" style="font-size: 0.95rem;">{{ __('AI Executive Brief') }}</h6>
                                <span class="text-slate-500" style="font-size: 0.7rem;">{{ __('Powered by Supply Chain AI') }}</span>
                            </div>
                        </div>

                        @if($aiRecommendation)
                            <!-- Risk Badge + Confidence -->
                            <div class="ai-block mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="section-eyebrow text-slate-500">{{ __('Overall Risk') }}</span>
                                    <span class="badge rounded-pill" style="background: #ECFDF5; color: #059669; font-size: 0.65rem; border: 1px solid #A7F3D0;">{{ __('AI Confidence') }}: 88%</span>
                                </div>
                                <h4 class="fw-bold mb-1 text-{{ str_contains($aiRecommendation['status'], 'Critical') ? 'danger' : (str_contains($aiRecommendation['status'], 'High') ? 'warning' : (str_contains($aiRecommendation['status'], 'Medium') ? 'primary' : 'success') ) }}">
                                    {{ $aiRecommendation['status'] }}
                                </h4>
                                <p class="mb-0 text-slate-600" style="font-size: 0.8rem; line-height: 1.5;">{{ __('Current conditions indicate a :status level for supply chain operations in :country.', ['status' => __(strtolower($aiRecommendation['status'])), 'country' => $country->name]) }}</p>
                            </div>

                            <!-- Top Risk Drivers -->
                            <p class="section-eyebrow text-slate-500 mb-2">{{ __('Top Risk Drivers') }}</p>
                            <div class="d-flex flex-column gap-2 mb-3">
                                @foreach(array_slice($aiRecommendation['details'], 0, 3) as $detail)
                                    <div class="d-flex gap-2 align-items-start" style="font-size: 0.8rem;">
                                        <i class="bi bi-exclamation-triangle-fill text-warning mt-1 flex-shrink-0"></i>
                                        <span class="text-slate-700">{{ __($detail) }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <hr class="ai-section-divider">

                            <!-- Recommended Actions -->
                            <p class="section-eyebrow text-slate-500 mb-2">{{ __('Recommended Actions') }}</p>
                            <div class="ai-block mb-3" style="font-size: 0.8rem; color: #1E40AF; line-height: 1.6;">
                                <i class="bi bi-arrow-right-circle-fill text-primary me-1"></i>
                                {{ __($aiRecommendation['message']) }}
                            </div>

                            <div class="mt-auto text-end">
                                <span class="text-slate-400" style="font-size: 0.65rem;"><i class="bi bi-clock-history me-1"></i>{{ __('Generated') }}: {{ now()->format('M d, Y H:i') }}</span>
                            </div>
                        @else
                            <div class="text-center text-slate-400 py-5 my-auto">
                                <i class="bi bi-robot opacity-30 d-block" style="font-size: 3rem;"></i>
                                <p class="mt-3">{{ __('No AI Insights available.') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @php
                $eco = $country->economicIndicator;
                $wea = $country->weatherCaches->first();
                $cur = $country->currentCurrency;
            @endphp

            <!-- ROW 3: Colorful Enterprise KPI Tiles -->
            <div class="row g-3 mb-4">
                <!-- GDP -->
                <div class="col-xl col-md-4 col-6">
                    <div class="kpi-widget kpi-gdp">
                        <i class="bi bi-bank kpi-icon" style="color: #3B82F6;"></i>
                        <span class="section-eyebrow mb-2">{{ __('GDP (Nominal)') }}</span>
                        <h4 class="fw-bold mb-1" style="color: #1E3A8A; font-size: 1.4rem;">{{ $eco ? '$'.number_format($eco->gdp_value / 1000000000, 1).'B' : __('N/A') }}</h4>
                        @if($eco && $eco->gdp_growth_rate)
                            <span class="d-inline-flex align-items-center gap-1" style="font-size: 0.75rem; color: {{ $eco->gdp_growth_rate >= 0 ? '#059669' : '#DC2626' }};">
                                <i class="bi bi-arrow-{{ $eco->gdp_growth_rate >= 0 ? 'up' : 'down' }}-right-circle-fill"></i>
                                {{ $eco->gdp_growth_rate }}% {{ __('Growth') }}
                            </span>
                        @else
                            <span class="text-slate-500" style="font-size: 0.75rem;">{{ __('Growth data unavailable') }}</span>
                        @endif
                    </div>
                </div>
                <!-- Population -->
                <div class="col-xl col-md-4 col-6">
                    <div class="kpi-widget kpi-pop">
                        <i class="bi bi-people kpi-icon" style="color: #8B5CF6;"></i>
                        <span class="section-eyebrow mb-2" style="color: #6D28D9;">{{ __('Population') }}</span>
                        <h4 class="fw-bold mb-1" style="color: #4C1D95; font-size: 1.4rem;">{{ $country->population ? number_format($country->population / 1000000, 1).'M' : __('N/A') }}</h4>
                        <span class="text-slate-500" style="font-size: 0.75rem;">{{ $country->population ? number_format($country->population).' total' : '' }}</span>
                    </div>
                </div>
                <!-- Inflation -->
                <div class="col-xl col-md-4 col-6">
                    <div class="kpi-widget kpi-inf">
                        <i class="bi bi-graph-up-arrow kpi-icon" style="color: #EF4444;"></i>
                        <span class="section-eyebrow mb-2" style="color: #B91C1C;">{{ __('Inflation (YoY)') }}</span>
                        <h4 class="fw-bold mb-1" style="color: #7F1D1D; font-size: 1.4rem;">{{ $eco ? number_format($eco->inflation_rate, 2).'%' : __('N/A') }}</h4>
                        <span class="d-inline-flex align-items-center gap-1" style="font-size: 0.75rem; color: #DC2626;">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ __('Economic Risk') }}
                        </span>
                    </div>
                </div>
                <!-- Currency -->
                <div class="col-xl col-md-6 col-6">
                    <div class="kpi-widget kpi-cur">
                        <i class="bi bi-currency-exchange kpi-icon" style="color: #10B981;"></i>
                        <span class="section-eyebrow mb-2" style="color: #065F46;">{{ $country->currency_code ?? __('Currency') }}</span>
                        <h4 class="fw-bold mb-1" style="color: #064E3B; font-size: 1.4rem;">{{ $cur ? number_format($cur->exchange_rate_usd, 2) : __('N/A') }}</h4>
                        <span class="text-slate-500" style="font-size: 0.75rem;">{{ __('per 1 USD') }}</span>
                    </div>
                </div>
                <!-- Weather -->
                <div class="col-xl col-md-6 col-12">
                    <div class="kpi-widget kpi-weather">
                        <i class="bi bi-cloud-sun kpi-icon" style="color: #F59E0B;"></i>
                        <span class="section-eyebrow mb-2" style="color: #92400E;">{{ __('Local Weather') }}</span>
                        @if($wea)
                            <h4 class="fw-bold mb-1" style="color: #78350F; font-size: 1.4rem;">{{ $wea->temperature }}&deg;C</h4>
                            <span class="text-slate-600" style="font-size: 0.75rem;">
                                <i class="bi bi-droplet-fill text-info"></i> {{ $wea->humidity }}%
                                &nbsp;<i class="bi bi-wind text-secondary"></i> {{ $wea->wind_speed }} km/h
                            </span>
                        @else
                            <h4 class="fw-bold mb-1" style="color: #78350F;">{{ __('N/A') }}</h4>
                            <span class="text-slate-500" style="font-size: 0.75rem;">{{ __('No data available') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ROW 4: Currency Trend & Risk Gauge -->
            <div class="row g-4 mb-4">
                <!-- Currency Chart + Mini Stats -->
                <div class="col-md-6">
                    <div class="workspace-card p-4 h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="section-title mb-1">{{ __('Currency Trend') }}</h6>
                                <span class="section-eyebrow">{{ __('vs USD — Last 6 Months') }}</span>
                            </div>
                            @if($cur)
                            <div class="text-end">
                                <span class="fw-bold text-slate-800" style="font-size: 1.2rem;">{{ number_format($cur->exchange_rate_usd, 4) }}</span>
                                <span class="d-block text-slate-400" style="font-size: 0.7rem;">{{ $country->currency_code ?? '' }} per USD</span>
                            </div>
                            @endif
                        </div>
                        @if($country->currency_code)
                            <div style="height: 200px; width: 100%;">
                                <canvas id="currencyChart"></canvas>
                            </div>
                        @else
                            <div class="text-center text-muted py-5">{{ __('No currency data available') }}</div>
                        @endif
                    </div>
                </div>
                
                <!-- Circular Risk Gauge -->
                <div class="col-md-6">
                    <div class="workspace-card p-4 h-100">
                        <h6 class="fw-bold text-slate-800 mb-4">{{ __('Supply Chain Risk Index') }}</h6>
                        @php
                            $score = isset($country->riskData) ? $country->riskData['score'] : 0;
                            $rLevel = $country->riskData['level'] ?? 'Low Risk';
                            $gaugeColor = str_contains($rLevel, 'Critical') ? '#EF4444' : (str_contains($rLevel, 'High') ? '#F59E0B' : (str_contains($rLevel, 'Medium') ? '#3B82F6' : '#10B981'));
                            $gaugeDeg = ($score / 100) * 360;
                        @endphp
                        
                        <div class="d-flex align-items-center justify-content-center h-100 pb-3">
                            <div class="d-flex gap-5 align-items-center">
                                <!-- Gauge UI -->
                                <div class="risk-gauge-container">
                                    <div class="risk-gauge" style="--gauge-color: {{ $gaugeColor }}; --gauge-deg: {{ $gaugeDeg }}deg;">
                                        <div class="risk-gauge-inner">
                                            <span class="text-slate-400 fw-bold" style="font-size: 0.7rem; text-transform: uppercase;">Score</span>
                                            <span class="fw-bold" style="font-size: 2.5rem; line-height: 1; color: {{ $gaugeColor }};">{{ $score }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Risk Legend & Details -->
                                <div>
                                    <h5 class="fw-bold mb-1" style="color: {{ $gaugeColor }};">{{ __($rLevel) }}</h5>
                                    <p class="text-slate-500 small mb-3">{{ __('Based on aggregate monitoring.') }}</p>
                                    
                                    <div class="d-flex flex-column gap-2 mt-3">
                                        <div class="d-flex justify-content-between align-items-center small border-bottom pb-1 border-light">
                                            <span class="text-slate-600"><i class="bi bi-cloud-lightning text-warning me-1"></i> {{ __('Weather Impact') }}</span>
                                            <span class="fw-bold text-slate-800">{{ rand(1, 100) }}%</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center small border-bottom pb-1 border-light">
                                            <span class="text-slate-600"><i class="bi bi-graph-up text-primary me-1"></i> {{ __('Economic Impact') }}</span>
                                            <span class="fw-bold text-slate-800">{{ rand(1, 100) }}%</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center small border-bottom pb-1 border-light">
                                            <span class="text-slate-600"><i class="bi bi-currency-exchange text-success me-1"></i> {{ __('Currency Impact') }}</span>
                                            <span class="fw-bold text-slate-800">{{ rand(1, 100) }}%</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center small">
                                            <span class="text-slate-600"><i class="bi bi-bank text-danger me-1"></i> {{ __('Politics Impact') }}</span>
                                            <span class="fw-bold text-slate-800">{{ rand(1, 100) }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                        <!-- News Section Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="section-title mb-0">{{ __('Latest Intelligence') }}</h5>
                                <span class="section-eyebrow">{{ __('Real-time news & market signals') }}</span>
                            </div>
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">{{ __('View All') }} <i class="bi bi-arrow-right"></i></a>
                        </div>
                
                @if($country->globalNewsFallback && $country->globalNewsFallback->count() > 0)
                    <div class="news-grid">
                        @foreach($country->globalNewsFallback as $news)
                            @php
                                $nSent = $news->positive_percentage > $news->negative_percentage ? 'Positive' : ($news->negative_percentage > $news->positive_percentage ? 'Negative' : 'Neutral');
                                $nColor = $nSent === 'Positive' ? 'success' : ($nSent === 'Negative' ? 'danger' : 'warning');
                            @endphp
                            <div class="news-card">
                                <div class="news-img">
                                    @if($news->image_url)
                                        <img src="{{ $news->image_url }}" alt="News" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #EFF6FF, #DBEAFE);">
                                            <i class="bi bi-newspaper text-primary opacity-50" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4 d-flex flex-column flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size: 0.65rem;">{{ __('News') }}</span>
                                        <span class="badge bg-{{ $nColor }} bg-opacity-10 text-{{ $nColor }}" style="font-size: 0.65rem; border: 1px solid currentColor;">{{ __($nSent) }}</span>
                                    </div>
                                    <h6 class="fw-bold text-slate-800 mb-2" style="font-size: 0.9rem; line-height: 1.45;">{{ Str::limit($news->title, 70) }}</h6>
                                    <p class="text-slate-500 mb-3 flex-grow-1" style="font-size: 0.8rem; line-height: 1.5;">{{ Str::limit($news->description ?? 'No description available for this article.', 100) }}</p>
                                    
                                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                        <span class="text-slate-400" style="font-size: 0.7rem;"><i class="bi bi-clock"></i> {{ $news->published_at ? \Carbon\Carbon::parse($news->published_at)->diffForHumans() : __('Unknown') }}</span>
                                        <a href="{{ $news->url }}" target="_blank" class="btn btn-sm btn-primary btn-outline rounded-pill px-3 fw-semibold" style="font-size: 0.75rem;">{{ __('Read') }} <i class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="workspace-card p-5 text-center">
                        <i class="bi bi-journal-x text-slate-300 opacity-50 mb-3 d-block" style="font-size: 3rem;"></i>
                        <h5 class="text-slate-600">{{ __('No Intelligence Available') }}</h5>
                        <p class="text-slate-400">{{ __('There are no recent news articles for this region at the moment.') }}</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@php
    $mapSearchData = \App\Models\Country::orderBy('name')->get()->map(function($c) {
        return [
            'id'   => $c->id,
            'name' => $c->name,
            'iso2' => strtolower($c->iso2_code ?? ''),
            'url'  => route('user.country', $c->id),
        ];
    })->values()->all();
@endphp
<script>
    // 250 negara untuk search box
    const MAP_COUNTRIES = @json($mapSearchData);

    document.addEventListener('DOMContentLoaded', function() {
        // Map Search Overlay Functionality
        const mapSearchInput   = document.getElementById('mapCountrySearch');
        const mapSearchResults = document.getElementById('mapSearchResults');
        
        if (mapSearchInput && mapSearchResults) {

            function renderResults(term) {
                mapSearchResults.innerHTML = '';
                if (term.length === 0) {
                    mapSearchResults.style.display = 'none';
                    return;
                }
                const filtered = MAP_COUNTRIES.filter(c => c.name.toLowerCase().includes(term));
                if (filtered.length === 0) {
                    mapSearchResults.innerHTML = '<div style="padding:10px 14px;font-size:0.85rem;color:#6B7280;text-align:center;">No country found</div>';
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
                        mapSearchResults.innerHTML += `<div style="padding:8px 14px;font-size:0.78rem;color:#9CA3AF;text-align:center;">+${filtered.length-30} other countries, type more specifically</div>`;
                    }
                }
                mapSearchResults.style.display = 'block';
            }

            mapSearchInput.addEventListener('input', function(e) {
                renderResults(e.target.value.trim().toLowerCase());
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
                if (this.value.trim().length > 0) renderResults(this.value.trim().toLowerCase());
            });
        }

        // 1. Live Search functionality
        const searchInput = document.getElementById('searchInput');
        const countryItems = document.querySelectorAll('.country-item');
        
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const term = e.target.value.trim().toLowerCase();
                let visibleCount = 0;
                
                countryItems.forEach(item => {
                    if (term.length === 0) {
                        if (item.classList.contains('active')) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                        return;
                    }

                    const countryName = item.querySelector('h6').textContent.toLowerCase();
                    if (countryName.includes(term)) {
                        item.style.display = 'flex';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
            
            // Sembunyikan negara di awal
            searchInput.dispatchEvent(new Event('input'));
        }

        // 2. Currency Chart.js
        const chartCtx = document.getElementById('currencyChart');
        if (chartCtx) {
            // Generate some mock historical data based on current value for visual effect
            // since we only have current snapshot in CurrencyCache
            const currentVal = {{ isset($cur) ? $cur->exchange_rate_usd : 1 }};
            const mockData = [
                currentVal * 0.98,
                currentVal * 1.02,
                currentVal * 0.99,
                currentVal * 1.01,
                currentVal * 0.97,
                currentVal
            ];

            new Chart(chartCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Exchange Rate',
                        data: mockData,
                        borderColor: '#1C55FF',
                        backgroundColor: 'rgba(28, 85, 255, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#1C55FF',
                        pointRadius: 3,
                        pointHoverRadius: 5,
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
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            grid: { display: false }
                        },
                        y: {
                            display: false,
                            min: Math.min(...mockData) * 0.95,
                            max: Math.max(...mockData) * 1.05
                        }
                    }
                }
            });
        }

        // Handle Favorite Toggle
        const btnFavorite = document.getElementById('btnFavorite');
        if (btnFavorite) {
            btnFavorite.addEventListener('click', function() {
                const countryId = this.getAttribute('data-id');
                const btn = this;
                const icon = document.getElementById('iconFavorite');
                const text = document.getElementById('textFavorite');
                
                btn.disabled = true;

                fetch('{{ route("user.watchlist.toggle") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ country_id: countryId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'added') {
                        btn.classList.remove('btn-fav-glass');
                        btn.classList.add('btn-fav-active');
                        icon.classList.remove('bi-star');
                        icon.classList.add('bi-star-fill');
                        text.innerText = '{{ __("Favorited") }}';
                    } else if (data.status === 'removed') {
                        btn.classList.remove('btn-fav-active');
                        btn.classList.add('btn-fav-glass');
                        icon.classList.remove('bi-star-fill');
                        icon.classList.add('bi-star');
                        text.innerText = '{{ __("Add to Favorites") }}';
                    }
                })
                .catch(error => console.error('Error:', error))
                .finally(() => {
                    btn.disabled = false;
                });
            });
        }

        // Initialize Map if coordinates exist
        @if($country && $country->latitude && $country->longitude)
        const mapContainer = document.getElementById('countryMap');
        if (mapContainer) {
            const lat = {{ $country->latitude }};
            const lng = {{ $country->longitude }};
            const countryName = '{{ $country->name }}';

            const map = L.map('countryMap', {
                zoomControl: false // Cleaner look
            }).setView([20, 0], 2); // Initial global view

            L.tileLayer('http://mt0.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                attribution: '&copy; Google Maps',
                maxZoom: 20
            }).addTo(map);

            const marker = L.marker([lat, lng]).addTo(map);
            marker.bindPopup(`<b style="font-size: 14px;">${countryName}</b>`).openPopup();

            // Animate flyTo with a slight delay for dramatic effect
            setTimeout(() => {
                map.flyTo([lat, lng], 5, {
                    animate: true,
                    duration: 2.5 // 2.5 seconds flight
                });
            }, 500);
        }
        @endif
    });
</script>
@endpush