@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Exchange Rates</h2>
        <form action="{{ route('currency.sync') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-repeat"></i> Sync Currency API</button>
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
                            <th>Currency</th>
                            <th>Exchange Rate (USD)</th>
                            <th>Last Sync</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exchangeRates as $index => $rate)
                        <tr>
                            <td class="ps-4">{{ $exchangeRates->firstItem() + $index }}</td>
                            <td class="fw-bold">{{ $rate->country->country_name ?? '-' }}</td>
                            <td><span class="badge bg-secondary">{{ $rate->target_currency }}</span></td>
                            <td>{{ number_format($rate->exchange_rate, 4) }}</td>
                            <td><small class="text-muted">{{ $rate->updated_at->diffForHumans() }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No exchange rates found. Please sync the API.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white border-top p-4" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
            {{ $exchangeRates->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
