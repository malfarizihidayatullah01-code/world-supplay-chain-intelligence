@extends('layouts.app')

@push('styles')
<!-- Flag Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/css/flag-icons.min.css"/>
<style>
    /* Page Container */
    .currency-monitoring-page {
        background-color: transparent;
        min-height: calc(100vh - 80px);
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Modern Card */
    .modern-card {
        background-color: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-card);
        box-shadow: var(--shadow-global);
        transition: all var(--transition-speed) ease;
    }
    
    .modern-card:hover {
        transform: translateY(-2px);
    }

    /* Chart Container (Massive) */
    .chart-wrapper {
        height: 380px;
        width: 100%;
        position: relative;
    }

    .empty-chart-state {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(4px);
        border-radius: 12px;
        border: 2px dashed #cbd5e1;
        z-index: 10;
        text-align: center;
        padding: 20px;
    }

    /* Compact Converter */
    .compact-converter {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .converter-box {
        flex: 1;
        min-width: 140px;
        padding: 12px 16px;
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-card);
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        transition: all var(--transition-speed);
    }
    .converter-box:hover {
        border-color: var(--primary);
        background: rgba(37, 99, 235, 0.05);
    }
    
    .converter-result {
        flex: 2;
        min-width: 200px;
        padding: 12px 20px;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(37, 99, 235, 0.05) 100%);
        border-radius: var(--radius-card);
        border: 1px solid rgba(37, 99, 235, 0.2);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .swap-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        box-shadow: var(--shadow-global);
        transition: all var(--transition-speed);
        flex-shrink: 0;
    }
    .swap-btn:hover {
        background: rgba(37, 99, 235, 0.1);
        color: var(--primary);
        transform: rotate(180deg);
    }

    /* Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .status-increasing { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .status-decreasing { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    .status-stable { background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; }

    /* Bullet List Insight */
    .insight-bullets {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .insight-bullets li {
        position: relative;
        padding-left: 24px;
        margin-bottom: 8px;
        font-size: 0.9rem;
        color: #475569;
    }
    .insight-bullets li::before {
        content: '\F26A'; /* Bootstrap Icon check-circle */
        font-family: 'bootstrap-icons';
        position: absolute;
        left: 0;
        top: 2px;
        color: var(--primary);
        font-size: 0.9rem;
    }

    /* Modal Styling */
    .currency-list {
        max-height: 350px;
        overflow-y: auto;
    }
    .currency-item {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        border-bottom: 1px solid #f1f5f9;
        text-decoration: none;
        color: var(--text-primary);
        transition: background var(--transition-speed);
    }
    .currency-item:hover {
        background: rgba(37, 99, 235, 0.05);
    }
</style>
@endpush

@section('content')
<div class="currency-monitoring-page pt-4 px-2 px-md-0 pb-4 bg-white rounded-4">

    {{-- ===== HEADER REMOVED ===== --}}

    @php
        $bIso = $baseCurrency ? strtolower(substr($baseCurrency->currency_code, 0, 2)) : 'id';
        $bCode = $baseCurrency ? $baseCurrency->currency_code : 'IDR';
        
        $sIso = $selectedCurrency ? strtolower(substr($selectedCurrency->currency_code, 0, 2)) : 'us'; 
        $sCode = $selectedCurrency ? $selectedCurrency->currency_code : 'USD';
        
        $cRate = 0;
        if ($selectedCurrency && $baseCurrency) {
            $tRate = (float)$selectedCurrency->exchange_rate_usd;
            $bRate = (float)$baseCurrency->exchange_rate_usd;
            $cRate = $tRate > 0 ? ($bRate / $tRate) : 0;
        }

        // Determine Status based on dailyChange
        $statusClass = 'status-stable';
        $statusText = 'Stable';
        $statusIcon = 'bi-dash-circle';
        
        if ($dailyChange > 0.1) {
            $statusClass = 'status-increasing';
            $statusText = 'Increasing';
            $statusIcon = 'bi-arrow-up-circle';
        } elseif ($dailyChange < -0.1) {
            $statusClass = 'status-decreasing';
            $statusText = 'Decreasing';
            $statusIcon = 'bi-arrow-down-circle';
        }
    @endphp


    <div class="row g-4">
        {{-- ===== HIERARCHY 2: CURRENCY CONVERTER (COMPACT) ===== --}}
        <div class="col-lg-6">
            <div class="modern-card p-4 h-100 d-flex flex-column justify-content-center">
                <h6 class="fw-bold text-muted mb-3 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                    <i class="bi bi-calculator me-1"></i> {{ __('Quick Converter') }}
                </h6>
                
                <div class="compact-converter">
                    <!-- Base Currency -->
                    <div class="converter-box shadow-sm" data-bs-toggle="modal" data-bs-target="#baseCurrencyModal">
                        <span class="fi fi-{{ $bIso }} rounded-circle border shadow-sm" style="width: 32px; height: 32px; font-size: 32px; display:inline-block;"></span>
                        <div>
                            <span class="d-block small text-muted fw-bold" style="font-size: 0.7rem;">BASE</span>
                            <span class="fw-bold text-dark">1 {{ $bCode }}</span>
                        </div>
                        <i class="bi bi-chevron-down ms-auto text-muted small"></i>
                    </div>

                    <!-- Swap Icon -->
                    <div class="swap-btn">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>

                    <!-- Target Currency -->
                    <div class="converter-box shadow-sm" data-bs-toggle="modal" data-bs-target="#targetCurrencyModal">
                        <span class="fi fi-{{ $sIso }} rounded-circle border shadow-sm" style="width: 32px; height: 32px; font-size: 32px; display:inline-block;"></span>
                        <div>
                            <span class="d-block small text-muted fw-bold" style="font-size: 0.7rem;">TARGET</span>
                            <span class="fw-bold text-dark">1 {{ $sCode }}</span>
                        </div>
                        <i class="bi bi-chevron-down ms-auto text-muted small"></i>
                    </div>
                </div>

                <!-- Result Box -->
                <div class="converter-result mt-3 shadow-sm">
                    <span class="text-primary fw-bold small text-uppercase mb-1">{{ __('Current Rate') }}</span>
                    <h3 class="fw-bold text-dark mb-0" style="letter-spacing: -0.5px;">
                        {{ number_format($cRate, 4, ',', '.') }} <span class="fs-5 text-muted fw-normal">{{ $bCode }}</span>
                    </h3>
                </div>
            </div>
        </div>

        {{-- ===== HIERARCHY 3: AI INSIGHT (COMPACT WITH BULLETS & BADGES) ===== --}}
        <div class="col-lg-6">
            <div class="modern-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h6 class="fw-bold text-muted mb-0 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                        <i class="bi bi-robot me-1"></i> {{ __('AI Insight') }}
                    </h6>
                    <!-- Status Badge -->
                    <div class="status-badge {{ $statusClass }}">
                        <i class="bi {{ $statusIcon }}"></i> {{ $statusText }}
                    </div>
                </div>

                <!-- Compact Summary (Replacing long paragraph) -->
                @if(isset($insight['impacts']) && count($insight['impacts']) > 0)
                    <p class="fw-medium text-dark small mb-3">
                        <i class="bi bi-info-circle text-primary me-1"></i> {!! __($insight['summary'] ?? 'Analisis dampak mata uang.') !!}
                    </p>
                    
                    <div class="bg-light rounded-3 p-3 mb-0 border">
                        <span class="d-block small fw-bold text-muted mb-2">{{ __('Potensi Dampak Logistik:') }}</span>
                        <ul class="insight-bullets">
                            @foreach($insight['impacts'] as $imp)
                            <li>{!! __($imp) !!}</li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border">
                        <i class="bi bi-shield-check text-success fs-3"></i>
                        <div>
                            <span class="d-block fw-bold text-dark small">{{ __('Kondisi Normal') }}</span>
                            <span class="text-muted small">Tidak ada peringatan khusus terkait fluktuasi nilai tukar saat ini.</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- HISTORICAL CHART -->
    <div class="row mt-4 fade-in-up">
        <div class="col-12">
            <div class="modern-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-graph-up-arrow me-2 text-primary"></i> {{ __('Tren Nilai Tukar Historis (7 Hari Terakhir)') }}
                    </h5>
                    <div class="text-muted small">
                        {{ $sCode }} ke {{ $bCode }}
                    </div>
                </div>
                <div class="chart-wrapper">
                    @if(count($historicalData) > 1)
                        <canvas id="trendChart"></canvas>
                    @else
                        <div class="empty-chart-state">
                            <i class="bi bi-graph-down text-muted mb-2" style="font-size: 2rem;"></i>
                            <h6 class="text-dark fw-bold">{{ __('Data Belum Cukup') }}</h6>
                            <p class="text-muted small mb-0">{{ __('Dibutuhkan minimal 2 hari data untuk menampilkan tren.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Target Currency Modal -->
<div class="modal fade" id="targetCurrencyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0 pt-3 px-3">
                <h6 class="modal-title fw-bold">{{ __('Pilih Target') }}</h6>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <input type="text" class="form-control form-control-sm bg-light border-0 rounded-pill mb-2 px-3" id="targetSearch" placeholder="Search...">
                <div class="currency-list" id="targetList">
                    @foreach($currencies as $c)
                        @php $iso = strtolower(substr($c->currency_code, 0, 2)); @endphp
                        <a href="{{ route('user.currency', ['currency' => $c->currency_code, 'base' => $bCode ?? 'IDR']) }}" class="currency-item target-item" data-search="{{ strtolower($c->currency_code) }}">
                            <span class="fi fi-{{ $iso }} rounded-circle border shadow-sm me-3" style="width: 20px; height: 20px; font-size: 20px; display:inline-block;"></span>
                            <span class="fw-medium small">{{ $c->currency_code }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Base Currency Modal -->
<div class="modal fade" id="baseCurrencyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0 pt-3 px-3">
                <h6 class="modal-title fw-bold">{{ __('Pilih Base (Pembanding)') }}</h6>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <input type="text" class="form-control form-control-sm bg-light border-0 rounded-pill mb-2 px-3" id="baseSearch" placeholder="Search...">
                <div class="currency-list" id="baseList">
                    @foreach($currencies as $c)
                        @php $iso = strtolower(substr($c->currency_code, 0, 2)); @endphp
                        <a href="{{ route('user.currency', ['currency' => $sCode ?? 'USD', 'base' => $c->currency_code]) }}" class="currency-item base-item" data-search="{{ strtolower($c->currency_code) }}">
                            <span class="fi fi-{{ $iso }} rounded-circle border shadow-sm me-3" style="width: 20px; height: 20px; font-size: 20px; display:inline-block;"></span>
                            <span class="fw-medium small">{{ $c->currency_code }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal Search Logic for Target
        const tSearch = document.getElementById('targetSearch');
        const tItems = document.querySelectorAll('.target-item');
        if(tSearch) {
            tSearch.addEventListener('keyup', function(e) {
                const term = e.target.value.toLowerCase();
                tItems.forEach(item => {
                    item.style.display = item.getAttribute('data-search').includes(term) ? 'flex' : 'none';
                });
            });
        }
        
        // Modal Search Logic for Base
        const bSearch = document.getElementById('baseSearch');
        const bItems = document.querySelectorAll('.base-item');
        if(bSearch) {
            bSearch.addEventListener('keyup', function(e) {
                const term = e.target.value.toLowerCase();
                bItems.forEach(item => {
                    item.style.display = item.getAttribute('data-search').includes(term) ? 'flex' : 'none';
                });
            });
        }

        // Initialize Chart.js with Modern Aesthetics
        const chartCtx = document.getElementById('trendChart');
        if (chartCtx) {
            const rawData = {!! json_encode($historicalData ?? []) !!};
            if(rawData && rawData.length > 1) {
                const ctx = chartCtx.getContext('2d');
                let gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(14, 165, 233, 0.2)');
                gradient.addColorStop(1, 'rgba(14, 165, 233, 0)');
    
                new Chart(chartCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($historicalLabels ?? []) !!},
                        datasets: [{
                            label: 'Exchange Rate',
                            data: rawData,
                            borderColor: '#0ea5e9',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#0ea5e9',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBorderWidth: 2,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                titleFont: { family: 'Inter', size: 13 },
                                bodyFont: { family: 'Inter', size: 14, weight: 'bold' },
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: false
                            }
                        },
                        scales: {
                            x: { 
                                display: true, 
                                grid: { display: false },
                                ticks: { font: { family: 'Inter' }, color: '#64748b' }
                            },
                            y: { 
                                display: true, 
                                grid: { color: '#f1f5f9', borderDash: [5, 5] },
                                ticks: { font: { family: 'Inter' }, color: '#64748b' }
                            }
                        }
                    }
                });
            }
        }
    });
</script>
@endpush
