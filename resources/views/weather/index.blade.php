@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Weather Data</h2>
        <form action="{{ route('weather.sync') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-repeat"></i> Sync Weather API</button>
        </form>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Country</th>
                            <th>Temperature (&deg;C)</th>
                            <th>Humidity (%)</th>
                            <th>Wind Speed (kph)</th>
                            <th>Condition</th>
                            <th>Last Sync</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($weatherData as $index => $weather)
                        <tr>
                            <td class="ps-4">{{ $weatherData->firstItem() + $index }}</td>
                            <td class="fw-bold">{{ $weather->country->country_name ?? '-' }}</td>
                            <td>{{ $weather->temperature }}</td>
                            <td>{{ $weather->humidity }}</td>
                            <td>{{ $weather->wind_speed }}</td>
                            <td>
                                <span class="badge bg-info-subtle text-info">{{ $weather->weather_condition }}</span>
                            </td>
                            <td><small class="text-muted">{{ $weather->updated_at->diffForHumans() }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No weather data found. Please sync the API.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white border-top p-4" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
            {{ $weatherData->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
