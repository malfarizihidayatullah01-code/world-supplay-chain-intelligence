@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Dashboard Synchronization</h2>
    </div>

    <!-- Last Synchronization Card -->
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px;">
        <div class="card-header bg-white border-bottom p-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
            <h5 class="m-0 fw-bold"><i class="bi bi-clock-history text-primary"></i> Last Synchronization</h5>
        </div>
        <div class="card-body">
            <div class="row text-center g-3">
                <div class="col-md-3 border-end">
                    <p class="text-muted mb-1 small">Countries Sync</p>
                    <h6 class="fw-bold mb-0">{{ $last_syncs['countries'] ? \Carbon\Carbon::parse($last_syncs['countries'])->diffForHumans() : 'Never' }}</h6>
                </div>
                <div class="col-md-3 border-end">
                    <p class="text-muted mb-1 small">Weather Sync</p>
                    <h6 class="fw-bold mb-0">{{ $last_syncs['weather'] ? \Carbon\Carbon::parse($last_syncs['weather'])->diffForHumans() : 'Never' }}</h6>
                </div>
                <div class="col-md-3 border-end">
                    <p class="text-muted mb-1 small">Exchange Rate Sync</p>
                    <h6 class="fw-bold mb-0">{{ $last_syncs['exchange_rates'] ? \Carbon\Carbon::parse($last_syncs['exchange_rates'])->diffForHumans() : 'Never' }}</h6>
                </div>
                <div class="col-md-3">
                    <p class="text-muted mb-1 small">News Sync</p>
                    <h6 class="fw-bold mb-0">{{ $last_syncs['news'] ? \Carbon\Carbon::parse($last_syncs['news'])->diffForHumans() : 'Never' }}</h6>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistic Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-2">
            <div class="card shadow-sm border-0 bg-primary text-white text-center" style="border-radius: 16px;">
                <div class="card-body py-4">
                    <h2 class="fw-bold mb-0">{{ number_format($counts['countries']) }}</h2>
                    <p class="mb-0 small opacity-75">Countries</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm border-0 bg-secondary text-white text-center" style="border-radius: 16px;">
                <div class="card-body py-4">
                    <h2 class="fw-bold mb-0">{{ number_format($counts['ports']) }}</h2>
                    <p class="mb-0 small opacity-75">Ports</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm border-0 bg-info text-white text-center" style="border-radius: 16px;">
                <div class="card-body py-4">
                    <h2 class="fw-bold mb-0">{{ number_format($counts['shipping_routes']) }}</h2>
                    <p class="mb-0 small opacity-75">Shipping Routes</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm border-0 bg-warning text-dark text-center" style="border-radius: 16px;">
                <div class="card-body py-4">
                    <h2 class="fw-bold mb-0">{{ number_format($counts['weather_data']) }}</h2>
                    <p class="mb-0 small opacity-75">Weather Data</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm border-0 bg-success text-white text-center" style="border-radius: 16px;">
                <div class="card-body py-4">
                    <h2 class="fw-bold mb-0">{{ number_format($counts['exchange_rates']) }}</h2>
                    <p class="mb-0 small opacity-75">Exchange Rates</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm border-0 bg-danger text-white text-center" style="border-radius: 16px;">
                <div class="card-body py-4">
                    <h2 class="fw-bold mb-0">{{ number_format($counts['news_articles']) }}</h2>
                    <p class="mb-0 small opacity-75">News Articles</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Data Tables -->
    <div class="row g-4">
        
        <!-- Recent Weather -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <h6 class="m-0 fw-bold"><i class="bi bi-cloud-sun text-warning"></i> Recent Weather Data</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Country</th>
                                    <th>Temp</th>
                                    <th class="pe-3">Condition</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_data['weather'] as $weather)
                                <tr>
                                    <td class="ps-3">{{ $weather->country->country_name ?? '-' }}</td>
                                    <td>{{ $weather->temperature }}&deg;C</td>
                                    <td class="pe-3"><small class="text-muted">{{ $weather->weather_condition }}</small></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3 text-muted">No recent weather data.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Exchange Rates -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <h6 class="m-0 fw-bold"><i class="bi bi-currency-exchange text-success"></i> Recent Exchange Rates</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Country</th>
                                    <th>Currency</th>
                                    <th class="pe-3">Rate (USD)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_data['exchange_rates'] as $rate)
                                <tr>
                                    <td class="ps-3">{{ $rate->country->country_name ?? '-' }}</td>
                                    <td>{{ $rate->target_currency }}</td>
                                    <td class="pe-3">{{ number_format($rate->exchange_rate, 4) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3 text-muted">No recent exchange rates.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent News -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <h6 class="m-0 fw-bold"><i class="bi bi-newspaper text-danger"></i> Recent News Articles</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Country</th>
                                    <th class="pe-3">Headline</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_data['news'] as $news)
                                <tr>
                                    <td class="ps-3" style="width: 30%">{{ $news->country->country_name ?? '-' }}</td>
                                    <td class="pe-3">
                                        <a href="{{ $news->url }}" target="_blank" class="text-decoration-none text-dark small text-truncate d-block" style="max-width: 180px;" title="{{ $news->title }}">
                                            {{ $news->title }}
                                        </a>
                                        <small class="text-muted" style="font-size: 0.7rem;">{{ $news->source }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center py-3 text-muted">No recent news articles.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
