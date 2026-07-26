@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --bg-body: #F8FAFC;
        --card-bg: #FFFFFF;
        --border-color: #E2E8F0;
        --text-main: #0F172A;
        --text-muted: #64748B;
        --primary: #2563EB;
        --primary-hover: #1D4ED8;
        --radius: 12px;
        --shadow-soft: 0 4px 20px rgba(15,23,42,0.04);
        --shadow-hover: 0 10px 30px rgba(15,23,42,0.08);
        --font-family: 'Inter', sans-serif;
    }

    body {
        background-color: var(--bg-body);
        font-family: var(--font-family);
    }

    .ent-form-card {
        background: #EFF6FF;
        border: 1px solid #BFDBFE;
        border-radius: 24px;
        box-shadow: var(--shadow-soft);
        padding: 40px;
        transition: box-shadow 200ms ease;
    }
    .ent-form-card:hover {
        box-shadow: var(--shadow-hover);
    }

    .txt-title { font-size: 28px; font-weight: 700; color: var(--text-main); letter-spacing: -0.5px; }
    .txt-subtitle { font-size: 14px; font-weight: 400; color: var(--text-muted); }
    .txt-section { font-size: 18px; font-weight: 600; color: var(--text-main); border-bottom: 2px solid var(--primary); padding-bottom: 16px; margin-bottom: 24px; }
    
    .btn-back-ent {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: linear-gradient(145deg, #ffffff, #f3f4f6);
        border: 1px solid #E5E7EB;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4B5563;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.03), -4px -4px 10px rgba(255, 255, 255, 0.8);
    }
    .btn-back-ent:hover {
        background: var(--primary);
        color: #FFFFFF;
        border-color: var(--primary);
        transform: translateX(-3px);
        box-shadow: 0 8px 15px rgba(37, 99, 235, 0.25);
    }
    .btn-back-ent i {
        font-size: 20px;
    }

    .form-label-ent {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 8px;
    }

    .form-control-ent, .form-select-ent {
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 12px 16px;
        font-size: 14px;
        color: var(--text-main);
        background-color: #FFFFFF;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        transition: all 0.2s ease;
    }
    .form-control-ent:focus, .form-select-ent:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        outline: none;
    }

    .input-group-ent {
        position: relative;
        display: flex;
        align-items: stretch;
        width: 100%;
    }
    .input-group-text-ent {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 400;
        color: var(--text-muted);
        text-align: center;
        background-color: #F8FAFC;
        border: 1px solid var(--border-color);
        border-right: none;
        border-radius: var(--radius) 0 0 var(--radius);
    }
    .input-group-ent > .form-control-ent {
        border-radius: 0 var(--radius) var(--radius) 0;
        flex: 1 1 auto;
        width: 1%;
    }
    .input-group-ent > .form-control-ent:focus {
        z-index: 3;
    }

    .btn-ent-primary {
        background-color: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius);
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
    }
    .btn-ent-primary:hover {
        background-color: var(--primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
    }
    
    .btn-ent-outline {
        background-color: white;
        color: var(--text-main);
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .btn-ent-outline:hover {
        background-color: #F1F5F9;
        border-color: #CBD5E1;
    }

    .route-container {
        background-color: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 20px;
        padding: 32px;
        margin-bottom: 32px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0 pb-5 pt-4" style="background-color: var(--bg-body); border-radius: 24px 24px 0 0; overflow: hidden;">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-xl-10">
            
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('user.shipments.index') }}" class="btn btn-light rounded-circle shadow-sm border bg-white me-3" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="bi bi-arrow-left text-dark"></i>
                </a>
                <div>
                    <h1 class="txt-title mb-1">{{ __('Create New Shipment') }}</h1>
                    <p class="txt-subtitle m-0">{{ __('Initialize a new logistics entry into the tracking network.') }}</p>
                </div>
            </div>

            <div class="ent-form-card">
                <form action="{{ route('user.shipments.store') }}" method="POST">
                    @csrf
                    
                    <h2 class="txt-section"><i class="bi bi-box-seam me-2" style="color: var(--primary);"></i>{{ __('General Information') }}</h2>
                    
                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <label class="form-label-ent">{{ __('Shipment Name') }}</label>
                            <input type="text" name="shipment_name" class="form-control form-control-ent" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-ent">{{ __('Goods') }}</label>
                            <input type="text" name="goods" class="form-control form-control-ent" required>
                        </div>
                    </div>

                    <h2 class="txt-section"><i class="bi bi-bezier2 me-2" style="color: var(--primary);"></i>{{ __('Routing Configuration') }}</h2>
                    
                    <!-- Route Section -->
                    <div class="route-container">
                        
                        <div class="row g-4 align-items-center mb-4">
                            <!-- Origin -->
                            <div class="col-md-5">
                                <label class="form-label-ent"><i class="bi bi-circle me-2 text-muted"></i>{{ __('Origin Country') }}</label>
                                <select id="origin_country" class="form-select form-select-ent" required>
                                    <option value="">{{ __('Select Country') }}</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Arrow Divider -->
                            <div class="col-md-2 text-center d-none d-md-block">
                                <div style="height: 1px; background-color: #BFDBFE; width: 100%; position: relative; top: 12px;"></div>
                                <i class="bi bi-arrow-right-circle-fill position-relative" style="color: #60A5FA; font-size: 20px; top: 2px; background: #FFFFFF; padding: 0 10px;"></i>
                            </div>
                            
                            <!-- Destination -->
                            <div class="col-md-5">
                                <label class="form-label-ent"><i class="bi bi-geo-alt-fill me-2" style="color: var(--primary);"></i>{{ __('Destination Country') }}</label>
                                <select id="dest_country" class="form-select form-select-ent" required>
                                    <option value="">{{ __('Select Country') }}</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-4 align-items-center">
                            <!-- Origin Port -->
                            <div class="col-md-5">
                                <label class="form-label-ent text-muted">{{ __('Origin Port') }}</label>
                                <select id="origin_port" name="origin_port_id" class="form-select form-select-ent" required disabled>
                                    <option value="">{{ __('Select Port') }}</option>
                                </select>
                            </div>

                            <div class="col-md-2 d-none d-md-block"></div>

                            <!-- Destination Port -->
                            <div class="col-md-5">
                                <label class="form-label-ent text-muted">{{ __('Destination Port') }}</label>
                                <select id="dest_port" name="destination_port_id" class="form-select form-select-ent" required disabled>
                                    <option value="">{{ __('Select Port') }}</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <h2 class="txt-section mt-5"><i class="bi bi-clock-history me-2" style="color: var(--primary);"></i>{{ __('Scheduling') }}</h2>

                    <!-- Schedule Section -->
                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <label class="form-label-ent">{{ __('Estimated Arrival (ETA)') }}</label>
                            <div class="input-group-ent">
                                <span class="input-group-text-ent"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" name="estimated_arrival" class="form-control form-control-ent" required>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Required Fields with Defaults -->
                    <input type="hidden" name="departure_date" value="{{ date('Y-m-d') }}">
                    <input type="hidden" name="current_status" value="pending">

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-5 pt-4 border-top gap-3">
                        
                        <!-- Fixed Info Alert -->
                        <div class="d-flex align-items-center gap-3 px-4 py-3 rounded" style="background-color: #EFF6FF; border: 1px solid #BFDBFE;">
                            <i class="bi bi-info-circle-fill fs-5" style="color: var(--primary);"></i>
                            <div>
                                <span class="d-block fw-bold text-dark" style="font-size: 13px;">{{ __('Automatic System ID') }}</span>
                                <span class="d-block text-muted" style="font-size: 12px;">{{ __('Shipment code will be generated upon save (e.g. SHP-2026...).') }}</span>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-3">
                            <a href="{{ route('user.shipments.index') }}" class="btn-ent-outline text-decoration-none">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn-ent-primary d-inline-flex align-items-center gap-2">
                                {{ __('Create Shipment') }} <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function loadPorts(countrySelectId, portSelectId) {
        const countryId = document.getElementById(countrySelectId).value;
        const portSelect = document.getElementById(portSelectId);
        
        portSelect.innerHTML = '<option value="">{{ __("Loading...") }}</option>';
        portSelect.disabled = true;

        if(!countryId) {
            portSelect.innerHTML = '<option value="">{{ __("Select Port") }}</option>';
            return;
        }

        fetch(`/user/shipments/api/ports/${countryId}`)
            .then(res => res.json())
            .then(data => {
                portSelect.innerHTML = '<option value="">{{ __("Select Port") }}</option>';
                data.forEach(port => {
                    portSelect.innerHTML += `<option value="${port.id}">${port.name}</option>`;
                });
                portSelect.disabled = false;
            })
            .catch(err => {
                portSelect.innerHTML = '<option value="">{{ __("Error loading ports") }}</option>';
                console.error(err);
            });
    }

    document.getElementById('origin_country').addEventListener('change', function() {
        loadPorts('origin_country', 'origin_port');
    });

    document.getElementById('dest_country').addEventListener('change', function() {
        loadPorts('dest_country', 'dest_port');
    });
});
</script>
@endpush
@endsection
