@extends('layout.home.main')

@section('title', __('translation.content_generator.history_title'))

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">{{ __('translation.content_generator.history_title') }}</h1>
            <p class="text-muted mb-0">{{ __('translation.content_generator.history_subtitle') }}</p>
        </div>
        <a href="{{ route('content.index') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>{{ __('translation.content_generator.new_content') }}
        </a>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="row g-3 align-items-end" id="filtersRow">
                <div class="col-md-4">
                    <label class="form-label small text-muted">{{ __('translation.content_generator.filter.content_type') }}</label>
                    <select name="content_type" id="filterContentType" class="form-select form-select-sm filter-select">
                        <option value="">{{ __('translation.content_generator.filter.all_types') }}</option>
                        @foreach($contentTypes as $type)
                        <option value="{{ $type->slug }}" {{ request('content_type') == $type->slug ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">{{ __('translation.content_generator.filter.specialty') }}</label>
                    <select name="specialty" id="filterSpecialty" class="form-select form-select-sm filter-select">
                        <option value="">{{ __('translation.content_generator.filter.all_specialties') }}</option>
                        @foreach($specialties as $specialty)
                        <option value="{{ $specialty->slug }}" {{ request('specialty') == $specialty->slug ? 'selected' : '' }}>{{ $specialty->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">{{ __('translation.content_generator.filter.status') }}</label>
                    <select name="status" id="filterStatus" class="form-select form-select-sm filter-select">
                        <option value="">{{ __('translation.content_generator.filter.all_statuses') }}</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('translation.content_generator.status.completed') }}</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ __('translation.content_generator.status.failed') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Container -->
    <div id="contentContainer">
        @include('content-generator.partials.history-table', ['contents' => $contents])
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filters = document.querySelectorAll('.filter-select');
    const container = document.getElementById('contentContainer');
    let debounceTimer;
    
    // Apply filters on change (with debounce)
    filters.forEach(filter => {
        filter.addEventListener('change', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(applyFilters, 300);
        });
    });
    
    async function applyFilters() {
        const params = new URLSearchParams();
        filters.forEach(f => { if (f.value) params.set(f.name, f.value); });
        
        // Update URL without reload
        const newUrl = `${window.location.pathname}${params.toString() ? '?' + params.toString() : ''}`;
        window.history.pushState({}, '', newUrl);
        
        // Show loading
        container.style.opacity = '0.5';
        container.style.pointerEvents = 'none';
        
        try {
            const response = await ApiClient.get('{{ route("content.history") }}', Object.fromEntries(params));
            
            // Create temp element to extract HTML
            if (response.html) {
                container.innerHTML = response.html;
            } else {
                // Fallback: reload via fetch
                const htmlResponse = await fetch(newUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await htmlResponse.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.getElementById('contentContainer');
                if (newContent) container.innerHTML = newContent.innerHTML;
            }
        } catch (error) {
            console.error('Filter error:', error);
        } finally {
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';
        }
    }
    
    // Handle pagination clicks via AJAX
    container.addEventListener('click', async function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            const url = link.href;
            window.history.pushState({}, '', url);
            
            container.style.opacity = '0.5';
            try {
                const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.getElementById('contentContainer');
                if (newContent) container.innerHTML = newContent.innerHTML;
            } catch (error) {
                window.location.href = url;
            } finally {
                container.style.opacity = '1';
            }
        }
    });
    
    // Handle browser back/forward
    window.addEventListener('popstate', () => location.reload());
});
</script>
@endsection
