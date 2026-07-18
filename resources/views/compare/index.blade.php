@extends('layouts.app')

@section('title', 'Compare Countries')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">📊 Country Comparison</h1>
        <p class="text-muted">Cari dan pilih dua negara untuk membandingkan</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('compare.result') }}" class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">🇨🇴 Negara 1</label>
                        <input type="text" name="country1_search" class="form-control form-control-lg" 
                               placeholder="Ketik nama negara..." id="country1_search" 
                               value="{{ request('country1_search') }}" autocomplete="off">
                        <div id="country1_suggestions" class="list-group mt-1" style="max-height: 200px; overflow-y: auto; display: none;"></div>
                    </div>
                    <div class="col-md-2 text-center d-flex align-items-center justify-content-center">
                        <h1 class="display-4 text-muted">⚡</h1>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-bold">🇨🇴 Negara 2</label>
                        <input type="text" name="country2_search" class="form-control form-control-lg" 
                               placeholder="Ketik nama negara..." id="country2_search" 
                               value="{{ request('country2_search') }}" autocomplete="off">
                        <div id="country2_suggestions" class="list-group mt-1" style="max-height: 200px; overflow-y: auto; display: none;"></div>
                    </div>
                    <div class="col-md-12 text-center mt-3">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-arrows-left-right me-2"></i> Bandingkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const countries = @json($countries->map(fn($c) => ['id' => $c->id, 'name' => $c->country_name]));

    function setupSearch(inputId, suggestionId) {
        const input = document.getElementById(inputId);
        const suggestions = document.getElementById(suggestionId);

        input.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            if (query.length < 1) {
                suggestions.style.display = 'none';
                return;
            }

            const filtered = countries.filter(c => 
                c.name.toLowerCase().includes(query)
            ).slice(0, 10);

            if (filtered.length === 0) {
                suggestions.style.display = 'none';
                return;
            }

            suggestions.innerHTML = filtered.map(c => 
                `<a href="#" class="list-group-item list-group-item-action" data-id="${c.id}" data-name="${c.name}">${c.name}</a>`
            ).join('');
            suggestions.style.display = 'block';

            // Pilih suggestion
            suggestions.querySelectorAll('a').forEach(el => {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    input.value = this.dataset.name;
                    input.dataset.selected = this.dataset.id;
                    suggestions.style.display = 'none';
                });
            });
        });

        // Sembunyikan saat klik di luar
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                suggestions.style.display = 'none';
            }
        });
    }

    setupSearch('country1_search', 'country1_suggestions');
    setupSearch('country2_search', 'country2_suggestions');
});
</script>
@endpush
@endsection