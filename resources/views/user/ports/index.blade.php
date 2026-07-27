@extends('layouts.app')

@section('content')
<div class="ports-page-wrapper">
    <div class="ports-layout">
        {{-- ── MAP (Left) ── --}}
        <div class="ports-map-wrap card border-0 shadow-sm rounded-4 bg-white p-4 d-flex flex-column">
            <div class="mb-3">
                <h4 class="fw-bold mb-0 text-dark">{{ __('Dasbor Lokasi Pelabuhan') }}</h4>
            </div>
            <div id="portsMap" class="rounded-3 flex-grow-1" style="width:100%;z-index:1;"></div>
        </div>

        {{-- ── FILTER PANEL (Right) ── --}}
        <div class="ports-filter-wrap">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                        <i class="bi bi-funnel-fill text-primary"></i> {{ __('Opsi Filter') }}
                    </h5>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted mb-1">{{ __('Search Port') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted" style="font-size:0.8rem;"></i></span>
                            <input type="text" id="searchInput" class="form-control bg-light border-start-0 ps-0" placeholder="{{ __('Nama pelabuhan...') }}" style="font-size:0.875rem;">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted mb-1">{{ __('Country') }}</label>
                        <select id="countrySelect" class="form-select" style="font-size:0.875rem;">
                            <option value="all">{{ __('All Countries') }}</option>
                            @php
                                $portCountries = $ports->pluck('country')->filter()->unique('id')->sortBy('name');
                            @endphp
                            @foreach($portCountries as $c)
                                <option value="{{ strtolower($c->iso2_code ?? '') }}"
                                    data-lat="{{ $c->latitude }}"
                                    data-lng="{{ $c->longitude }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button id="btnApply" class="btn btn-primary w-100 fw-semibold rounded-3 mb-2" style="font-size:0.875rem;">
                        <i class="bi bi-search me-2"></i>{{ __('Terapkan Filter') }}
                    </button>
                    <button id="btnReset" class="btn btn-link w-100 text-muted text-decoration-none fw-semibold" style="font-size:0.85rem;">
                        <i class="bi bi-x-lg me-1"></i>{{ __('Reset') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<style>
    .ports-page-wrapper { padding: 24px 0; }

    .ports-layout {
        display: flex;
        gap: 20px;
        height: 78vh;
    }

    .ports-map-wrap {
        flex: 1 1 0%;
        min-width: 0;
        background: #fff;
    }

    .ports-filter-wrap {
        width: 300px;
        flex-shrink: 0;
        overflow-y: auto;
    }

    /* Custom Scrollbar */
    #searchResults::-webkit-scrollbar { width: 4px; }
    #searchResults::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    #searchResults::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }

    .port-item {
        transition: all 0.15s ease;
        cursor: pointer;
        border-left: 3px solid transparent;
        border-radius: 10px;
        padding: 8px 10px;
        margin-bottom: 6px;
        background: #F9FAFB;
        border: 1px solid #E5E7EB;
    }
    .port-item:hover {
        background: #EFF6FF;
        border-color: #BFDBFE;
        border-left: 3px solid #3B82F6;
    }

    /* Light Green Teardrop Marker */
    .custom-div-icon {
        background: transparent !important;
        border: none !important;
    }
    .map-pin {
        width: 30px;
        height: 30px;
        background-color: #4ADE80;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: -2px 2px 6px rgba(0,0,0,0.2);
        border: 2px solid #16A34A;
        cursor: pointer;
        transition: transform 0.15s;
    }
    .map-pin:hover { transform: rotate(-45deg) scale(1.15); }
    .map-pin-inner {
        width: 18px;
        height: 18px;
        background: #fff;
        border-radius: 50%;
        transform: rotate(45deg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #16A34A;
        font-size: 9px;
    }

    /* Popup View Details button white text */
    .popup-detail-btn {
        display: block;
        width: 100%;
        padding: 6px 0;
        background: #22C55E;
        color: #fff !important;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        margin-top: 8px;
    }
    .popup-detail-btn:hover { background: #16A34A; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const rawPorts = @json($ports);
    let clusterGroup = null;

    // Map init
    const map = L.map('portsMap', { zoomControl: false }).setView([20, 0], 3);
    L.tileLayer('http://mt0.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        attribution: '&copy; Google Maps',
        maxZoom: 20
    }).addTo(map);
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    // Icon
    const portIcon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div class="map-pin"><div class="map-pin-inner"><i class="bi bi-geo-fill"></i></div></div>`,
        iconSize: [30, 30],
        iconAnchor: [15, 30],
        popupAnchor: [0, -30]
    });

    // Render markers with clustering (no lag!)
    function renderAll(data) {
        // Remove old cluster group
        if (clusterGroup) {
            map.removeLayer(clusterGroup);
        }
        clusterGroup = L.markerClusterGroup({
            maxClusterRadius: 50,
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true
        });

        data.forEach(port => {
            const lat = parseFloat(port.latitude);
            const lng = parseFloat(port.longitude);
            const countryCode = port.country ? port.country.iso2_code.toLowerCase() : '';
            const countryName = port.country ? port.country.name : 'Unknown';

            if (!isNaN(lat) && !isNaN(lng)) {
                const marker = L.marker([lat, lng], { icon: portIcon });
                marker.bindPopup(`
                    <div style="text-align:center;min-width:150px;padding:4px;">
                        <img src="https://flagcdn.com/32x24/${countryCode}.png" style="margin-bottom:8px;border-radius:3px;border:1px solid #e5e7eb;" onerror="this.style.display='none'">
                        <div style="font-weight:700;font-size:0.9rem;margin-bottom:3px;">${port.name}</div>
                        <div style="font-size:0.78rem;color:#6B7280;margin-bottom:2px;">${countryName}</div>
                        <a href="/user/ports/${port.id}" class="popup-detail-btn">View Details →</a>
                    </div>`);
                clusterGroup.addLayer(marker);
            }
        });

        map.addLayer(clusterGroup);
    }

    // Initial render
    renderAll(rawPorts);

    // Filter function
    function applyFilter(flyToCountry = false) {
        const query = document.getElementById('searchInput').value.trim().toLowerCase();
        const selectedCountry = document.getElementById('countrySelect').value.toLowerCase();

        const filtered = rawPorts.filter(port => {
            const nameMatch = port.name.toLowerCase().includes(query);
            const countryCode = port.country ? port.country.iso2_code.toLowerCase() : '';
            const countryMatch = selectedCountry === 'all' || countryCode === selectedCountry;
            return nameMatch && countryMatch;
        });

        renderAll(filtered);

        if (flyToCountry && selectedCountry !== 'all') {
            const opt = document.getElementById('countrySelect').options[document.getElementById('countrySelect').selectedIndex];
            const lat = parseFloat(opt.getAttribute('data-lat'));
            const lng = parseFloat(opt.getAttribute('data-lng'));
            if (!isNaN(lat) && !isNaN(lng)) {
                map.flyTo([lat, lng], 5, { duration: 1.5 });
            }
        } else if (flyToCountry) {
            map.flyTo([20, 0], 3, { duration: 1.5 });
        }
    }

    // Live search
    document.getElementById('searchInput').addEventListener('input', () => applyFilter(false));
    document.getElementById('countrySelect').addEventListener('change', () => applyFilter(true));

    // Buttons
    document.getElementById('btnApply').addEventListener('click', () => applyFilter(true));
    document.getElementById('btnReset').addEventListener('click', () => {
        document.getElementById('searchInput').value = '';
        document.getElementById('countrySelect').value = 'all';
        renderAll(rawPorts);
        map.flyTo([20, 0], 3, { duration: 1.5 });
    });
});
</script>
@endpush