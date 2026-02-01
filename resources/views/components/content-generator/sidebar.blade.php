{{-- Content Generator Show Page - Sidebar Component --}}
@props(['content'])

<!-- Quick Stats -->
<div class="quick-stats">
    <div class="quick-stat">
        <div class="quick-stat-value">{{ $content->word_count ?? str_word_count($content->output_text ?? '') }}</div>
        <div class="quick-stat-label">{{ __('translation.content_generator.words') }}</div>
    </div>
    <div class="quick-stat">
        <div class="quick-stat-value">{{ $content->credits_used ?? 1 }}</div>
        <div class="quick-stat-label">{{ __('translation.content_generator.credits') }}</div>
    </div>
    <div class="quick-stat">
        <div class="quick-stat-value">{{ count($content->translations ?? []) }}</div>
        <div class="quick-stat-label">{{ __('translation.content_generator.translations') }}</div>
    </div>
</div>

<!-- Content Details -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold">{{ __('translation.content_generator.content_details') }}</h6>
    </div>
    <div class="card-body">
        <ul class="list-unstyled mb-0">
            <li class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">{{ __('translation.content_generator.type') }}</span>
                <span class="fw-medium">
                    @if($content->contentType)
                    <span class="badge" style="background-color: {{ $content->contentType->color ?? '#4A90D9' }};">
                        {{ $content->contentType->name }}
                    </span>
                    @else
                    -
                    @endif
                </span>
            </li>
            <li class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">{{ __('translation.content_generator.specialty') }}</span>
                <span class="fw-medium">
                    @if($content->specialty)
                    {{ $content->specialty->name }}
                    @else
                    {{ __('translation.content_generator.form.general') }}
                    @endif
                </span>
            </li>
            @if($content->topic)
            <li class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">{{ __('translation.content_generator.topic') }}</span>
                <span class="fw-medium">{{ $content->topic->name }}</span>
            </li>
            @endif
            <li class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">{{ __('translation.content_generator.language') }}</span>
                <span class="fw-medium">
                    @php
                        $langCode = $content->input_data['language'] ?? $content->language ?? 'en';
                        $languages = config('languages', []);
                        $langName = $languages[$langCode]['display'] ?? $langCode;
                    @endphp
                    <i class="bi bi-translate me-1"></i>{{ $langName }}
                </span>
            </li>
            <li class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">{{ __('translation.content_generator.tone') }}</span>
                <span class="fw-medium">{{ ucfirst($content->input_data['tone'] ?? '-') }}</span>
            </li>
            <li class="d-flex justify-content-between py-2">
                <span class="text-muted">{{ __('translation.content_generator.created') }}</span>
                <span class="fw-medium">{{ $content->created_at->format('M d, Y H:i') }}</span>
            </li>
        </ul>
    </div>
</div>

<!-- Translations Panel -->
<div class="translations-panel" id="translationsPanelSidebar">
    <div class="translations-panel-header">
        <span class="translations-panel-title">
            <i class="bi bi-translate me-1"></i> {{ __('translation.content_generator.translations') }}
        </span>
        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#translateModal">
            <i class="bi bi-plus"></i> {{ __('translation.content_generator.add') }}
        </button>
    </div>
    <div id="sidebarTranslationsList">
        <div class="no-translations" id="noTranslationsMessage">
            <i class="bi bi-translate d-block"></i>
            <p class="mb-2">{{ __('translation.content_generator.no_translations') }}</p>
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#translateModal">
                <i class="bi bi-plus me-1"></i> {{ __('translation.content_generator.create_first_translation') }}
            </button>
        </div>
    </div>
</div>

<!-- SEO Score Widget -->
@if($content->seo_overall_score)
<div class="seo-widget mt-3">
    <div class="text-center">
        <div class="seo-score-circle {{ $content->seo_overall_score >= 80 ? 'seo-score-high' : ($content->seo_overall_score >= 50 ? 'seo-score-medium' : 'seo-score-low') }}">
            {{ $content->seo_overall_score }}
        </div>
        <div class="fw-bold">{{ __('translation.content_generator.seo_score') }}</div>
        <small class="text-muted">{{ __('translation.content_generator.last_updated') }}: {{ $content->last_seo_check?->diffForHumans() ?? 'N/A' }}</small>
        <div class="mt-2">
            <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#seoAnalysisModal">
                <i class="bi bi-arrow-repeat me-1"></i> {{ __('translation.content_generator.re_analyze') }}
            </button>
        </div>
    </div>
</div>
@endif

<!-- Topic Card -->
<div class="card border-0 shadow-sm mt-3">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold">{{ __('translation.content_generator.original_topic') }}</h6>
    </div>
    <div class="card-body">
        <p class="mb-0 text-muted">{{ $content->input_data['topic'] ?? '-' }}</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="card border-0 shadow-sm mt-3">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-lightning-charge text-warning me-2"></i>{{ __('translation.content_generator.quick_actions') }}</h6>
    </div>
    <div class="card-body">
        <div class="d-grid gap-2">
            <a href="{{ route('content.index') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>{{ __('translation.content_generator.generate_another') }}
            </a>
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#aiRefinementModal">
                <i class="bi bi-magic me-2"></i>{{ __('translation.content_generator.ai_refine') }}
            </button>
        </div>
    </div>
</div>

<!-- Enhancement Tools -->
<div class="card border-0 shadow-sm mt-3">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-tools text-info me-2"></i>{{ __('translation.content_generator.enhancement_tools') }}</h6>
    </div>
    <div class="card-body">
        <div class="d-grid gap-2">
            <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#seoAnalysisModal">
                <i class="bi bi-graph-up me-2"></i>{{ __('translation.content_generator.seo_analysis') }}
            </button>
            <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#translateModal">
                <i class="bi bi-translate me-2"></i>{{ __('translation.content_generator.translate') }}
                @if(($content->translations ?? null) && count($content->translations) > 0)
                    <span class="badge bg-info ms-1">{{ count($content->translations) }}</span>
                @endif
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#saveTemplateModal">
                <i class="bi bi-file-earmark-plus me-2"></i>{{ __('translation.content_generator.save_template') }}
            </button>
            <a href="{{ route('templates.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-collection me-2"></i>{{ __('translation.content_generator.my_templates') }}
            </a>
            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#analyticsModal">
                <i class="bi bi-bar-chart-line me-2"></i>{{ __('translation.content_generator.analytics_page.title') }}
            </button>
            <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#scheduleModal">
                <i class="bi bi-calendar-check me-2"></i>{{ __('translation.content_generator.schedule') }}
            </button>
        </div>
    </div>
</div>

<!-- Team Collaboration -->
<div class="card border-0 shadow-sm mt-3">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-people-fill text-primary me-2"></i>{{ __('translation.content_generator.team_collaboration') }}</h6>
    </div>
    <div class="card-body">
        <div class="d-grid gap-2">
            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#teamCollaborationModal">
                <i class="bi bi-people me-2"></i>{{ __('translation.content_generator.my_teams') }}
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#contentCommentsModal">
                <i class="bi bi-chat-dots me-2"></i>{{ __('translation.content_generator.comments') }}
                <span class="badge bg-secondary ms-1 comments-count-badge" style="display: none;">0</span>
            </button>
            <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#assignContentModal">
                <i class="bi bi-person-plus me-2"></i>{{ __('translation.content_generator.assign') }}
            </button>
            <a href="{{ route('my-tasks') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-list-task me-2"></i>{{ __('translation.content_generator.my_tasks') }}
            </a>
        </div>
    </div>
</div>

