@extends('layout.home.main')

@section('title', __('translation.content_generator.favorites_title'))

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">
                <i class="bi bi-star-fill text-warning me-2"></i>{{ __('translation.content_generator.favorites_title') }}
            </h1>
            <p class="text-muted mb-0">{{ __('translation.content_generator.favorites_subtitle') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('content.history') }}" class="btn btn-outline-secondary">
                <i class="bi bi-clock-history me-2"></i>{{ __('translation.content_generator.view_history') }}
            </a>
            <a href="{{ route('content.index') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>{{ __('translation.content_generator.new_content') }}
            </a>
        </div>
    </div>

    @if($contents->isEmpty())
        <!-- Empty State -->
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-star display-1 text-warning mb-3"></i>
                <h4 class="fw-bold mb-2">{{ __('translation.content_generator.no_favorites') }}</h4>
                <p class="text-muted mb-4">{{ __('translation.content_generator.no_favorites_message') }}</p>
                <a href="{{ route('content.history') }}" class="btn btn-outline-primary">
                    <i class="bi bi-clock-history me-2"></i>{{ __('translation.content_generator.browse_history') }}
                </a>
            </div>
        </div>
    @else
        <!-- Content Grid -->
        <div class="row g-3">
            @foreach($contents as $content)
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body">
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-2">
                                    @if($content->contentType)
                                    <span class="badge me-2" style="background-color: {{ $content->contentType->color ?? '#4A90D9' }};">
                                        {{ $content->contentType->name }}
                                    </span>
                                    @endif
                                    @if($content->specialty)
                                    <span class="badge bg-secondary">{{ $content->specialty->name }}</span>
                                    @endif
                                </div>
                                <h5 class="fw-bold mb-2">
                                    {{ Str::limit($content->input_data['topic'] ?? 'Content', 60) }}
                                </h5>
                            </div>
                            <button type="button" 
                                    class="btn btn-sm btn-link text-warning p-0 favorite-btn-small"
                                    data-content-id="{{ $content->id }}"
                                    onclick="toggleFavoriteInList(this)"
                                    title="{{ __('translation.content_generator.remove_favorite') }}">
                                <i class="bi bi-star-fill fs-5"></i>
                            </button>
                        </div>

                        <!-- Preview -->
                        <p class="text-muted small mb-3">
                            {{ Str::limit(strip_tags($content->output_text), 120) }}
                        </p>

                        <!-- Meta Info -->
                        <div class="d-flex justify-content-between align-items-center text-muted small mb-3">
                            <span>
                                <i class="bi bi-calendar3 me-1"></i>{{ $content->created_at->format('M d, Y') }}
                            </span>
                            <span>
                                <i class="bi bi-translate me-1"></i>{{ $content->input_data['language'] ?? 'English' }}
                            </span>
                            <span>
                                <i class="bi bi-coin me-1"></i>{{ $content->credits_used ?? 1 }} {{ __('translation.content_generator.credits') }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('content.show', $content->id) }}" class="btn btn-primary btn-sm flex-grow-1">
                                <i class="bi bi-eye me-1"></i>{{ __('translation.content_generator.view') }}
                            </a>
                            <a href="{{ route('content.export.pdf', $content->id) }}?format=a4" 
                               class="btn btn-outline-danger btn-sm"
                               title="{{ __('translation.content_generator.export_pdf') }}">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                            <button type="button" 
                                    class="btn btn-outline-secondary btn-sm" 
                                    onclick="copyToClipboard('{{ addslashes(strip_tags($content->output_text)) }}')"
                                    title="{{ __('translation.content_generator.copy') }}">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $contents->links() }}
        </div>
    @endif
</div>

<script>
/**
 * Toggle favorite in list view using centralized FormHelper
 */
function toggleFavoriteInList(button) {
    const contentId = button.dataset.contentId;
    const baseUrl = "{{ route('content.toggle.favorite', ['id' => ':id']) }}";
    const toggleUrl = baseUrl.replace(':id', contentId);
    
    if (typeof FormHelper !== 'undefined') {
        FormHelper.toggleFavorite(button, toggleUrl, {
            removeCard: true,
            successMessage: '{{ __("translation.content_generator.removed_favorite") }}',
            errorMessage: '{{ __("translation.content_generator.error_occurred") }}'
        });
    } else {
        // Fallback
        const card = button.closest('.col-lg-6');
        const icon = button.querySelector('i');
        icon.className = 'bi bi-hourglass-split fs-5';
        button.disabled = true;
        
        fetch(toggleUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                card.style.opacity = '0';
                setTimeout(() => { card.remove(); if (!document.querySelector('.col-lg-6')) location.reload(); }, 300);
            } else {
                icon.className = 'bi bi-star-fill fs-5';
                button.disabled = false;
            }
        });
    }
}

/**
 * Copy text to clipboard using centralized function
 */
function copyToClipboard(text) {
    if (typeof ContentManager !== 'undefined' && ContentManager.copyText) {
        ContentManager.copyText(text, '{{ __("translation.content_generator.copied_success") }}');
    } else {
        navigator.clipboard.writeText(text).then(() => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'success', title: '{{ __("translation.content_generator.copied_success") }}', toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
            }
        });
    }
}
</script>
@endsection
