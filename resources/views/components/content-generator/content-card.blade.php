{{-- Content Generator Show Page - Content Card Component --}}
@props(['content'])

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-file-text text-primary me-2"></i>{{ __('translation.content_generator.generated_content') }}
        </h5>
        <div class="d-flex gap-2">
            <!-- Favorite Button -->
            <button type="button" 
                    class="btn btn-outline-warning btn-sm favorite-btn" 
                    data-content-id="{{ $content->id }}"
                    data-is-favorited="{{ $content->isFavoritedBy(Auth::id()) ? 'true' : 'false' }}"
                    onclick="toggleFavorite(this)">
                <i class="bi {{ $content->isFavoritedBy(Auth::id()) ? 'bi-star-fill' : 'bi-star' }} me-1"></i>
                <span class="favorite-text">{{ $content->isFavoritedBy(Auth::id()) ? __('translation.content_generator.favorited') : __('translation.content_generator.add_favorite') }}</span>
            </button>
            
            @if($content->contentType && $content->contentType->key === 'social_media_post')
            <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#socialPreviewModal">
                <i class="bi bi-share me-1"></i>{{ __('translation.content_generator.social_preview') }}
            </button>
            @endif
        </div>
    </div>
    <div class="card-body">
        @if($content->status === 'completed')
        <div id="content-output" class="content-output">
            {!! \Illuminate\Support\Str::markdown($content->output_text) !!}
        </div>
        @elseif($content->status === 'failed')
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ __('translation.content_generator.generation_failed') }}
            @if($content->error_message)
            <br><small>{{ $content->error_message }}</small>
            @endif
        </div>
        @else
        <div class="alert alert-info">
            <i class="bi bi-hourglass-split me-2"></i>
            {{ __('translation.content_generator.processing') }}
        </div>
        @endif

        <!-- Disclaimer -->
        <div class="alert alert-warning mt-4 mb-0">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <small>{{ __('translation.content_generator.disclaimer') }}</small>
        </div>
    </div>
</div>
