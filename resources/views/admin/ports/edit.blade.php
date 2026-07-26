@extends('layouts.admin')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .admin-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        background-color: #fff;
    }
    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #555;
    }
    .form-control, .form-select {
        border-radius: 8px;
        padding: 10px 15px;
        border: 1px solid #e0e0e0;
        font-size: 0.9rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #3E53A0;
        box-shadow: 0 0 0 0.25rem rgba(62, 83, 160, 0.1);
    }
    .btn-navy {
        background-color: #3E53A0;
        color: white;
        border: none;
    }
    .btn-navy:hover {
        background-color: #2c3e80;
        color: white;
    }
    #map {
        height: 300px;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        z-index: 1;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" style="max-width: 900px;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">{{ __('Edit Data Pelabuhan') }}</h4>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Update information for {{ $port->name }}</p>
        </div>
        <a href="{{ route('admin.ports.index') }}" class="btn btn-light border" style="border-radius: 8px; font-size: 0.85rem;">
            <i data-lucide="arrow-left" style="width: 16px; margin-right: 4px;"></i> Back to List
        </a>
    </div>

    <!-- Form Card -->
    <div class="admin-card card p-4">
        @if($errors->any())
            <div class="alert alert-danger" style="border-radius: 8px;">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.ports.update', $port->id) }}">
            @csrf
            @method('PUT')
            
            <h6 class="fw-bold mb-4 border-bottom pb-2">{{ __('Detail Pelabuhan') }}</h6>
            
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label">{{ __('Nama Pelabuhan') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $port->name) }}" placeholder="e.g. Tanjung Priok Port" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kode Pelabuhan (UN/LOCODE)</label>
                    <input type="text" name="unlocode" class="form-control" value="{{ old('unlocode', $port->unlocode) }}" placeholder="e.g. IDTJK" style="text-transform: uppercase;" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label">{{ __('NEGARA') }}</label>
                    <select name="country_id" class="form-select" required>
                        <option value="">-- Select Country --</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id', $port->country_id) == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <h6 class="fw-bold mb-4 border-bottom pb-2">Geographical Coordinates</h6>

            <div class="row g-4 mb-5">
                <div class="col-md-5">
                    <div class="mb-4">
                        <label class="form-label">Latitude</label>
                        <input type="number" step="any" name="latitude" id="inputLat" class="form-control" value="{{ old('latitude', $port->latitude) }}" placeholder="e.g. -6.1431" required>
                        <div class="form-text" style="font-size: 0.75rem;">Range: -90 to 90</div>
                    </div>
                    <div>
                        <label class="form-label">Longitude</label>
                        <input type="number" step="any" name="longitude" id="inputLng" class="form-control" value="{{ old('longitude', $port->longitude) }}" placeholder="e.g. 106.8706" required>
                        <div class="form-text" style="font-size: 0.75rem;">Range: -180 to 180</div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label mb-0"><i data-lucide="map" style="width: 16px; margin-right: 4px;"></i> Pilih Lokasi di Peta</label>
                        <span class="badge bg-light text-primary border"><i data-lucide="move" style="width: 12px; margin-right: 2px;"></i> Klik / Geser Marker</span>
                    </div>
                    <div id="map"></div>
                    <div class="form-text mt-2" style="font-size: 0.75rem;">
                        <i data-lucide="info" style="width: 12px; margin-right: 2px;"></i> Klik lokasi pada peta atau geser marker untuk memperbarui koordinat.
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.ports.index') }}" class="btn btn-light border px-4" style="border-radius: 8px;">{{ __('Batal') }}</a>
                <button type="submit" class="btn btn-navy px-4" style="border-radius: 8px;">
                    <i data-lucide="save" style="width: 16px; margin-right: 4px;"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        lucide.createIcons();

        // Initialize coordinates
        let initialLat = parseFloat(document.getElementById('inputLat').value) || -6.1431;
        let initialLng = parseFloat(document.getElementById('inputLng').value) || 106.8706;

        // Initialize Map
        const map = L.map('map').setView([initialLat, initialLng], 5);

        // Add Tile Layer (CartoDB Voyager - English labels)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
        }).addTo(map);

        // Add Draggable Marker
        const marker = L.marker([initialLat, initialLng], {
            draggable: true
        }).addTo(map);

        // Function to update inputs
        function updateInputs(lat, lng) {
            document.getElementById('inputLat').value = lat.toFixed(6);
            document.getElementById('inputLng').value = lng.toFixed(6);
        }

        // When marker is dragged
        marker.on('dragend', function(event) {
            const position = marker.getLatLng();
            updateInputs(position.lat, position.lng);
            map.panTo(position);
        });

        // When map is clicked
        map.on('click', function(event) {
            const position = event.latlng;
            marker.setLatLng(position);
            updateInputs(position.lat, position.lng);
            map.panTo(position);
        });

        // When inputs are typed manually, update map
        document.getElementById('inputLat').addEventListener('input', function() {
            const lat = parseFloat(this.value);
            const lng = parseFloat(document.getElementById('inputLng').value);
            if(!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                map.panTo([lat, lng]);
            }
        });

        document.getElementById('inputLng').addEventListener('input', function() {
            const lat = parseFloat(document.getElementById('inputLat').value);
            const lng = parseFloat(this.value);
            if(!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                map.panTo([lat, lng]);
            }
        });
    });
</script>
@endsection
