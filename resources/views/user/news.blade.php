@extends('layouts.app')

@section('title', __('News Intelligence'))

@section('content')
<div class="container-fluid pb-5 bg-white rounded-4" style="min-height: 100vh; padding-top: 20px;">
    <!-- Header Section -->
    <div class="header-hero-section position-relative overflow-hidden mb-4" style="background: linear-gradient(135deg, var(--primary) 0%, #3B82F6 100%); border-radius: var(--radius-card); box-shadow: var(--shadow-global); padding: 40px 48px;">
        <!-- Transparent Pattern Decor -->
        <div class="position-absolute top-0 end-0 h-100" style="width: 40%; background-image: radial-gradient(#ffffff 1.5px, transparent 1.5px); background-size: 20px 20px; opacity: 0.1; pointer-events: none;"></div>

        <div class="row align-items-center position-relative z-1">
            <!-- Kiri (70%) -->
            <div class="col-lg-8 mb-4 mb-lg-0">
                <h2 class="fw-bolder mb-2 text-white" style="letter-spacing: -0.5px;">{{ __('Global News Intelligence') }}</h2>
                <p class="text-white-50 mb-3 fs-6" style="max-width: 600px;">{{ __('Monitoring center for the latest news in logistics, trade, and global maritime sectors.') }}</p>
                <span class="badge rounded-pill fw-semibold bg-white text-primary px-3 py-2" style="font-size: 0.8rem;">
                    🌐 {{ __('Global Monitoring') }}
                </span>
            </div>
            
            <!-- Kanan (30%) -->
            <div class="col-lg-4 d-flex justify-content-lg-end">
                <div class="d-flex align-items-center gap-4 bg-white p-3 rounded-4 shadow-sm border" style="border-color: var(--border-color) !important; border-radius: var(--radius-card) !important;">
                    <div>
                        <span class="d-block text-muted fw-semibold mb-1" style="font-size: 0.8rem;">{{ __('Total News') }}</span>
                        <h2 class="fw-bold text-primary mb-2 lh-1" style="font-size: 38px;" id="totalNewsCount">{{ $categoryStats['total'] }}</h2>
                        <div class="d-flex align-items-center gap-1">
                            <span class="text-success" style="font-size: 0.5rem;">●</span>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ __('Last updated: 2 minutes ago') }}</small>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-sync shadow-sm rounded-pill px-4 py-2 fw-bold d-flex align-items-center justify-content-center gap-2" id="syncNewsBtn">
                        <i class="bi bi-arrow-repeat fs-5"></i>
                        <span>{{ __('Sync News') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="px-4 mb-4">
        <form id="newsFilterForm" class="bg-white p-3 shadow-sm border" style="border-color: var(--border-color) !important; border-radius: var(--radius-card); box-shadow: var(--shadow-global) !important;">
            <div class="row g-3 align-items-center">
                <!-- Search -->
                <div class="col-12 col-md-4 col-lg-5">
                    <div class="input-group input-group-lg bg-light rounded-pill px-2 border" style="border-color: #F3F4F6 !important;">
                        <span class="input-group-text bg-transparent border-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" id="searchInput" class="form-control bg-transparent border-0 fs-6 shadow-none" placeholder="{{ __('Search news keywords...') }}" value="{{ $search }}">
                    </div>
                </div>
                <!-- Category -->
                <div class="col-12 col-md-3 col-lg-3">
                    <select name="category" id="categorySelect" class="form-select form-select-lg bg-white border fs-6 shadow-none text-muted" style="border-radius: 12px; border-color: #E5E7EB !important;">
                        <option value="All Categories" {{ $category == 'All Categories' ? 'selected' : '' }}>{{ __('All Categories') }}</option>
                        <option value="Logistics" {{ $category == 'Logistics' ? 'selected' : '' }}>{{ __('Logistics') }}</option>
                        <option value="Trade" {{ $category == 'Trade' ? 'selected' : '' }}>{{ __('Trade') }}</option>
                        <option value="Shipping" {{ $category == 'Shipping' ? 'selected' : '' }}>{{ __('Shipping') }}</option>
                        <option value="Economy" {{ $category == 'Economy' ? 'selected' : '' }}>{{ __('Economy') }}</option>
                    </select>
                </div>
                <!-- Country -->
                <div class="col-12 col-md-3 col-lg-2">
                    <select name="country" id="countrySelect" class="form-select form-select-lg bg-white border fs-6 shadow-none text-muted" style="border-radius: 12px; border-color: #E5E7EB !important;">
                        <option value="All Countries" {{ $countryId == 'All Countries' ? 'selected' : '' }}>{{ __('All Countries') }}</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->id }}" {{ $countryId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Reset -->
                <div class="col-12 col-md-2 col-lg-2">
                    <button type="button" class="btn bg-light border fw-semibold text-muted w-100 py-2 fs-6" id="resetFilterBtn" style="border-radius: 12px; border-color: #E5E7EB !important;">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> {{ __('Reset') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Main Content Layout -->
    <div class="row g-4 px-4">
        
        <!-- Left Content: News Feed -->
        <div class="col-xl-9 col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-rss text-primary"></i> {{ __('News Feed') }}
                </h5>
                <span class="text-muted small fw-medium" id="loadingIndicator" style="display: none;">
                    <i class="bi bi-arrow-repeat bi-spin me-1"></i> {{ __('Loading News...') }}
                </span>
            </div>
            
            <div id="newsListContainer">
                @include('user.partials.news_list')
            </div>
        </div>

        <!-- Right Sidebar: Summary Panel -->
        <div class="col-xl-3 col-lg-4">
            <div class="sticky-top" style="top: 24px;">
                <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                    <i class="bi bi-bar-chart-steps text-primary"></i> {{ __('Data Summary') }}
                </h5>

                <!-- AI Insight Card -->
                <div class="card bg-white border-0 shadow-sm rounded-4 mb-4" style="border: 1px solid var(--border-color) !important; border-radius: var(--radius-card) !important; box-shadow: var(--shadow-global) !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom" style="border-bottom-color: #F3F4F6 !important;">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="bi bi-lightning-charge-fill fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">{{ __('AI Market Insight') }}</h6>
                                <small class="text-muted">{{ __('Analisis Sentimen Global') }}</small>
                            </div>
                        </div>
                        <p class="mb-4 text-secondary fw-medium lh-base" style="font-size: 0.85rem;" id="ai-insight-msg">{{ $marketInsight['summary'] ?? 'Kondisi pasar saat ini relatif stabil.' }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded-3 border" style="border-color: #F3F4F6 !important;">
                            <span class="small fw-semibold text-muted d-flex align-items-center gap-2"><i class="bi bi-activity"></i> {{ __('Dominasi Sentimen') }}</span>
                            @php
                                $sentOverall = $sentimentStats['overall_sentiment'] ?? 'Neutral';
                                $sentBadge = 'bg-secondary text-secondary';
                                if($sentOverall === 'Positive') $sentBadge = 'bg-success text-success';
                                if($sentOverall === 'Negative') $sentBadge = 'bg-danger text-danger';
                            @endphp
                            <span class="badge {{ $sentBadge }} bg-opacity-10 rounded-pill px-3 py-1 fw-bold border" style="font-size: 0.75rem;" id="ai-overall-sent">
                                {{ __($sentOverall) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Top Categories -->
                <div class="card bg-white border-0 shadow-sm rounded-4" style="border: 1px solid var(--border-color) !important; border-radius: var(--radius-card) !important; box-shadow: var(--shadow-global) !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <i class="bi bi-pie-chart text-muted"></i>
                            <h6 class="fw-bold text-dark mb-0">{{ __('Category Distribution') }}</h6>
                        </div>
                        
                        <!-- Hidden chart just to keep updateCharts working without errors -->
                        <canvas id="sentimentChart" style="display:none;"></canvas>

                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center pb-3 border-bottom" style="border-bottom-color: #F3F4F6 !important;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;"><i class="bi bi-globe fs-6"></i></div>
                                    <span class="fw-semibold text-dark small">{{ __('Trade') }}</span>
                                </div>
                                <span class="fw-bold fs-6 text-dark" id="tradeNewsCount">{{ $categoryStats['categories']['Trade']['count'] }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center pb-3 border-bottom" style="border-bottom-color: #F3F4F6 !important;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background-color: rgba(67, 56, 202, 0.1); color: #4338CA;"><i class="bi bi-box-seam fs-6"></i></div>
                                    <span class="fw-semibold text-dark small">{{ __('Shipping') }}</span>
                                </div>
                                <span class="fw-bold fs-6 text-dark" id="shippingNewsCount">{{ $categoryStats['categories']['Shipping']['count'] }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center pb-3 border-bottom" style="border-bottom-color: #F3F4F6 !important;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;"><i class="bi bi-truck fs-6"></i></div>
                                    <span class="fw-semibold text-dark small">{{ __('Logistics') }}</span>
                                </div>
                                <span class="fw-bold fs-6 text-dark" id="logisticsNewsCount">{{ $categoryStats['categories']['Logistics']['count'] }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pb-1">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;"><i class="bi bi-graph-up-arrow fs-6"></i></div>
                                    <span class="fw-semibold text-dark small">{{ __('Economy') }}</span>
                                </div>
                                <span class="fw-bold fs-6 text-dark" id="economyNewsCount">{{ $categoryStats['categories']['Economy']['count'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let sentimentChartObj = null; // Kept to prevent errors, though chart is hidden

document.addEventListener("DOMContentLoaded", function() {
    initCharts(@json($sentimentStats));

    // Filter Trigger
    const formElements = document.querySelectorAll('#newsFilterForm input, #newsFilterForm select');
    formElements.forEach(el => {
        el.addEventListener('change', fetchNews);
    });
    
    // Search input needs debounce
    let typingTimer;
    document.getElementById('searchInput').addEventListener('keyup', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(fetchNews, 500);
    });

    // Reset Button
    document.getElementById('resetFilterBtn').addEventListener('click', function() {
        document.getElementById('searchInput').value = '';
        document.getElementById('categorySelect').value = 'All Categories';
        document.getElementById('countrySelect').value = 'All Countries';
        fetchNews();
    });

    // Sync Button
    document.getElementById('syncNewsBtn').addEventListener('click', function() {
        const btn = this;
        const icon = btn.querySelector('i');
        btn.disabled = true;
        icon.classList.add('bi-spin');
        
        fetch('{{ route("user.news.sync") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            updateDashboard(data);
            btn.disabled = false;
            icon.classList.remove('bi-spin');
        })
        .catch(err => {
            console.error(err);
            btn.disabled = false;
            icon.classList.remove('bi-spin');
        });
    });
});

function fetchNews() {
    document.getElementById('loadingIndicator').style.display = 'inline-block';
    const form = document.getElementById('newsFilterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData).toString();

    fetch('{{ route("user.news") }}?' + params, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        updateDashboard(data);
        document.getElementById('loadingIndicator').style.display = 'none';
    });
}

function updateDashboard(data) {
    if(data.news_html) {
        document.getElementById('newsListContainer').innerHTML = data.news_html;
    }
    
    if(data.category_stats) {
        if(document.getElementById('totalNewsCount')) document.getElementById('totalNewsCount').innerText = data.category_stats.total;
        if(document.getElementById('shippingNewsCount')) document.getElementById('shippingNewsCount').innerText = data.category_stats.categories.Shipping.count;
        if(document.getElementById('tradeNewsCount')) document.getElementById('tradeNewsCount').innerText = data.category_stats.categories.Trade.count;
        if(document.getElementById('economyNewsCount')) document.getElementById('economyNewsCount').innerText = data.category_stats.categories.Economy.count;
        if(document.getElementById('logisticsNewsCount')) document.getElementById('logisticsNewsCount').innerText = data.category_stats.categories.Logistics.count;
    }

    if(data.sentiment_stats) {
        updateCharts(data.sentiment_stats);
        const overall = data.sentiment_stats.overall_sentiment;
        const badge = document.getElementById('ai-overall-sent');
        if(badge) {
            badge.innerText = overall;
            badge.className = 'badge rounded-pill px-3 py-2';
            if(overall === 'Positive') badge.classList.add('bg-success', 'bg-opacity-10', 'text-success');
            else if(overall === 'Negative') badge.classList.add('bg-danger', 'bg-opacity-10', 'text-danger');
            else badge.classList.add('bg-secondary', 'bg-opacity-10', 'text-secondary');
        }
    }
    
    if(data.market_insight) {
        if(document.getElementById('ai-insight-msg')) document.getElementById('ai-insight-msg').innerText = data.market_insight.summary;
    }
}

function initCharts(sentStats) {
    const ctx2 = document.getElementById('sentimentChart');
    if(ctx2) {
        sentimentChartObj = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Positive', 'Neutral', 'Negative'],
                datasets: [{
                    data: [sentStats.positive_pct, sentStats.neutral_pct, sentStats.negative_pct],
                    backgroundColor: ['#198754', '#ffc107', '#dc3545'],
                    borderWidth: 0,
                    cutout: '75%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
}

function updateCharts(sentStats) {
    if(sentimentChartObj) {
        sentimentChartObj.data.datasets[0].data = [sentStats.positive_pct, sentStats.neutral_pct, sentStats.negative_pct];
        sentimentChartObj.update();
    }
}
</script>
<style>
.bi-spin { animation: spin 1s linear infinite; }
@keyframes spin { 100% { transform: rotate(360deg); } }

.btn-sync {
    background-color: var(--primary) !important;
    border-color: var(--primary) !important;
    color: #ffffff !important;
    opacity: 1 !important;
    transition: all var(--transition-speed) cubic-bezier(0.25, 0.8, 0.25, 1);
}
.btn-sync:hover {
    background-color: var(--primary-hover) !important;
    border-color: var(--primary-hover) !important;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(37,99,235,0.2) !important;
}
</style>
@endpush