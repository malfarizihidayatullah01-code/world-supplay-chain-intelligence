@extends('layouts.app')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    .fav-page {
        font-family: 'Inter', sans-serif;
        padding-top: 12px;
    }

    /* ── Header ── */
    .fav-header {
        background: linear-gradient(135deg, #EFF6FF 0%, #F8FAFC 100%);
        border-radius: 20px;
        border: 1px solid #E5E7EB;
        padding: 28px 32px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }

    .fav-header-title h1 {
        font-size: 1.35rem;
        font-weight: 700;
        color: #0F172A;
        margin: 0 0 4px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .fav-header-title h1 .icon-fav {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #6366F1, #4F46E5);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: #FFFFFF;
        box-shadow: 0 3px 8px rgba(79, 70, 229, 0.25);
    }

    .fav-header-title p {
        font-size: 0.85rem;
        color: #6B7280;
        margin: 0;
    }

    /* ── Search ── */
    .fav-search-wrap {
        position: relative;
        min-width: 260px;
    }

    .fav-search-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9CA3AF;
        font-size: 0.9rem;
    }

    .fav-search-wrap input {
        width: 100%;
        border: 1.5px solid #E5E7EB;
        border-radius: 12px;
        padding: 10px 14px 10px 38px;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        color: #374151;
        background: #F9FAFB;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .fav-search-wrap input:focus {
        border-color: #3B82F6;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }

    /* ── Table Header ── */
    .fav-table-header {
        background: #F8FAFC;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        display: grid;
        grid-template-columns: 40px 2fr 1.2fr 1.2fr 100px 100px 120px;
        align-items: center;
        padding: 12px 24px;
        margin-bottom: 12px;
        gap: 12px;
    }

    .fav-table-header span {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #9CA3AF;
    }

    /* ── Row ── */
    .fav-row {
        background: #fff;
        border: 1px solid #E5E7EB;
        border-radius: 16px;
        margin-bottom: 8px;
        display: grid;
        grid-template-columns: 40px 2fr 1.2fr 1.2fr 100px 100px 120px;
        align-items: center;
        padding: 14px 24px;
        gap: 12px;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
        cursor: default;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }
    
    .fav-row:nth-child(even) {
        background: #FAFAFA;
    }

    .fav-row:hover {
        background: #F0F7FF;
        border-color: #BFDBFE;
        border-left-color: #3B82F6;
        box-shadow: 0 6px 16px rgba(59,130,246,0.08);
        transform: translateY(-1px);
    }

    /* Flag */
    .fav-flag {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #F3F4F6;
        flex-shrink: 0;
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }

    .fav-flag .fi {
        font-size: 2.4rem;
    }

    /* Country Name */
    .fav-country-name {
        font-weight: 600;
        font-size: 0.92rem;
        color: #111827;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .fav-country-id {
        font-size: 0.72rem;
        color: #9CA3AF;
        margin-top: 2px;
    }

    /* Region */
    .fav-region {
        font-size: 0.825rem;
        color: #6B7280;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Risk badge */
    .risk-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .risk-high   { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }
    .risk-medium { background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; }
    .risk-low    { background: #F0FDF4; color: #16A34A; border: 1px solid #BBF7D0; }
    .risk-na     { background: #F9FAFB; color: #6B7280; border: 1px solid #E5E7EB; }

    /* Temp */
    .fav-temp {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Currency */
    .fav-currency {
        font-size: 0.8rem;
        font-weight: 600;
        color: #4B5563;
        background: #F3F4F6;
        border-radius: 8px;
        padding: 4px 10px;
        display: inline-block;
    }

    /* Action buttons */
    .fav-actions {
        display: flex;
        align-items: center;
        gap: 6px;
        justify-content: flex-end;
    }

    .btn-fav-detail {
        font-size: 0.78rem;
        font-weight: 600;
        color: #3B82F6;
        border: 1.5px solid #BFDBFE;
        background: transparent;
        border-radius: 10px;
        padding: 6px 14px;
        text-decoration: none;
        transition: background 0.15s, color 0.15s;
        white-space: nowrap;
    }

    .btn-fav-detail:hover {
        background: #3B82F6;
        color: #fff;
        border-color: #3B82F6;
    }

    .btn-fav-remove {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        border: 1.5px solid #FEE2E2;
        background: transparent;
        color: #EF4444;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        cursor: pointer;
        transition: background 0.15s, color 0.15s;
        flex-shrink: 0;
    }

    .btn-fav-remove:hover {
        background: #FEF2F2;
        border-color: #EF4444;
    }

    /* Empty State */
    .fav-empty {
        background: #fff;
        border-radius: 20px;
        border: 1px dashed #D1D5DB;
        padding: 80px 40px;
        text-align: center;
    }

    .fav-empty .empty-icon {
        width: 72px;
        height: 72px;
        background: linear-gradient(135deg, #EEF2FF, #E0E7FF);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #4F46E5;
        margin: 0 auto 20px;
    }

    .fav-empty h5 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 8px;
    }

    .fav-empty p {
        font-size: 0.875rem;
        color: #6B7280;
        margin-bottom: 24px;
    }

    /* Count badge */
    .count-pill {
        background: #EFF6FF;
        color: #2563EB;
        border: 1px solid #BFDBFE;
        border-radius: 999px;
        padding: 3px 12px;
        font-size: 0.78rem;
        font-weight: 600;
    }

    @media (max-width: 992px) {
        .fav-table-header { display: none; }
        .fav-row {
            grid-template-columns: 40px 1fr;
            grid-template-rows: auto;
        }
    }

    /* no-results */
    .fav-no-results {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #E5E7EB;
        padding: 40px;
        text-align: center;
        display: none;
    }
</style>
@endpush

@section('title', __('Favorites Monitoring'))

@section('content')
<div class="fav-page">
<div class="container-fluid px-4">

    {{-- ── Header ── --}}
    <div class="fav-header">
        <div class="fav-header-title">
            <h1>
                <span class="icon-fav"><i class="bi bi-stars"></i></span>
                {{ __('Favorites Monitoring') }}
                @if(!$countries->isEmpty())
                <span class="count-pill ms-1">{{ $countries->count() }} {{ __('countries') }}</span>
                @endif
            </h1>
            <p>{{ __('Track and monitor your favorited countries in real-time.') }}</p>
        </div>
        @if(!$countries->isEmpty())
        <div class="fav-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" id="favSearchInput" placeholder="{{ __('Search country...') }}" autocomplete="off">
        </div>
        @endif
    </div>

    @if($countries->isEmpty())
        {{-- ── Empty State ── --}}
        <div class="fav-empty">
            <div class="empty-icon"><i class="bi bi-stars"></i></div>
            <h5>{{ __('No Favorites Yet') }}</h5>
            <p>{{ __("You haven't added any countries to your favorites list yet.") }}</p>
            <a href="{{ route('user.countries.index') }}" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="bi bi-globe me-2"></i>{{ __('Explore Countries') }}
            </a>
        </div>
    @else
        {{-- ── Table Header ── --}}
        <div class="fav-table-header">
            <span></span>
            <span>{{ __('Country') }}</span>
            <span>{{ __('Region') }}</span>
            <span>{{ __('Risk Level') }}</span>
            <span>{{ __('Temperature') }}</span>
            <span>{{ __('Currency') }}</span>
            <span></span>
        </div>

        {{-- ── List ── --}}
        <div id="favList">
            @foreach($countries as $country)
            @php
                $riskLevel = $country->riskData['level'] ?? 'N/A';
                $isHigh    = str_contains($riskLevel, 'High');
                $isMedium  = str_contains($riskLevel, 'Medium');
                $isLow     = str_contains($riskLevel, 'Low');
                $riskClass = $isHigh ? 'risk-high' : ($isMedium ? 'risk-medium' : ($isLow ? 'risk-low' : 'risk-na'));
                $riskIcon  = $isHigh ? 'bi-exclamation-triangle-fill' : ($isMedium ? 'bi-dash-circle-fill' : ($isLow ? 'bi-check-circle-fill' : 'bi-question-circle'));
                $weather   = $country->weatherCaches->first();
                $temp      = $weather ? round($weather->temperature) . '°C' : '—';
                $tempIcon  = $weather ? (str_contains(strtolower($weather->condition ?? ''), 'rain') ? 'bi-cloud-rain-fill text-info' : (str_contains(strtolower($weather->condition ?? ''), 'cloud') ? 'bi-cloud-fill text-secondary' : 'bi-sun-fill text-warning')) : 'bi-thermometer';
            @endphp
            <div class="fav-row" data-name="{{ strtolower($country->name) }}" data-id="{{ $country->id }}">

                {{-- Flag --}}
                <div class="fav-flag">
                    <span class="fi fi-{{ strtolower($country->iso2_code ?? 'un') }}"></span>
                </div>

                {{-- Country Name --}}
                <div>
                    <div class="fav-country-name">{{ $country->name }}</div>
                    <div class="fav-country-id">{{ $country->iso2_code }}</div>
                </div>

                {{-- Region --}}
                <div class="fav-region">
                    <i class="bi bi-geo-alt" style="color:#9CA3AF;font-size:0.78rem;"></i>
                    {{ __($country->region ?? 'Unknown') }}
                </div>

                {{-- Risk Badge --}}
                <div>
                    <span class="risk-badge {{ $riskClass }}">
                        <i class="bi {{ $riskIcon }}"></i>
                        {{ __($riskLevel) }}
                    </span>
                </div>

                {{-- Temperature --}}
                <div class="fav-temp">
                    <i class="bi {{ $tempIcon }}"></i>
                    {{ $temp }}
                </div>

                {{-- Currency --}}
                <div>
                    <span class="fav-currency">{{ $country->currency_code ?? 'N/A' }}</span>
                </div>

                {{-- Actions --}}
                <div class="fav-actions">
                    <a href="{{ route('user.country', $country->id) }}" class="btn-fav-detail">
                        {{ __('Detail') }} <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    <button class="btn-fav-remove btn-remove-favorite" data-id="{{ $country->id }}" title="{{ __('Remove from favorites') }}">
                        <i class="bi bi-star-fill"></i>
                    </button>
                </div>

            </div>
            @endforeach

            <div class="fav-no-results" id="noResults">
                <i class="bi bi-search text-muted" style="font-size: 2rem; display:block; margin-bottom:12px;"></i>
                <p class="text-muted mb-0">{{ __('No countries match your search.') }}</p>
            </div>
        </div>
    @endif

</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Live Search ──
    const searchInput = document.getElementById('favSearchInput');
    const noResults   = document.getElementById('noResults');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const term = this.value.trim().toLowerCase();
            const rows = document.querySelectorAll('.fav-row');
            let visible = 0;

            rows.forEach(function (row) {
                const name = row.getAttribute('data-name') || '';
                if (name.includes(term)) {
                    row.style.display = '';
                    visible++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (noResults) {
                noResults.style.display = visible === 0 ? 'block' : 'none';
            }
        });
    }

    // ── Remove Favorite ──
    document.querySelectorAll('.btn-remove-favorite').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const countryId = this.getAttribute('data-id');
            const row       = this.closest('.fav-row');

            if (!confirm('{{ __("Are you sure you want to remove this country from favorites?") }}')) return;

            this.disabled = true;
            const self = this;

            fetch('{{ route("user.watchlist.toggle") }}', {
                method : 'POST',
                headers: {
                    'Content-Type' : 'application/json',
                    'X-CSRF-TOKEN' : '{{ csrf_token() }}'
                },
                body: JSON.stringify({ country_id: countryId })
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.status === 'removed') {
                    row.style.transition = 'opacity 0.3s, transform 0.3s';
                    row.style.opacity   = '0';
                    row.style.transform = 'translateX(20px)';
                    setTimeout(function () {
                        row.remove();
                        const remaining = document.querySelectorAll('.fav-row');
                        if (remaining.length === 0) window.location.reload();
                    }, 300);
                }
            })
            .catch(function (e) { console.error(e); })
            .finally(function () {
                if (self) self.disabled = false;
            });
        });
    });

});
</script>
@endpush
