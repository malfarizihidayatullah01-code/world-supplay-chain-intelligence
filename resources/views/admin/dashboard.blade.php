@extends('layouts.admin')

@section('content')
<!-- HERO SECTION -->
<div class="row mb-5 fade-in-up">
    <div class="col-xl-12 text-center text-xl-start">
        <p class="fw-bold mb-2" style="color: #0f766e; font-size: 1.1rem; letter-spacing: 0.1em; text-transform: uppercase;">{{ __('Good Morning') }}, {{ Auth::user()->name }}</p>
        <h1 class="mb-3">{{ __('Global Supply Chain Intelligence') }}</h1>
        <p class="text-secondary" style="font-size: 1.1rem; max-width: 600px;">{{ __('Monitor worldwide ports, datasets, reports and operational activities from one unified platform.') }}</p>
    </div>
</div>

<!-- KPI CARDS (3 Columns) -->
<div class="row g-4 mb-4 fade-in-up delay-100 justify-content-center">
    <!-- Card 1: Users -->
    <div class="col-xl-4 col-md-4">
        <div class="saas-card kpi-card-1 h-100 p-4">
            <div class="text-muted fw-bold text-uppercase mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">{{ __('Total Users') }}</div>
            <div class="kpi-value-lg mb-2">{{ number_format($summary['totalUsers'] ?? 0) }}</div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1"><i data-lucide="arrow-up" style="width: 12px;"></i> +{{ $summary['newUsers'] ?? 0 }}</span>
                <span class="text-muted" style="font-size: 0.85rem;">{{ __('new this month') }}</span>
            </div>
            <div class="bg-icon" style="opacity: 0.5;">
                <i data-lucide="users" style="width: 64px; height: 64px;"></i>
            </div>
        </div>
    </div>
    
    <!-- Card 2: Ports -->
    <div class="col-xl-4 col-md-4">
        <div class="saas-card kpi-card-2 h-100 p-4">
            <div class="text-muted fw-bold text-uppercase mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">{{ __('Monitored Ports') }}</div>
            <div class="kpi-value-lg mb-2">{{ number_format($summary['totalPorts'] ?? 0) }}</div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1"><i data-lucide="trending-up" style="width: 12px;"></i> +{{ $summary['newPorts'] ?? 0 }}</span>
                <span class="text-muted" style="font-size: 0.85rem;">{{ __('vs last month') }}</span>
            </div>
            <div class="bg-icon">
                <i data-lucide="anchor" style="width: 64px; height: 64px;"></i>
            </div>
        </div>
    </div>
    
    <!-- Card 3: Reports -->
    <div class="col-xl-4 col-md-4">
        <div class="saas-card kpi-card-3 h-100 p-4">
            <div class="text-muted fw-bold text-uppercase mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">{{ __('Published Reports') }}</div>
            <div class="kpi-value-lg">{{ number_format($summary['publishedArticles'] ?? 0) }}</div>
            <div class="d-flex align-items-center gap-2">
                <span class="text-warning fw-bold" style="font-size: 0.85rem;"><i data-lucide="activity" style="width: 14px;"></i> Stable</span>
            </div>
            <div class="trend-box">
                <div class="trend-bar" style="height: 40%;"></div>
                <div class="trend-bar" style="height: 60%;"></div>
                <div class="trend-bar" style="height: 50%;"></div>
                <div class="trend-bar" style="height: 80%;"></div>
                <div class="trend-bar" style="height: 100%;"></div>
                <div class="trend-bar" style="height: 70%;"></div>
            </div>
        </div>
    </div>
</div>

<!-- SYSTEM STATUS PILL -->
<div class="d-flex justify-content-center mb-5 fade-in-up delay-100">
    <div class="d-inline-flex bg-white px-4 py-3 rounded-pill shadow-sm border border-light align-items-center gap-4">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-success" style="width: 10px; height: 10px;"></div>
            <span class="fw-semibold text-dark" style="font-size: 0.9rem;">{{ __('System Online') }}</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-primary" style="width: 10px; height: 10px;"></div>
            <span class="fw-semibold text-dark" style="font-size: 0.9rem;">{{ __('API Active') }}</span>
        </div>
        <div class="vr" style="opacity: 0.2;"></div>
        <div class="d-flex align-items-center gap-2">
            <i data-lucide="refresh-cw" style="width: 14px; color: #64748B;"></i>
            <span class="fw-semibold text-secondary" style="font-size: 0.9rem;">Sync Status: <span class="text-dark">{{ $summary['lastSync'] ?? 'N/A' }}</span></span>
        </div>
    </div>
</div>

<!-- MAIN LAYOUT 70/30 -->
<div class="row g-5 fade-in-up delay-200">
    
    <!-- LEFT SECTION (70%) -->
    <div class="col-xl-8">
        
        <!-- Dataset Sync Status (HTML/CSS Progress) -->
        <h5 class="mb-4">Dataset Synchronization Status</h5>
        <div class="saas-card mb-5">
            <p class="text-secondary mb-4" style="font-size: 0.9rem;">Distribution of records across monitored datasets</p>
            
            @php
                $colors = ['#2962FF', '#22C55E', '#F59E0B', '#EF4444', '#8B5CF6'];
                $i = 0;
                $maxRecords = 0;
                foreach($summary['datasets'] ?? [] as $ds) {
                    if($ds->records > $maxRecords) $maxRecords = $ds->records;
                }
            @endphp
            
            @foreach($summary['datasets'] ?? [] as $ds)
            @php
                $color = $colors[$i % count($colors)];
                $percentage = $maxRecords > 0 ? ($ds->records / $maxRecords) * 100 : 0;
                $i++;
            @endphp
            <div class="dataset-progress-wrapper">
                <div class="dataset-progress-header">
                    <span class="text-dark">{{ $ds->name }}</span>
                    <span class="text-secondary">{{ number_format($ds->records) }} records</span>
                </div>
                <div class="dataset-progress-track">
                    <div class="dataset-progress-fill" style="width: {{ $percentage }}%; background-color: {{ $color }};"></div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Latest Users -->
        <h5 class="mb-4">Latest Users</h5>
        <div class="saas-card p-0 mb-5 border-0 bg-transparent shadow-none">
            @forelse($summary['recentUsers'] ?? [] as $user)
            <div class="saas-card modern-list-item px-4 py-3 mb-3 d-flex align-items-center justify-content-between flex-wrap gap-3" style="border-radius: 16px;">
                <div class="d-flex align-items-center gap-3" style="width: 250px;">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=F8FAFC&color=102A43" class="rounded-circle" width="46" height="46">
                    <div>
                        <h6 class="mb-1 text-dark">{{ $user->name }}</h6>
                        <div class="text-secondary" style="font-size: 0.85rem;">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="text-muted d-none d-sm-block flex-grow-1 text-center" style="font-size: 0.85rem;">
                    {{ $user->created_at->format('d M Y') }}
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-{{ $user->role == 'admin' ? 'primary' : 'success' }} bg-opacity-10 text-{{ $user->role == 'admin' ? 'primary' : 'success' }} px-3 py-1 rounded-pill text-capitalize">{{ $user->role }}</span>
                    <button class="btn btn-sm btn-light rounded-circle text-muted" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;"><i data-lucide="more-horizontal" style="width: 16px;"></i></button>
                </div>
            </div>
            @empty
            <div class="saas-card text-center p-4 text-muted">No recent users.</div>
            @endforelse
        </div>
        
        <!-- Recent Reports -->
        <h5 class="mb-4">Recent Reports</h5>
        <div class="saas-card p-0 mb-5 border-0 bg-transparent shadow-none">
            @forelse($summary['recentArticles'] ?? [] as $article)
            <div class="saas-card modern-list-item px-4 py-3 mb-3 d-flex align-items-center justify-content-between flex-wrap gap-3" style="border-radius: 16px;">
                <div class="d-flex align-items-center gap-4" style="min-width: 0;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px; background: rgba(139, 92, 246, 0.1); color: var(--purple);">
                        <i data-lucide="file-text"></i>
                    </div>
                    <div style="min-width: 0;">
                        <h6 class="mb-1 text-dark text-truncate" style="max-width: 400px;">{{ $article->title }}</h6>
                        <div class="text-secondary" style="font-size: 0.85rem;">Published on {{ $article->created_at->format('d M Y') }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    @if($article->is_published)
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill">Published</span>
                    @else
                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-1 rounded-pill">Draft</span>
                    @endif
                    <button class="btn btn-sm btn-light rounded-circle text-muted" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;"><i data-lucide="more-horizontal" style="width: 16px;"></i></button>
                </div>
            </div>
            @empty
            <div class="saas-card text-center p-4 text-muted">No recent reports.</div>
            @endforelse
        </div>
        
    </div>
    
    <!-- RIGHT SECTION (30%) -->
    <div class="col-xl-4">
        
        <!-- Dataset Distribution -->
        <h5 class="mb-4">Dataset Distribution</h5>
        <div class="saas-card mb-5">
            <p class="text-secondary mb-3" style="font-size: 0.9rem;">Proportion by source</p>
            <div class="chart-container" style="height: 320px;">
                <canvas id="datasetDoughnutChart"></canvas>
            </div>
        </div>
        
        <!-- Storage Usage -->
        <h5 class="mb-4">Storage Capacity</h5>
        <div class="saas-card mb-5 d-flex flex-column align-items-center justify-content-center py-5">
            <div style="position: relative; width: 140px; height: 140px; border-radius: 50%; background: conic-gradient(var(--warning) 82%, #F1F5F9 0);">
                <div style="position: absolute; top: 12px; left: 12px; right: 12px; bottom: 12px; background: #FFFFFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                    <span style="font-size: 2rem; font-weight: 800; color: var(--text-dark); line-height: 1;">82%</span>
                    <span class="text-secondary" style="font-size: 0.75rem; font-weight: 600;">USED</span>
                </div>
            </div>
            <p class="text-secondary text-center mt-4 mb-0" style="font-size: 0.9rem;">System storage is approaching limits. Consider archiving old reports.</p>
        </div>
        

        <!-- Activity Timeline -->
        <h5 class="mb-4">Recent Activity</h5>
        <div class="saas-card p-4 pb-2">
            <ul class="timeline-aether">
                @forelse($summary['recentUsers'] ?? [] as $user)
                <li class="timeline-aether-item">
                    <div class="timeline-aether-marker"><i data-lucide="user" style="color: var(--primary);"></i></div>
                    <div class="timeline-aether-content">
                        <div class="timeline-aether-title">User Created</div>
                        <div class="timeline-aether-time">{{ $user->created_at->diffForHumans() }}</div>
                    </div>
                </li>
                @empty
                @endforelse

                @forelse($summary['recentArticles'] ?? [] as $article)
                <li class="timeline-aether-item">
                    <div class="timeline-aether-marker"><i data-lucide="file-text" style="color: var(--success);"></i></div>
                    <div class="timeline-aether-content">
                        <div class="timeline-aether-title">Report Published</div>
                        <div class="timeline-aether-time">{{ $article->created_at->diffForHumans() }}</div>
                    </div>
                </li>
                @empty
                @endforelse
                
                @if(empty($summary['recentUsers']) && empty($summary['recentArticles']))
                <li class="timeline-aether-item">
                    <div class="timeline-aether-marker"><i data-lucide="clock" style="color: var(--text-muted);"></i></div>
                    <div class="timeline-aether-content">
                        <div class="timeline-aether-title text-muted">No Activity</div>
                        <div class="timeline-aether-time">System is standing by.</div>
                    </div>
                </li>
                @endif
            </ul>
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const datasetsData = @json($summary['datasets'] ?? []);
    
    if (datasetsData && datasetsData.length > 0) {
        const labels = datasetsData.map(d => d.name);
        const data = datasetsData.map(d => d.records);
        const bgColors = ['#2962FF', '#22C55E', '#F59E0B', '#EF4444', '#8B5CF6'];
        
        // Doughnut Chart
        const doughnutCtx = document.getElementById('datasetDoughnutChart');
        if (doughnutCtx) {
            new Chart(doughnutCtx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: bgColors,
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: { family: 'Plus Jakarta Sans', size: 12 }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#0F172A',
                            padding: 12,
                            cornerRadius: 12,
                            titleFont: { family: 'Plus Jakarta Sans', size: 13 },
                            bodyFont: { family: 'Plus Jakarta Sans', size: 14, weight: 'bold' }
                        }
                    }
                }
            });
        }
    }
});
</script>
@endpush
