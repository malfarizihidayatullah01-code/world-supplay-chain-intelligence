@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="font-family: 'Inter', sans-serif;">
    <!-- Header -->
    <div class="mb-4">
        <h3 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">{{ __('Mesin Perbandingan Negara') }}</h3>
    </div>

    <!-- Selectors -->
    <div class="card border-0 mb-4 overflow-hidden" style="border: 1px solid var(--border-color) !important; border-radius: var(--radius-card) !important; box-shadow: var(--shadow-global) !important;">
        <div class="card-body p-4 bg-white">
            <form id="compareForm" class="row g-3 align-items-center m-0">
                <!-- Country A -->
                <div class="col-md-5">
                    <label class="form-label text-muted small fw-bold mb-2">{{ __('Negara A') }}</label>
                    <select name="country_a" id="country_a" class="form-select border-0 bg-light rounded-3 shadow-none" required>
                        <option value="">{{ __('Select Country...') }}</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->id }}" data-iso2="{{ strtolower($c->iso2_code ?? '') }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- VS Separator & Button -->
                <div class="col-md-2 d-flex flex-column align-items-center justify-content-center position-relative">
                    <div class="d-none d-md-block position-absolute w-100" style="top: 60%; height: 2px; background: #f1f5f9; z-index: 0;"></div>
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle text-white fw-bold shadow-sm position-relative mt-md-4 mb-3 mb-md-0" style="width: 44px; height: 44px; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); z-index: 1; font-size: 0.9rem;">
                        VS
                    </div>
                </div>

                <!-- Country B -->
                <div class="col-md-5">
                    <label class="form-label text-muted small fw-bold mb-2">{{ __('Negara B') }}</label>
                    <div class="d-flex gap-2">
                        <select name="country_b" id="country_b" class="form-select border-0 bg-light rounded-3 shadow-none flex-grow-1" required>
                            <option value="">{{ __('Select Country...') }}</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->id }}" data-iso2="{{ strtolower($c->iso2_code ?? '') }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" id="compareBtn" class="btn fw-bold shadow-sm rounded-3 text-white px-4 text-nowrap flex-shrink-0" style="background-color: #0ea5e9; border: none; min-width: max-content; display: inline-flex !important; flex-direction: row; align-items: center; justify-content: center; gap: 6px;">
                            <i class="bi bi-play-fill"></i> <span>{{ __('Bandingkan') }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Skeleton -->
    <div id="loadingSkeleton" class="d-none text-center py-5 my-5">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
        <h5 class="text-muted mt-3 fw-bold">{{ __('Menganalisis Data...') }}</h5>
    </div>

    <!-- Comparison Content (VS Board) -->
    <div id="comparisonContent" class="d-none">
        
        <div class="card border-0 mb-4 overflow-hidden" style="border: 1px solid var(--border-color) !important; border-radius: var(--radius-card) !important; box-shadow: var(--shadow-global) !important;">
            <!-- Headers for the Board -->
            <div class="row g-0 align-items-center border-bottom py-4 px-3" style="background: #f8fafc;">
                <div class="col-5 d-flex align-items-center justify-content-center">
                    <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-3" id="boardNameA">-</h4>
                </div>
                <div class="col-2 text-center">
                    <span class="badge px-4 py-2 rounded-pill fw-bold shadow-sm" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: #ffffff; font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase;">
                        VS
                    </span>
                </div>
                <div class="col-5 d-flex align-items-center justify-content-center">
                    <h4 class="fw-bold text-dark mb-0 d-flex align-items-center justify-content-end gap-3" id="boardNameB" style="text-align: right;">-</h4>
                </div>
            </div>

            <!-- 1. GDP -->
            <div class="row g-0 align-items-center border-bottom bg-white hover-bg-light transition-all py-3">
                <div class="col-4 text-center">
                    <h3 class="fw-bold text-dark mb-0" id="gdpA">-</h3>
                    <small class="text-muted fw-medium">{{ __('Trillion USD') }}</small>
                </div>
                <div class="col-4 d-flex flex-column align-items-center justify-content-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                        <i class="bi bi-bank fs-5"></i>
                    </div>
                    <div class="fw-bold text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('PDB') }}</div>
                </div>
                <div class="col-4 text-center">
                    <h3 class="fw-bold text-dark mb-0" id="gdpB">-</h3>
                    <small class="text-muted fw-medium">{{ __('Trillion USD') }}</small>
                </div>
            </div>

            <!-- 2. INFLATION -->
            <div class="row g-0 align-items-center border-bottom bg-slate-50 hover-bg-light transition-all py-3" style="background-color: #f8fafc;">
                <div class="col-4 text-center">
                    <h3 class="fw-bold text-dark mb-1" id="infA">-</h3>
                    <div id="infLabelA"></div>
                </div>
                <div class="col-4 d-flex flex-column align-items-center justify-content-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                        <i class="bi bi-graph-up-arrow fs-5"></i>
                    </div>
                    <div class="fw-bold text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('Inflasi') }}</div>
                </div>
                <div class="col-4 text-center">
                    <h3 class="fw-bold text-dark mb-1" id="infB">-</h3>
                    <div id="infLabelB"></div>
                </div>
            </div>

            <!-- 3. RISK -->
            <div class="row g-0 align-items-center border-bottom bg-white hover-bg-light transition-all py-3">
                <div class="col-4 text-center">
                    <h3 class="fw-bold text-dark mb-1" id="riskA">-</h3>
                    <div id="riskLabelA"></div>
                </div>
                <div class="col-4 d-flex flex-column align-items-center justify-content-center">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                        <i class="bi bi-shield-exclamation fs-5"></i>
                    </div>
                    <div class="fw-bold text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('Skor Risiko') }}</div>
                </div>
                <div class="col-4 text-center">
                    <h3 class="fw-bold text-dark mb-1" id="riskB">-</h3>
                    <div id="riskLabelB"></div>
                </div>
            </div>

            <!-- 4. WEATHER -->
            <div class="row g-0 align-items-center border-bottom bg-slate-50 hover-bg-light transition-all py-3" style="background-color: #f8fafc;">
                <div class="col-4 text-center">
                    <h3 class="fw-bold text-dark mb-0" id="weatherA">-</h3>
                    <small class="text-muted fw-medium" id="weatherDescA">-</small>
                </div>
                <div class="col-4 d-flex flex-column align-items-center justify-content-center">
                    <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                        <i class="bi bi-cloud-sun fs-5"></i>
                    </div>
                    <div class="fw-bold text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('Cuaca') }}</div>
                </div>
                <div class="col-4 text-center">
                    <h3 class="fw-bold text-dark mb-0" id="weatherB">-</h3>
                    <small class="text-muted fw-medium" id="weatherDescB">-</small>
                </div>
            </div>

            <!-- 5. CURRENCY -->
            <div class="row g-0 align-items-center bg-white hover-bg-light transition-all py-3">
                <div class="col-4 text-center">
                    <h4 class="fw-bold text-dark mb-1" id="currA" style="font-size: 1.4rem;">-</h4>
                    <div id="currChangeA"></div>
                </div>
                <div class="col-4 d-flex flex-column align-items-center justify-content-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                        <i class="bi bi-currency-exchange fs-5"></i>
                    </div>
                    <div class="fw-bold text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('Mata Uang') }}</div>
                </div>
                <div class="col-4 text-center">
                    <h4 class="fw-bold text-dark mb-1" id="currB" style="font-size: 1.4rem;">-</h4>
                    <div id="currChangeB"></div>
                </div>
            </div>
        </div>

        <!-- AI Recommendation Summary -->
        <div class="card border-0 mt-3" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-radius: var(--radius-card) !important; box-shadow: var(--shadow-global) !important;">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center bg-success text-white rounded-circle shadow-sm flex-shrink-0" style="width: 44px; height: 44px;">
                    <i class="bi bi-robot fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-success mb-1" style="letter-spacing: -0.3px;">{{ __('AI Final Verdict') }}</h6>
                    <p class="text-dark fw-medium mb-0" style="font-size: 0.9rem; line-height: 1.4;" id="aiRec">-</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .transition-all { transition: all var(--transition-speed) ease-in-out; }
    .hover-bg-light:hover { background-color: rgba(37, 99, 235, 0.05) !important; }
    
    /* Select2 Bootstrap 5 Theme overrides */
    .select2-container .select2-selection--single {
        height: 48px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-card);
        display: flex;
        align-items: center;
        background-color: var(--surface-color);
        box-shadow: none !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 48px;
        padding-left: 16px;
        color: var(--text-primary);
        font-weight: 600;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px;
        right: 12px;
    }
    .select2-dropdown {
        border-color: var(--border-color);
        border-radius: var(--radius-card);
        box-shadow: var(--shadow-global);
        overflow: hidden;
    }
    .select2-results__option {
        padding: 10px 16px;
        font-weight: 500;
        transition: background var(--transition-speed);
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: var(--primary);
    }
    .select2-search--dropdown .select2-search__field {
        border-radius: var(--radius-button);
        border: 1px solid var(--border-color);
    }
</style>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function formatCountry(country) {
        if (!country.id) { return country.text; }
        var iso2 = $(country.element).data('iso2');
        if(!iso2) return country.text;
        
        var $country = $(
            '<div class="d-flex align-items-center"><img src="https://flagcdn.com/w20/' + iso2 + '.png" class="shadow-sm me-2" style="width:24px; border-radius: 3px;" onerror="this.style.display=\'none\'" /> <span>' + country.text + '</span></div>'
        );
        return $country;
    }

    $(document).ready(function() {
        $('#country_a, #country_b').select2({
            templateResult: formatCountry,
            templateSelection: formatCountry,
            width: '100%'
        });
    });

    function getBadge(level, extra = '') {
        if (!level) return `<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3">-</span>`;
        let extText = extra ? ` <span class="ms-1 fw-bold">${extra}</span>` : '';
        const baseStyle = 'badge px-3 py-1 rounded-pill fw-bold shadow-sm';
        
        if(level === 'Low') return `<span class="${baseStyle} bg-white text-success border border-success border-opacity-25">Low${extText}</span>`;
        if(level === 'Medium') return `<span class="${baseStyle} bg-white text-warning border border-warning border-opacity-25">Medium${extText}</span>`;
        if(level === 'High') return `<span class="${baseStyle} bg-white text-danger border border-danger border-opacity-25">High${extText}</span>`;
        return `<span class="${baseStyle} bg-white text-secondary border border-secondary border-opacity-25">-</span>`;
    }

    function fetchDataAndRender(ca, cb) {
        if(!ca || !cb) return;
        
        document.getElementById('comparisonContent').classList.add('d-none');
        document.getElementById('loadingSkeleton').classList.remove('d-none');

        fetch(`{{ route('user.comparison.ajax') }}?country_a=${ca}&country_b=${cb}`)
            .then(res => res.json())
            .then(data => {
                if(data.error) {
                    alert(data.error);
                    return;
                }
                
                const A = data.countryA || {};
                const B = data.countryB || {};

                // Get iso2 robustly from the dropdown
                let isoA = $('#country_a').val() === ca ? $('#country_a option:selected').data('iso2') : '';
                let isoB = $('#country_b').val() === cb ? $('#country_b option:selected').data('iso2') : '';
                
                let imgA = isoA ? `<img src="https://flagcdn.com/w40/${isoA}.png" class="shadow-sm rounded-2" style="width:40px; height: auto;" onerror="this.style.display='none'">` : '';
                let imgB = isoB ? `<img src="https://flagcdn.com/w40/${isoB}.png" class="shadow-sm rounded-2" style="width:40px; height: auto;" onerror="this.style.display='none'">` : '';

                // Headers
                document.getElementById('boardNameA').innerHTML = `${imgA} <span class="text-truncate">${A.name || 'N/A'}</span>`;
                document.getElementById('boardNameB').innerHTML = `<span class="text-truncate">${B.name || 'N/A'}</span> ${imgB}`;

                // 1. GDP
                document.getElementById('gdpA').innerText = (A.gdp !== undefined && A.gdp !== null) ? A.gdp : '-';
                document.getElementById('gdpB').innerText = (B.gdp !== undefined && B.gdp !== null) ? B.gdp : '-';

                // 2. Inflation
                document.getElementById('infA').innerText = (A.inflation !== undefined && A.inflation !== null) ? A.inflation + '%' : '-%';
                document.getElementById('infB').innerText = (B.inflation !== undefined && B.inflation !== null) ? B.inflation + '%' : '-%';
                
                let infBadge = (val) => {
                    if (val === undefined || val === null || isNaN(val)) return `<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3 py-1 shadow-sm">-</span>`;
                    if (val > 5) return `<span class="badge bg-white text-danger border border-danger border-opacity-25 rounded-pill px-3 py-1 shadow-sm fw-bold">Tinggi</span>`;
                    if (val < 0) return `<span class="badge bg-white text-warning border border-warning border-opacity-25 rounded-pill px-3 py-1 shadow-sm fw-bold">Deflasi</span>`;
                    return `<span class="badge bg-white text-success border border-success border-opacity-25 rounded-pill px-3 py-1 shadow-sm fw-bold">Stabil</span>`;
                };
                document.getElementById('infLabelA').innerHTML = infBadge(A.inflation);
                document.getElementById('infLabelB').innerHTML = infBadge(B.inflation);

                // 3. Risk
                document.getElementById('riskA').innerText = A.risk && A.risk.overall !== undefined ? A.risk.overall : '-';
                document.getElementById('riskB').innerText = B.risk && B.risk.overall !== undefined ? B.risk.overall : '-';
                document.getElementById('riskLabelA').innerHTML = A.risk && A.risk.overall_label ? getBadge(A.risk.overall_label) : getBadge('');
                document.getElementById('riskLabelB').innerHTML = B.risk && B.risk.overall_label ? getBadge(B.risk.overall_label) : getBadge('');

                // 4. Weather
                let wTempA = A.weather && A.weather.temp !== undefined ? A.weather.temp + '°C' : '-°C';
                let wTempB = B.weather && B.weather.temp !== undefined ? B.weather.temp + '°C' : '-°C';
                document.getElementById('weatherA').innerText = wTempA;
                document.getElementById('weatherB').innerText = wTempB;
                document.getElementById('weatherDescA').innerText = A.weather && A.weather.desc ? A.weather.desc : '-';
                document.getElementById('weatherDescB').innerText = B.weather && B.weather.desc ? B.weather.desc : '-';

                // 5. Currency
                let cRateA = A.currency && A.currency.rate !== undefined ? Number(A.currency.rate).toLocaleString(undefined, {minimumFractionDigits: 2}) : '-';
                let cRateB = B.currency && B.currency.rate !== undefined ? Number(B.currency.rate).toLocaleString(undefined, {minimumFractionDigits: 2}) : '-';
                document.getElementById('currA').innerText = cRateA;
                document.getElementById('currB').innerText = cRateB;
                
                const formatChange = (c, code) => {
                    if (c === undefined || c === null) return `<span class="small text-muted">-</span>`;
                    let color = 'text-muted';
                    let icon = 'bi-dash';
                    if(c > 0) { color = 'text-success'; icon = 'bi-caret-up-fill'; }
                    if(c < 0) { color = 'text-danger'; icon = 'bi-caret-down-fill'; }
                    let num = c !== 0 ? Math.abs(c) + '%' : '0%';
                    let codeText = code ? ` <span class="small text-muted">(${code}/USD)</span>` : '';
                    return `<span class="small fw-bold ${color}"><i class="bi ${icon}"></i> ${num}</span>${codeText}`;
                };
                
                let cChangeA = A.currency ? A.currency.change : null;
                let cChangeB = B.currency ? B.currency.change : null;
                document.getElementById('currChangeA').innerHTML = formatChange(cChangeA, A.currency_code);
                document.getElementById('currChangeB').innerHTML = formatChange(cChangeB, B.currency_code);

                // AI Recommendation
                const rec = data.recommendation || {};
                document.getElementById('aiRec').innerText = rec.recommendation || 'Data tidak cukup untuk menyimpulkan.';

                // Show Data
                document.getElementById('loadingSkeleton').classList.add('d-none');
                document.getElementById('comparisonContent').classList.remove('d-none');
            })
            .catch(err => {
                console.error(err);
                alert("Terjadi kesalahan saat mengambil data.");
                document.getElementById('loadingSkeleton').classList.add('d-none');
            });
    }

    document.getElementById('compareForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const ca = document.getElementById('country_a').value;
        const cb = document.getElementById('country_b').value;
        const btn = document.getElementById('compareBtn');
        
        if(!ca || !cb) {
            alert('Silakan pilih kedua negara terlebih dahulu.');
            return;
        }

        if(ca === cb) {
            alert('Harap pilih dua negara yang berbeda.');
            return;
        }
        
        btn.disabled = true;
        
        // Show loading state on button
        const originalBtnHtml = btn.innerHTML;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menganalisis...`;
        
        fetchDataAndRender(ca, cb);
        
        // Timeout to re-enable button to prevent spam click
        setTimeout(() => { 
            btn.disabled = false; 
            btn.innerHTML = originalBtnHtml;
        }, 1000);
    });
</script>
@endpush
