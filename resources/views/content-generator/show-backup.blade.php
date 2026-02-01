@extends('layout.home.main')

@section('title', __('translation.content_generator.result_title'))

@push('styles')
<style>
/* ============================================================== */
/* PROFESSIONAL ACTION CENTER STYLES */
/* ============================================================== */
.action-center {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}
.action-center-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
}
.action-group {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}
.action-group:last-child {
    margin-bottom: 0;
}
.action-divider {
    width: 100%;
    height: 1px;
    background: linear-gradient(90deg, transparent, #dee2e6, transparent);
    margin: 1rem 0;
}
.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1rem;
    border-radius: 10px;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}
.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.action-btn-primary {
    background: linear-gradient(135deg, #4A90D9, #357ABD);
    color: white;
}
.action-btn-ai {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}
.action-btn-seo {
    background: linear-gradient(135deg, #11998e, #38ef7d);
    color: white;
}
.action-btn-translate {
    background: linear-gradient(135deg, #f093fb, #f5576c);
    color: white;
}
.action-btn-template {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    color: white;
}
.action-btn-analytics {
    background: linear-gradient(135deg, #fa709a, #fee140);
    color: white;
}
.action-btn-calendar {
    background: linear-gradient(135deg, #a8edea, #fed6e3);
    color: #333;
}
.action-btn-team {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
}
.action-btn-comments {
    background: linear-gradient(135deg, #14b8a6, #06b6d4);
    color: white;
}
.action-btn-assign {
    background: linear-gradient(135deg, #f97316, #eab308);
    color: white;
}
.action-btn-outline {
    background: white;
    border: 2px solid #dee2e6;
    color: #495057;
}
.action-btn-outline:hover {
    border-color: #4A90D9;
    color: #4A90D9;
}

/* ============================================================== */
/* TRANSLATIONS PANEL STYLES */
/* ============================================================== */
.translations-panel {
    background: linear-gradient(135deg, #667eea10, #764ba210);
    border: 1px solid #667eea30;
    border-radius: 12px;
    padding: 1rem;
    margin-top: 1rem;
}
.translations-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}
.translations-panel-title {
    font-weight: 600;
    color: #667eea;
    font-size: 0.9rem;
}
.translation-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: white;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    border: 1px solid #e9ecef;
    transition: all 0.2s;
}
.translation-item:hover {
    border-color: #667eea;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
}
.translation-item:last-child {
    margin-bottom: 0;
}
.translation-lang {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.translation-flag {
    font-size: 1.25rem;
}
.translation-name {
    font-weight: 500;
    color: #333;
}
.translation-quality {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 20px;
    background: #e8f5e9;
    color: #2e7d32;
}
.translation-actions {
    display: flex;
    gap: 0.25rem;
}
.translation-btn {
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    border: none;
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.2s;
}
.translation-btn-view {
    background: #667eea;
    color: white;
}
.translation-btn-copy {
    background: #e9ecef;
    color: #495057;
}
.translation-btn:hover {
    transform: scale(1.05);
}
.no-translations {
    text-align: center;
    padding: 1.5rem;
    color: #6c757d;
}
.no-translations i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

/* ============================================================== */
/* SEO SCORE WIDGET STYLES */
/* ============================================================== */
.seo-widget {
    background: linear-gradient(135deg, #11998e10, #38ef7d10);
    border: 1px solid #11998e30;
    border-radius: 12px;
    padding: 1rem;
    margin-top: 1rem;
}
.seo-score-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.25rem;
    margin: 0 auto 0.5rem;
}
.seo-score-high { background: linear-gradient(135deg, #11998e, #38ef7d); color: white; }
.seo-score-medium { background: linear-gradient(135deg, #f093fb, #f5576c); color: white; }
.seo-score-low { background: linear-gradient(135deg, #eb3349, #f45c43); color: white; }

/* ============================================================== */
/* FLOATING ACTION BUTTON */
/* ============================================================== */
.fab-container {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    z-index: 1000;
}
.fab-main {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.fab-main:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 30px rgba(102, 126, 234, 0.5);
}
.fab-menu {
    position: absolute;
    bottom: 70px;
    right: 0;
    display: none;
    flex-direction: column;
    gap: 0.5rem;
}
.fab-container.active .fab-menu {
    display: flex;
}
.fab-container.active .fab-main {
    transform: rotate(45deg);
}
.fab-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: white;
    border-radius: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s;
    text-decoration: none;
    color: #333;
    font-size: 0.875rem;
}
.fab-item:hover {
    transform: translateX(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}
.fab-item i {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    color: white;
}
.fab-item-ai i { background: linear-gradient(135deg, #667eea, #764ba2); }
.fab-item-seo i { background: linear-gradient(135deg, #11998e, #38ef7d); }
.fab-item-translate i { background: linear-gradient(135deg, #f093fb, #f5576c); }
.fab-item-template i { background: linear-gradient(135deg, #4facfe, #00f2fe); }
.fab-item-analytics i { background: linear-gradient(135deg, #fa709a, #fee140); }

/* ============================================================== */
/* QUICK STATS BAR */
/* ============================================================== */
.quick-stats {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}
.quick-stat {
    flex: 1;
    min-width: 120px;
    background: white;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    border: 1px solid #e9ecef;
    text-align: center;
}
.quick-stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #4A90D9;
}
.quick-stat-label {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
}
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('content.index') }}">{{ __('translation.content_generator.title') }}</a></li>
            <li class="breadcrumb-item active">{{ __('translation.content_generator.result_title') }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Content Column -->
        <div class="col-lg-8">
            <!-- ============================================================== -->
            <!-- PROFESSIONAL ACTION CENTER -->
            <!-- ============================================================== -->
            <div class="action-center">
                <!-- Export & History -->
                <div class="action-center-title">
                    <i class="bi bi-box-arrow-up me-1"></i> Export & History
                </div>
                <div class="action-group">
                    <button type="button" class="action-btn action-btn-outline" onclick="copyContent()">
                        <i class="bi bi-clipboard"></i>
                        {{ __('translation.content_generator.copy') }}
                    </button>
                    <button type="button" class="action-btn action-btn-outline" onclick="downloadContent()">
                        <i class="bi bi-download"></i>
                        {{ __('translation.content_generator.download') }}
                    </button>
                    <div class="btn-group">
                        <button type="button" class="action-btn action-btn-outline dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-file-earmark-pdf text-danger"></i>
                            {{ __('translation.content_generator.export_pdf') }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('content.export.pdf', $content->id) }}?format=a4&orientation=portrait">
                                <i class="bi bi-file-earmark-pdf text-danger me-2"></i>A4 {{ __('translation.content_generator.portrait') }}
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('content.export.pdf', $content->id) }}?format=a4&orientation=landscape">
                                <i class="bi bi-file-earmark-pdf text-danger me-2"></i>A4 {{ __('translation.content_generator.landscape') }}
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('content.export.pdf', $content->id) }}?action=stream" target="_blank">
                                <i class="bi bi-eye text-primary me-2"></i>{{ __('translation.content_generator.preview_pdf') }}
                            </a></li>
                        </ul>
                    </div>
                    <button type="button" class="action-btn action-btn-pptx" style="background: linear-gradient(135deg, #D35230, #B7472A); color: white; border: none;" data-bs-toggle="modal" data-bs-target="#pptxThemeModal" title="{{ __('translation.content_generator.export_pptx_desc') }}">
                        <i class="bi bi-file-earmark-ppt"></i>
                        {{ __('translation.content_generator.export_pptx') }}
                    </button>
                    <a href="{{ route('content.history') }}" class="action-btn action-btn-outline">
                        <i class="bi bi-clock-history"></i>
                        {{ __('translation.content_generator.view_history') }}
                    </a>
                    @if($content->version > 1 || $content->childVersions()->count() > 0)
                    <button type="button" class="action-btn action-btn-outline" data-bs-toggle="modal" data-bs-target="#versionHistoryModal">
                        <i class="bi bi-git"></i>
                        Version {{ $content->version }}
                    </button>
                    @endif
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- CONTENT CARD -->
            <!-- ============================================================== -->
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
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="quick-stats">
                <div class="quick-stat">
                    <div class="quick-stat-value">{{ $content->word_count ?? str_word_count($content->output_text ?? '') }}</div>
                    <div class="quick-stat-label">Words</div>
                </div>
                <div class="quick-stat">
                    <div class="quick-stat-value">{{ $content->credits_used ?? 1 }}</div>
                    <div class="quick-stat-label">Credits</div>
                </div>
                <div class="quick-stat">
                    <div class="quick-stat-value">{{ count($content->translations ?? []) }}</div>
                    <div class="quick-stat-label">Translations</div>
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

            <!-- ============================================================== -->
            <!-- TRANSLATIONS PANEL - ALWAYS VISIBLE -->
            <!-- ============================================================== -->
            <div class="translations-panel" id="translationsPanelSidebar">
                <div class="translations-panel-header">
                    <span class="translations-panel-title">
                        <i class="bi bi-translate me-1"></i> Translations
                    </span>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#translateModal">
                        <i class="bi bi-plus"></i> Add
                    </button>
                </div>
                <div id="sidebarTranslationsList">
                    <!-- Will be populated by JavaScript -->
                    <div class="no-translations" id="noTranslationsMessage">
                        <i class="bi bi-translate d-block"></i>
                        <p class="mb-2">No translations yet</p>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#translateModal">
                            <i class="bi bi-plus me-1"></i> Create First Translation
                        </button>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- SEO SCORE WIDGET -->
            <!-- ============================================================== -->
            @if($content->seo_overall_score)
            <div class="seo-widget mt-3">
                <div class="text-center">
                    <div class="seo-score-circle {{ $content->seo_overall_score >= 80 ? 'seo-score-high' : ($content->seo_overall_score >= 50 ? 'seo-score-medium' : 'seo-score-low') }}">
                        {{ $content->seo_overall_score }}
                    </div>
                    <div class="fw-bold">SEO Score</div>
                    <small class="text-muted">Last checked: {{ $content->last_seo_check?->diffForHumans() ?? 'N/A' }}</small>
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#seoAnalysisModal">
                            <i class="bi bi-arrow-repeat me-1"></i> Re-analyze
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
                    <h6 class="mb-0 fw-bold"><i class="bi bi-lightning-charge text-warning me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('content.index') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>{{ __('translation.content_generator.generate_another') }}
                        </a>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#aiRefinementModal">
                            <i class="bi bi-magic me-2"></i>AI Refine
                        </button>
                    </div>
                </div>
            </div>

            <!-- Enhancement Tools -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-tools text-info me-2"></i>Enhancement Tools</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#seoAnalysisModal">
                            <i class="bi bi-graph-up me-2"></i>SEO Analysis
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#translateModal">
                            <i class="bi bi-translate me-2"></i>Translate
                            @if(($content->translations ?? null) && count($content->translations) > 0)
                                <span class="badge bg-info ms-1">{{ count($content->translations) }}</span>
                            @endif
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#saveTemplateModal">
                            <i class="bi bi-file-earmark-plus me-2"></i>Save Template
                        </button>
                        <a href="{{ route('templates.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-collection me-2"></i>My Templates
                        </a>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#analyticsModal">
                            <i class="bi bi-bar-chart-line me-2"></i>Analytics
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#scheduleModal">
                            <i class="bi bi-calendar-check me-2"></i>Schedule
                        </button>
                    </div>
                </div>
            </div>

            <!-- Team Collaboration -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-people-fill text-primary me-2"></i>Team Collaboration</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#teamCollaborationModal">
                            <i class="bi bi-people me-2"></i>My Teams
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#contentCommentsModal">
                            <i class="bi bi-chat-dots me-2"></i>Comments
                            <span class="badge bg-secondary ms-1 comments-count-badge" style="display: none;">0</span>
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#assignContentModal">
                            <i class="bi bi-person-plus me-2"></i>Assign
                        </button>
                        <a href="{{ route('my-tasks') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-list-task me-2"></i>My Tasks
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- FLOATING ACTION BUTTON -->
<!-- ============================================================== -->
<div class="fab-container" id="fabContainer">
    <div class="fab-menu">
        <a class="fab-item fab-item-ai" data-bs-toggle="modal" data-bs-target="#aiRefinementModal">
            <i class="bi bi-magic"></i>
            <span>AI Refine</span>
        </a>
        <a class="fab-item fab-item-seo" data-bs-toggle="modal" data-bs-target="#seoAnalysisModal">
            <i class="bi bi-graph-up"></i>
            <span>SEO Analysis</span>
        </a>
        <a class="fab-item fab-item-translate" data-bs-toggle="modal" data-bs-target="#translateModal">
            <i class="bi bi-translate"></i>
            <span>Translate</span>
        </a>
        <a class="fab-item fab-item-template" data-bs-toggle="modal" data-bs-target="#saveTemplateModal">
            <i class="bi bi-file-earmark-plus"></i>
            <span>Save Template</span>
        </a>
        <a class="fab-item fab-item-analytics" data-bs-toggle="modal" data-bs-target="#analyticsModal">
            <i class="bi bi-bar-chart-line"></i>
            <span>Analytics</span>
        </a>
    </div>
    <button class="fab-main" onclick="toggleFab()">
        <i class="bi bi-plus-lg"></i>
    </button>
</div>

<!-- Social Media Preview Modal -->
<div class="modal fade" id="socialPreviewModal" tabindex="-1" aria-labelledby="socialPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="socialPreviewModalLabel">
                    <i class="bi bi-share me-2"></i>{{ __('translation.content_generator.social_preview') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Platform Tabs -->
                <ul class="nav nav-pills mb-4 justify-content-center flex-wrap gap-2" id="platformTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="facebook-tab" data-bs-toggle="pill" data-bs-target="#facebook-preview" 
                                type="button" role="tab" onclick="loadSocialPreview('facebook')" style="background: transparent; color: #1877F2; border: 2px solid #1877F2;">
                            <i class="bi bi-facebook me-2"></i>Facebook
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="twitter-tab" data-bs-toggle="pill" data-bs-target="#twitter-preview" 
                                type="button" role="tab" onclick="loadSocialPreview('twitter')" style="background: transparent; color: #000; border: 2px solid #000;">
                            <i class="bi bi-twitter-x me-2"></i>X
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="linkedin-tab" data-bs-toggle="pill" data-bs-target="#linkedin-preview" 
                                type="button" role="tab" onclick="loadSocialPreview('linkedin')" style="background: transparent; color: #0A66C2; border: 2px solid #0A66C2;">
                            <i class="bi bi-linkedin me-2"></i>LinkedIn
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="instagram-tab" data-bs-toggle="pill" data-bs-target="#instagram-preview" 
                                type="button" role="tab" onclick="loadSocialPreview('instagram')" style="background: transparent; color: #E4405F; border: 2px solid #E4405F;">
                            <i class="bi bi-instagram me-2"></i>Instagram
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tiktok-tab" data-bs-toggle="pill" data-bs-target="#tiktok-preview" 
                                type="button" role="tab" onclick="loadSocialPreview('tiktok')" style="background: transparent; color: #000; border: 2px solid #000;">
                            <svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/></svg>TikTok
                        </button>
                    </li>
                </ul>

                <!-- Preview Content -->
                <div class="tab-content" id="platformTabsContent">
                    <div id="preview-loading" class="text-center py-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2">{{ __('translation.content_generator.loading_preview') }}</p>
                    </div>
                    <div id="preview-content"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>{{ __('translation.common.close') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentPreviewData = {};

/**
 * Copy content to clipboard - uses centralized ContentManager
 */
function copyContent() {
    if (typeof ContentManager !== 'undefined') {
        ContentManager.copyContent('content-output', '{{ __("translation.content_generator.copied_success") }}');
    } else {
        const content = document.getElementById('content-output').innerText;
        navigator.clipboard.writeText(content).then(() => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'success', title: '{{ __("translation.content_generator.copied_success") }}', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true, background: '#d1e7dd', iconColor: '#0f5132' });
            }
        });
    }
}

/**
 * Download content as text file - uses centralized ContentManager
 */
function downloadContent() {
    if (typeof ContentManager !== 'undefined') {
        ContentManager.downloadContent('content-output', 'generated-content-{{ $content->id }}.txt');
    } else {
        // Fallback implementation
        const content = document.getElementById('content-output').innerText;
        const blob = new Blob([content], { type: 'text/plain' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'generated-content-{{ $content->id }}.txt';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
    }
}

/**
 * Toggle favorite status - uses centralized ContentManager with fallback
 */
function toggleFavorite(button) {
    const toggleUrl = "{{ route('content.toggle.favorite', ['id' => ':id']) }}".replace(':id', button.dataset.contentId);
    const messages = {
        favorite: '{{ __("translation.content_generator.add_favorite") }}',
        unfavorite: '{{ __("translation.content_generator.favorited") }}',
        error: '{{ __("translation.content_generator.chat.error_occurred") }}'
    };
    
    if (typeof ContentManager !== 'undefined') {
        ContentManager.toggleFavorite(button, toggleUrl, messages);
    } else {
        // Fallback implementation
        const contentId = button.dataset.contentId;
        button.disabled = true;
        button.style.opacity = '0.7';
        
        const icon = button.querySelector('i');
        const text = button.querySelector('.favorite-text');
        const originalIcon = icon.className;
        const originalText = text.textContent;
        
        icon.className = 'bi bi-hourglass-split me-1';
        
        fetch(toggleUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                button.dataset.isFavorited = data.is_favorited;
                icon.className = data.is_favorited ? 'bi bi-star-fill me-1 text-warning' : 'bi bi-star me-1';
                text.textContent = data.message;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'success', title: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
                }
            } else {
                icon.className = originalIcon;
                text.textContent = originalText;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: data.message || messages.error, toast: true, position: 'top-end', timer: 3000 });
                }
            }
        })
        .catch(error => {
            icon.className = originalIcon;
            text.textContent = originalText;
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: messages.error, toast: true, position: 'top-end', timer: 3000 });
            }
        })
        .finally(() => {
            button.disabled = false;
            button.style.opacity = '1';
        });
    }
}

// Social Media Translation strings for JavaScript
const socialMediaTranslations = {
    // Common
    medicalProfessional: @json(__('translation.content_generator.social_media.medical_professional')),
    healthcareExpert: @json(__('translation.content_generator.social_media.healthcare_expert')),
    medicalContentCreator: @json(__('translation.content_generator.social_media.medical_content_creator')),
    justNow: @json(__('translation.content_generator.social_media.just_now')),
    seeMore: @json(__('translation.content_generator.social_media.see_more')),
    seeLess: @json(__('translation.content_generator.social_media.see_less')),
    more: @json(__('translation.content_generator.social_media.more')),
    follow: @json(__('translation.content_generator.social_media.follow')),
    characterCount: @json(__('translation.content_generator.social_media.character_count')),
    characters: @json(__('translation.content_generator.social_media.characters')),
    aboveRecommended: @json(__('translation.content_generator.social_media.above_recommended')),
    optimalLength: @json(__('translation.content_generator.social_media.optimal_length')),
    bestPractices: @json(__('translation.content_generator.social_media.best_practices')),
    
    // Facebook
    fbLike: @json(__('translation.content_generator.social_media.facebook.like')),
    fbComment: @json(__('translation.content_generator.social_media.facebook.comment')),
    fbShare: @json(__('translation.content_generator.social_media.facebook.share')),
    fbComments: @json(__('translation.content_generator.social_media.facebook.comments')),
    fbShares: @json(__('translation.content_generator.social_media.facebook.shares')),
    
    // Twitter/X
    xNow: @json(__('translation.content_generator.social_media.twitter.now')),
    xReply: @json(__('translation.content_generator.social_media.twitter.reply')),
    xRepost: @json(__('translation.content_generator.social_media.twitter.repost')),
    xReposts: @json(__('translation.content_generator.social_media.twitter.reposts')),
    xLike: @json(__('translation.content_generator.social_media.twitter.like')),
    xLikes: @json(__('translation.content_generator.social_media.twitter.likes')),
    xViews: @json(__('translation.content_generator.social_media.twitter.views')),
    xQuotes: @json(__('translation.content_generator.social_media.twitter.quotes')),
    xBookmarks: @json(__('translation.content_generator.social_media.twitter.bookmarks')),
    xThreadSuggestion: @json(__('translation.content_generator.social_media.twitter.thread_suggestion')),
    xContentTooLong: @json(__('translation.content_generator.social_media.twitter.content_too_long')),
    
    // LinkedIn
    liFollow: @json(__('translation.content_generator.social_media.linkedin.follow')),
    liHealthcareExpert: @json(__('translation.content_generator.social_media.linkedin.healthcare_expert')),
    liTimeAgo: @json(__('translation.content_generator.social_media.linkedin.time_ago')),
    liSeeMore: @json(__('translation.content_generator.social_media.linkedin.see_more')),
    liSeeLess: @json(__('translation.content_generator.social_media.linkedin.see_less')),
    liLike: @json(__('translation.content_generator.social_media.linkedin.like')),
    liComment: @json(__('translation.content_generator.social_media.linkedin.comment')),
    liRepost: @json(__('translation.content_generator.social_media.linkedin.repost')),
    liSend: @json(__('translation.content_generator.social_media.linkedin.send')),
    liReactions: @json(__('translation.content_generator.social_media.linkedin.reactions')),
    liComments: @json(__('translation.content_generator.social_media.linkedin.comments')),
    liReposts: @json(__('translation.content_generator.social_media.linkedin.reposts')),
    
    // Instagram
    igLikes: @json(__('translation.content_generator.social_media.instagram.likes')),
    igMore: @json(__('translation.content_generator.social_media.instagram.more')),
    igMoreTags: @json(__('translation.content_generator.social_media.instagram.more_tags')),
    igViewAllComments: @json(__('translation.content_generator.social_media.instagram.view_all_comments')),
    igViewComments: @json(__('translation.content_generator.social_media.instagram.view_comments')),
    igAddComment: @json(__('translation.content_generator.social_media.instagram.add_comment')),
    igPost: @json(__('translation.content_generator.social_media.instagram.post')),
    igTimeAgo: @json(__('translation.content_generator.social_media.instagram.time_ago')),
    igOriginalAudio: @json(__('translation.content_generator.social_media.instagram.original_audio')),
    igAddImage: @json(__('translation.content_generator.social_media.instagram.add_image')),
    igAddImageHere: @json(__('translation.content_generator.social_media.instagram.add_image_here')),
    
    // TikTok
    ttShare: @json(__('translation.content_generator.social_media.tiktok.share')),
    ttYourVideo: @json(__('translation.content_generator.social_media.tiktok.your_video')),
    ttYourVideoHere: @json(__('translation.content_generator.social_media.tiktok.your_video_here')),
    ttOriginalSound: @json(__('translation.content_generator.social_media.tiktok.original_sound')),
    
    // Statistics
    characterCount: @json(__('translation.content_generator.social_media.character_count')),
    characters: @json(__('translation.content_generator.social_media.characters')),
    chars: @json(__('translation.content_generator.social_media.chars')),
    aboveRecommended: @json(__('translation.content_generator.social_media.above_recommended')),
    optimalLength: @json(__('translation.content_generator.social_media.optimal_length')),
    threadSuggestion: @json(__('translation.content_generator.social_media.thread_suggestion')),
    contentTooLong: @json(__('translation.content_generator.social_media.content_too_long')),
    bestPractices: @json(__('translation.content_generator.social_media.best_practices')),
    copyContent: @json(__('translation.content_generator.social_media.copy_content')),
};

/**
 * Load social media preview - uses centralized SocialManager
 */
function loadSocialPreview(platform) {
    const loading = document.getElementById('preview-loading');
    const content = document.getElementById('preview-content');
    
    loading.style.display = 'block';
    content.innerHTML = '';
    
    fetch(`{{ route('content.social.preview', $content->id) }}?platform=${platform}`)
        .then(response => response.json())
        .then(data => {
            loading.style.display = 'none';
            if (data.success) {
                currentPreviewData[platform] = data.preview;
                renderPreview(data.preview);
            } else {
                content.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            content.innerHTML = '<div class="alert alert-danger">{{ __("translation.content_generator.chat.error_occurred") }}</div>';
            console.error('Error:', error);
        });
}

function renderPreview(preview) {
    const content = document.getElementById('preview-content');
    const t = socialMediaTranslations; // shorthand
    
    // RTL support
    const direction = preview.direction || 'ltr';
    const textAlign = preview.text_align || 'left';
    const isRtl = direction === 'rtl';
    
    // Platform-specific realistic mockup designs - matching real platforms exactly
    let html = '';
    
    if (preview.platform === 'facebook') {
        // Facebook: Full text visible with "See more" for very long posts
        const fbMaxVisible = 500;
        const fbText = preview.text || '';
        const fbShowMore = fbText.length > fbMaxVisible;
        const fbVisibleText = fbShowMore ? fbText.substring(0, fbMaxVisible) : fbText;
        
        html = `
            <div class="facebook-mockup" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; direction: ${direction};">
                <!-- Facebook Post Card - 2024 Design -->
                <div class="card border-0 shadow" style="max-width: 680px; margin: 0 auto; border-radius: 8px; background: #fff;">
                    <div class="card-body p-0">
                        <!-- Post Header -->
                        <div class="d-flex align-items-center p-3">
                            <div class="position-relative">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px; background: linear-gradient(135deg, #1877F2, #42B72A); color: white;">
                                    <i class="bi bi-hospital-fill" style="font-size: 18px;"></i>
                                </div>
                            </div>
                            <div class="${isRtl ? 'me-3' : 'ms-3'} flex-grow-1">
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold" style="font-size: 15px; color: #050505;">${t.medicalProfessional}</span>
                                    <i class="bi bi-patch-check-fill ${isRtl ? 'me-1' : 'ms-1'}" style="color: #1877F2; font-size: 14px;"></i>
                                </div>
                                <div class="d-flex align-items-center text-muted" style="font-size: 13px;">
                                    <span>${t.justNow}</span>
                                    <span class="mx-1">·</span>
                                    <i class="bi bi-globe2" style="font-size: 11px;"></i>
                                </div>
                            </div>
                            <button class="btn btn-link text-muted p-2" style="font-size: 20px;">
                                <i class="bi bi-three-dots"></i>
                            </button>
                        </div>
                        
                        <!-- Post Content -->
                        <div class="px-3 pb-3" style="font-size: 15px; line-height: 1.5; color: #050505; direction: ${direction}; text-align: ${textAlign};">
                            ${preview.headline ? `<div class="fw-bold mb-2" style="font-size: 16px;">${preview.headline}</div>` : ''}
                            <div id="fb-post-text">
                                <span id="fb-visible-text">${fbVisibleText.replace(/\n/g, '<br>')}</span>${fbShowMore ? '...' : ''}
                                ${fbShowMore ? `
                                    <span id="fb-hidden-text" style="display: none;">${fbText.substring(fbMaxVisible).replace(/\n/g, '<br>')}</span>
                                    <span class="text-primary fw-semibold" style="cursor: pointer;" onclick="toggleFbText(this)">${t.seeMore}</span>
                                ` : ''}
                            </div>
                        </div>
                        
                        <!-- Hashtags -->
                        ${preview.hashtags && preview.hashtags.length > 0 ? `
                            <div class="px-3 pb-3">
                                ${preview.hashtags.map(tag => `<a href="#" class="text-decoration-none me-1" style="color: #1877F2; font-size: 15px;">${tag}</a>`).join('')}
                            </div>
                        ` : ''}
                        
                        <!-- Engagement Stats -->
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top text-muted" style="font-size: 15px;">
                            <div class="d-flex align-items-center">
                                <div class="d-flex" style="margin-${isRtl ? 'left' : 'right'}: 8px;">
                                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width: 18px; height: 18px; background: #1877F2; margin-${isRtl ? 'left' : 'right'}: -4px; border: 2px solid white; z-index: 3;">
                                        <i class="bi bi-hand-thumbs-up-fill text-white" style="font-size: 10px;"></i>
                                    </span>
                                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width: 18px; height: 18px; background: #F0284A; margin-${isRtl ? 'left' : 'right'}: -4px; border: 2px solid white; z-index: 2;">
                                        <i class="bi bi-heart-fill text-white" style="font-size: 10px;"></i>
                                    </span>
                                </div>
                                <span style="color: #65676B;">128</span>
                            </div>
                            <span style="color: #65676B;">24 ${t.fbComments} · 12 ${t.fbShares}</span>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-around px-2 py-1 border-top">
                            <button class="btn btn-light flex-fill mx-1 py-2 d-flex align-items-center justify-content-center rounded-2" style="border: none; font-size: 15px; font-weight: 600; color: #65676B; background: transparent;">
                                <i class="bi bi-hand-thumbs-up ${isRtl ? 'ms-2' : 'me-2'}" style="font-size: 18px;"></i>${t.fbLike}
                            </button>
                            <button class="btn btn-light flex-fill mx-1 py-2 d-flex align-items-center justify-content-center rounded-2" style="border: none; font-size: 15px; font-weight: 600; color: #65676B; background: transparent;">
                                <i class="bi bi-chat-left ${isRtl ? 'ms-2' : 'me-2'}" style="font-size: 18px;"></i>${t.fbComment}
                            </button>
                            <button class="btn btn-light flex-fill mx-1 py-2 d-flex align-items-center justify-content-center rounded-2" style="border: none; font-size: 15px; font-weight: 600; color: #65676B; background: transparent;">
                                <i class="bi bi-share ${isRtl ? 'ms-2' : 'me-2'}" style="font-size: 18px;"></i>${t.fbShare}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else if (preview.platform === 'twitter') {
        // Twitter/X: Full text visible (up to 280 chars or full for Premium)
        const xText = preview.text || '';
        
        html = `
            <div class="twitter-mockup" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; direction: ${direction}; background: #000;">
                <div class="card border-0" style="max-width: 598px; margin: 0 auto; border-radius: 0; background: #000; border-bottom: 1px solid #2F3336;">
                    <div class="card-body p-3">
                        <div class="d-flex">
                            <!-- Avatar -->
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px; background: linear-gradient(135deg, #1DA1F2, #14171A); flex-shrink: 0;">
                                <i class="bi bi-hospital-fill text-white" style="font-size: 18px;"></i>
                            </div>
                            <div class="${isRtl ? 'me-3' : 'ms-3'} flex-grow-1">
                                <!-- Header -->
                                <div class="d-flex align-items-center flex-wrap mb-1">
                                    <span class="fw-bold text-white" style="font-size: 15px;">${t.medicalProfessional}</span>
                                    <i class="bi bi-patch-check-fill mx-1" style="color: #1D9BF0; font-size: 16px;"></i>
                                    <span style="color: #71767B; font-size: 15px;">@MedicalPro</span>
                                    <span style="color: #71767B; font-size: 15px;" class="mx-1">·</span>
                                    <span style="color: #71767B; font-size: 15px;">${t.xNow}</span>
                                    <button class="btn btn-link p-0 ${isRtl ? 'me-auto' : 'ms-auto'}" style="color: #71767B;">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                </div>
                                
                                <!-- Tweet Content - FULL TEXT -->
                                <div class="mb-3" style="font-size: 15px; line-height: 1.5; color: #E7E9EA; direction: ${direction}; text-align: ${textAlign}; white-space: pre-wrap; word-wrap: break-word;">
                                    ${xText.replace(/\n/g, '<br>')}
                                </div>
                                
                                <!-- Hashtags -->
                                ${preview.hashtags && preview.hashtags.length > 0 ? `
                                    <div class="mb-3">
                                        ${preview.hashtags.map(tag => `<a href="#" class="text-decoration-none me-2" style="color: #1D9BF0; font-size: 15px;">${tag}</a>`).join('')}
                                    </div>
                                ` : ''}
                                
                                <!-- Time & Source -->
                                <div class="mb-3 pb-3 border-bottom" style="border-color: #2F3336 !important;">
                                    <span style="color: #71767B; font-size: 15px;">12:30 PM · ${new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                                    <span style="color: #71767B; font-size: 15px;"> · </span>
                                    <span style="color: #71767B; font-size: 15px;"><span class="fw-bold text-white">1.2M</span> ${t.xViews}</span>
                                </div>
                                
                                <!-- Engagement Stats -->
                                <div class="d-flex gap-4 mb-3 pb-3 border-bottom" style="border-color: #2F3336 !important;">
                                    <span style="color: #71767B; font-size: 14px;"><span class="fw-bold text-white">245</span> ${t.xReposts}</span>
                                    <span style="color: #71767B; font-size: 14px;"><span class="fw-bold text-white">89</span> ${t.xQuotes}</span>
                                    <span style="color: #71767B; font-size: 14px;"><span class="fw-bold text-white">1.5K</span> ${t.xLikes}</span>
                                    <span style="color: #71767B; font-size: 14px;"><span class="fw-bold text-white">12</span> ${t.xBookmarks}</span>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="d-flex justify-content-between" style="max-width: 425px;">
                                    <button class="btn btn-link p-2 rounded-circle" style="color: #71767B;">
                                        <i class="bi bi-chat" style="font-size: 18px;"></i>
                                    </button>
                                    <button class="btn btn-link p-2 rounded-circle" style="color: #71767B;">
                                        <i class="bi bi-repeat" style="font-size: 18px;"></i>
                                    </button>
                                    <button class="btn btn-link p-2 rounded-circle" style="color: #71767B;">
                                        <i class="bi bi-heart" style="font-size: 18px;"></i>
                                    </button>
                                    <button class="btn btn-link p-2 rounded-circle" style="color: #71767B;">
                                        <i class="bi bi-bar-chart" style="font-size: 18px;"></i>
                                    </button>
                                    <button class="btn btn-link p-2 rounded-circle" style="color: #71767B;">
                                        <i class="bi bi-bookmark" style="font-size: 18px;"></i>
                                    </button>
                                    <button class="btn btn-link p-2 rounded-circle" style="color: #71767B;">
                                        <i class="bi bi-upload" style="font-size: 18px;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Character Count Badge -->
                <div class="text-center py-2" style="background: #16181C;">
                    <span class="badge ${preview.current_length > preview.max_length ? 'bg-danger' : preview.current_length > preview.recommended_length ? 'bg-warning' : 'bg-success'}" style="font-size: 13px; padding: 8px 16px;">
                        <i class="bi bi-fonts ${isRtl ? 'ms-2' : 'me-2'}"></i>${preview.current_length} / ${preview.max_length} ${t.characters}
                    </span>
                </div>
            </div>
        `;
    } else if (preview.platform === 'linkedin') {
        // LinkedIn: Full text with "...see more" for posts over 210 chars (first 3 lines)
        const liMaxVisible = 500;
        const liText = preview.text || '';
        const liShowMore = liText.length > liMaxVisible;
        const liVisibleText = liShowMore ? liText.substring(0, liMaxVisible) : liText;
        
        html = `
            <div class="linkedin-mockup" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; direction: ${direction}; background: #F4F2EE;">
                <div class="card border-0 shadow-sm" style="max-width: 552px; margin: 0 auto; border-radius: 8px; background: #fff;">
                    <div class="card-body p-0">
                        <!-- Header -->
                        <div class="d-flex align-items-start p-3 pb-0">
                            <div class="position-relative">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 48px; height: 48px; background: linear-gradient(135deg, #0A66C2, #004182);">
                                    <i class="bi bi-hospital-fill text-white" style="font-size: 20px;"></i>
                                </div>
                                <span class="position-absolute bg-success rounded-circle border border-2 border-white" 
                                      style="width: 14px; height: 14px; bottom: 0; ${isRtl ? 'left' : 'right'}: 0;"></span>
                            </div>
                            <div class="${isRtl ? 'me-2' : 'ms-2'} flex-grow-1">
                                <div class="d-flex align-items-center">
                                    <span class="fw-semibold" style="font-size: 14px; color: rgba(0,0,0,0.9);">${t.medicalProfessional}</span>
                                    <span class="mx-1" style="color: rgba(0,0,0,0.6);">•</span>
                                    <span style="color: #0A66C2; font-size: 14px; font-weight: 600;">${t.liFollow}</span>
                                </div>
                                <div style="font-size: 12px; color: rgba(0,0,0,0.6); line-height: 1.3;">${t.liHealthcareExpert}</div>
                                <div class="d-flex align-items-center" style="font-size: 12px; color: rgba(0,0,0,0.6);">
                                    <span>${t.liTimeAgo}</span>
                                    <span class="mx-1">•</span>
                                    <i class="bi bi-globe2" style="font-size: 11px;"></i>
                                </div>
                            </div>
                            <div class="d-flex">
                                <button class="btn btn-link p-1" style="color: rgba(0,0,0,0.6);"><i class="bi bi-three-dots"></i></button>
                                <button class="btn btn-link p-1" style="color: rgba(0,0,0,0.6);"><i class="bi bi-x-lg"></i></button>
                            </div>
                        </div>
                        
                        <!-- Post Content - FULL TEXT with see more -->
                        <div class="px-3 pt-2 pb-3" style="font-size: 14px; line-height: 1.5; color: rgba(0,0,0,0.9); direction: ${direction}; text-align: ${textAlign};">
                            ${preview.headline ? `<div class="fw-semibold mb-2" style="font-size: 16px;">${preview.headline}</div>` : ''}
                            <div id="li-post-text">
                                <span id="li-visible-text">${liVisibleText.replace(/\n/g, '<br>')}</span>${liShowMore ? '...' : ''}
                                ${liShowMore ? `
                                    <span id="li-hidden-text" style="display: none;">${liText.substring(liMaxVisible).replace(/\n/g, '<br>')}</span>
                                    <span class="fw-semibold" style="color: rgba(0,0,0,0.6); cursor: pointer;" onclick="toggleLiText(this)">${t.liSeeMore}</span>
                                ` : ''}
                            </div>
                        </div>
                        
                        <!-- Hashtags -->
                        ${preview.hashtags && preview.hashtags.length > 0 ? `
                            <div class="px-3 pb-3">
                                ${preview.hashtags.map(tag => `<a href="#" class="text-decoration-none me-1" style="color: #0A66C2; font-size: 14px; font-weight: 600;">${tag}</a>`).join('')}
                            </div>
                        ` : ''}
                        
                        <!-- Engagement Stats -->
                        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-top" style="font-size: 12px; color: rgba(0,0,0,0.6);">
                            <div class="d-flex align-items-center">
                                <div class="d-flex">
                                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width: 16px; height: 16px; background: #0A66C2; margin-${isRtl ? 'left' : 'right'}: -3px; border: 1px solid white; z-index: 3;">
                                        <i class="bi bi-hand-thumbs-up-fill text-white" style="font-size: 9px;"></i>
                                    </span>
                                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width: 16px; height: 16px; background: #E16745; margin-${isRtl ? 'left' : 'right'}: -3px; border: 1px solid white; z-index: 2;">
                                        <i class="bi bi-heart-fill text-white" style="font-size: 9px;"></i>
                                    </span>
                                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width: 16px; height: 16px; background: #6DAE4F; border: 1px solid white; z-index: 1;">
                                        <i class="bi bi-emoji-smile-fill text-white" style="font-size: 9px;"></i>
                                    </span>
                                </div>
                                <span class="${isRtl ? 'me-2' : 'ms-2'}">1,247</span>
                            </div>
                            <span>89 ${t.liComments} • 34 ${t.liReposts}</span>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-around px-2 py-1 border-top">
                            <button class="btn btn-light flex-fill mx-1 py-2 d-flex align-items-center justify-content-center rounded-1" style="border: none; font-size: 14px; font-weight: 600; color: rgba(0,0,0,0.6); background: transparent;">
                                <i class="bi bi-hand-thumbs-up ${isRtl ? 'ms-2' : 'me-2'}" style="font-size: 18px;"></i>${t.liLike}
                            </button>
                            <button class="btn btn-light flex-fill mx-1 py-2 d-flex align-items-center justify-content-center rounded-1" style="border: none; font-size: 14px; font-weight: 600; color: rgba(0,0,0,0.6); background: transparent;">
                                <i class="bi bi-chat-left-text ${isRtl ? 'ms-2' : 'me-2'}" style="font-size: 18px;"></i>${t.liComment}
                            </button>
                            <button class="btn btn-light flex-fill mx-1 py-2 d-flex align-items-center justify-content-center rounded-1" style="border: none; font-size: 14px; font-weight: 600; color: rgba(0,0,0,0.6); background: transparent;">
                                <i class="bi bi-repeat ${isRtl ? 'ms-2' : 'me-2'}" style="font-size: 18px;"></i>${t.liRepost}
                            </button>
                            <button class="btn btn-light flex-fill mx-1 py-2 d-flex align-items-center justify-content-center rounded-1" style="border: none; font-size: 14px; font-weight: 600; color: rgba(0,0,0,0.6); background: transparent;">
                                <i class="bi bi-send ${isRtl ? 'ms-2' : 'me-2'}" style="font-size: 18px;"></i>${t.liSend}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else if (preview.platform === 'instagram') {
        // Instagram: Caption with "... more" for posts over 125 chars - FULL TEXT accessible
        const igMaxVisible = 125;
        const igText = preview.text || '';
        const igShowMore = igText.length > igMaxVisible;
        const igVisibleText = igShowMore ? igText.substring(0, igMaxVisible) : igText;
        
        html = `
            <div class="instagram-mockup" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; direction: ${direction}; background: #000;">
                <div class="card border-0" style="max-width: 470px; margin: 0 auto; background: #000;">
                    <!-- Header -->
                    <div class="d-flex align-items-center p-3">
                        <div class="rounded-circle p-0.5" style="background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); padding: 2px;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #000; border: 2px solid #000;">
                                <i class="bi bi-hospital-fill" style="font-size: 14px; color: #fff;"></i>
                            </div>
                        </div>
                        <div class="${isRtl ? 'me-3' : 'ms-3'} flex-grow-1">
                            <div class="d-flex align-items-center">
                                <span class="fw-semibold text-white" style="font-size: 14px;">medicalpro</span>
                                <i class="bi bi-patch-check-fill mx-1" style="color: #0095F6; font-size: 14px;"></i>
                            </div>
                            <span style="font-size: 12px; color: #A8A8A8;">${t.igOriginalAudio}</span>
                        </div>
                        <button class="btn btn-link text-white p-0"><i class="bi bi-three-dots"></i></button>
                    </div>
                    
                    <!-- Image/Content Area -->
                    <div class="position-relative" style="background: linear-gradient(135deg, #405DE6, #5851DB, #833AB4, #C13584, #E1306C, #FD1D1D); aspect-ratio: 1/1;">
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <div class="text-center text-white p-4">
                                <i class="bi bi-image" style="font-size: 64px; opacity: 0.5;"></i>
                                <p class="mt-2 mb-0" style="font-size: 14px; opacity: 0.7;">${t.igAddImage}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="p-3 pb-2">
                        <div class="d-flex justify-content-between mb-2">
                            <div class="d-flex">
                                <button class="btn btn-link text-white p-0 ${isRtl ? 'ms-3' : 'me-3'}"><i class="bi bi-heart" style="font-size: 24px;"></i></button>
                                <button class="btn btn-link text-white p-0 ${isRtl ? 'ms-3' : 'me-3'}"><i class="bi bi-chat" style="font-size: 24px;"></i></button>
                                <button class="btn btn-link text-white p-0"><i class="bi bi-send" style="font-size: 24px;"></i></button>
                            </div>
                            <button class="btn btn-link text-white p-0"><i class="bi bi-bookmark" style="font-size: 24px;"></i></button>
                        </div>
                        
                        <!-- Likes -->
                        <div class="fw-semibold text-white mb-2" style="font-size: 14px;">2,847 ${t.igLikes}</div>
                        
                        <!-- Caption - FULL TEXT with expand -->
                        <div id="ig-caption" style="font-size: 14px; direction: ${direction}; text-align: ${textAlign};">
                            <span class="fw-semibold text-white">medicalpro</span>
                            <span class="text-white"> </span>
                            ${preview.hook && !igShowMore ? `<span class="fw-bold text-white">${preview.hook}</span>` : ''}
                            <span id="ig-visible-text" class="text-white">${igVisibleText.replace(/\n/g, '<br>')}</span>${igShowMore ? '' : ''}
                            ${igShowMore ? `
                                <span id="ig-hidden-text" style="display: none;" class="text-white">${igText.substring(igMaxVisible).replace(/\n/g, '<br>')}</span>
                                <span id="ig-more-btn" style="color: #A8A8A8; cursor: pointer;" onclick="toggleIgText(this)">... ${t.igMore}</span>
                            ` : ''}
                        </div>
                        
                        <!-- Hashtags - FULL LIST -->
                        ${preview.hashtags && preview.hashtags.length > 0 ? `
                            <div class="mt-2" id="ig-hashtags" style="font-size: 14px; line-height: 1.6;">
                                ${preview.hashtags.slice(0, 5).map(tag => `<a href="#" class="text-decoration-none me-1" style="color: #E0F1FF;">${tag}</a>`).join('')}
                                ${preview.hashtags.length > 5 ? `
                                    <span id="ig-more-tags" style="display: none;">
                                        ${preview.hashtags.slice(5).map(tag => `<a href="#" class="text-decoration-none me-1" style="color: #E0F1FF;">${tag}</a>`).join('')}
                                    </span>
                                    <span style="color: #A8A8A8; cursor: pointer;" onclick="toggleIgTags(this)">...+${preview.hashtags.length - 5} ${t.igMoreTags}</span>
                                ` : ''}
                            </div>
                        ` : ''}
                        
                        <!-- Comments Link -->
                        <div class="mt-2" style="color: #A8A8A8; font-size: 14px; cursor: pointer;">${t.igViewAllComments}</div>
                        
                        <!-- Time -->
                        <div class="mt-1" style="color: #A8A8A8; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px;">
                            ${t.igTimeAgo}
                        </div>
                    </div>
                    
                    <!-- Comment Input -->
                    <div class="d-flex align-items-center px-3 py-2 border-top" style="border-color: #262626 !important;">
                        <button class="btn btn-link p-0 ${isRtl ? 'ms-3' : 'me-3'}"><span style="font-size: 24px;">😊</span></button>
                        <input type="text" class="form-control bg-transparent border-0 text-white" placeholder="${t.igAddComment}" style="font-size: 14px;">
                        <button class="btn btn-link p-0 text-primary fw-semibold" style="font-size: 14px; color: #0095F6 !important;">${t.igPost}</button>
                    </div>
                </div>
            </div>
        `;
    } else if (preview.platform === 'tiktok') {
        // TikTok: Full caption visible
        const ttText = preview.text || '';
        
        html = `
            <div class="tiktok-mockup" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; direction: ${direction}; background: #000;">
                <div class="position-relative" style="max-width: 400px; margin: 0 auto; aspect-ratio: 9/16; background: linear-gradient(135deg, #25F4EE, #FE2C55); border-radius: 12px; overflow: hidden;">
                    <!-- Video Placeholder -->
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <div class="text-center text-white">
                            <i class="bi bi-play-circle" style="font-size: 64px; opacity: 0.7;"></i>
                            <p class="mt-2 mb-0" style="font-size: 14px; opacity: 0.7;">${t.ttYourVideo}</p>
                        </div>
                    </div>
                    
                    <!-- Right Side Actions -->
                    <div class="position-absolute d-flex flex-column align-items-center gap-3" style="${isRtl ? 'left' : 'right'}: 12px; bottom: 100px;">
                        <div class="text-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mb-1" style="width: 48px; height: 48px; background: rgba(255,255,255,0.1);">
                                <i class="bi bi-hospital-fill text-white" style="font-size: 20px;"></i>
                            </div>
                            <span class="badge bg-danger rounded-pill" style="font-size: 10px;">+</span>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-link text-white p-0"><i class="bi bi-heart-fill" style="font-size: 32px;"></i></button>
                            <div class="text-white" style="font-size: 12px;">12.5K</div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-link text-white p-0"><i class="bi bi-chat-dots-fill" style="font-size: 32px;"></i></button>
                            <div class="text-white" style="font-size: 12px;">847</div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-link text-white p-0"><i class="bi bi-bookmark-fill" style="font-size: 32px;"></i></button>
                            <div class="text-white" style="font-size: 12px;">2.1K</div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-link text-white p-0"><i class="bi bi-share-fill" style="font-size: 32px;"></i></button>
                            <div class="text-white" style="font-size: 12px;">${t.ttShare}</div>
                        </div>
                    </div>
                    
                    <!-- Bottom Content -->
                    <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                        <div class="d-flex align-items-center mb-2">
                            <span class="fw-bold text-white" style="font-size: 16px;">@medicalpro</span>
                            <i class="bi bi-patch-check-fill mx-1" style="color: #20D5EC; font-size: 14px;"></i>
                        </div>
                        
                        <!-- Caption - FULL TEXT -->
                        <div class="mb-2" style="font-size: 14px; color: white; direction: ${direction}; text-align: ${textAlign}; max-height: 80px; overflow-y: auto;">
                            ${ttText.replace(/\n/g, '<br>')}
                        </div>
                        
                        <!-- Hashtags -->
                        ${preview.hashtags && preview.hashtags.length > 0 ? `
                            <div class="mb-2" style="font-size: 14px;">
                                ${preview.hashtags.slice(0, 5).map(tag => `<a href="#" class="text-decoration-none me-1 fw-semibold" style="color: white;">${tag}</a>`).join('')}
                            </div>
                        ` : ''}
                        
                        <!-- Music -->
                        <div class="d-flex align-items-center">
                            <i class="bi bi-music-note-beamed text-white ${isRtl ? 'ms-2' : 'me-2'}"></i>
                            <div class="text-white" style="font-size: 13px; white-space: nowrap; overflow: hidden;">
                                <marquee>${t.ttOriginalSound}</marquee>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Add statistics and actions below the mockup
    html += `
        <div class="mt-4">
            <!-- Character Count -->
            <div class="alert alert-info d-flex align-items-center justify-content-between">
                <div>
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>${t.characterCount}:</strong> ${preview.current_length} / ${preview.max_length}
                    ${preview.current_length > preview.recommended_length ? 
                        `<span class="badge bg-warning ms-2">${t.aboveRecommended}</span>` : 
                        `<span class="badge bg-success ms-2">${t.optimalLength}</span>`}
                </div>
            </div>
            
            <!-- Thread Suggestion for Twitter -->
            ${preview.thread_suggestion && preview.thread_suggestion.length > 0 ? `
                <div class="alert alert-primary">
                    <strong><i class="bi bi-chat-square-text me-2"></i>${t.threadSuggestion}:</strong>
                    <small class="text-muted">(${t.contentTooLong})</small>
                    <div class="list-group list-group-flush mt-2">
                        ${preview.thread_suggestion.map((tweet, i) => `
                            <div class="list-group-item bg-transparent border-start border-primary border-3 ps-3">
                                <div class="d-flex align-items-center mb-1">
                                    <span class="badge bg-primary me-2">${i + 1}</span>
                                    <small class="text-muted">${tweet.length} ${t.chars}</small>
                                </div>
                                <div>${tweet}</div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            ` : ''}
            
            <!-- Best Practices -->
            ${preview.best_practices ? `
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="card-title"><i class="bi bi-lightbulb text-warning me-2"></i>${t.bestPractices}</h6>
                        <ul class="mb-0">
                            ${preview.best_practices.map(tip => `<li class="mb-1">${tip}</li>`).join('')}
                        </ul>
                    </div>
                </div>
            ` : ''}
            
            <!-- Copy Button -->
            <div class="text-center mt-4">
                <button class="btn btn-lg btn-primary shadow" onclick="copySocialPreview('${preview.platform}')">
                    <i class="bi bi-clipboard-check me-2"></i>${t.copyContent} ${preview.platform.charAt(0).toUpperCase() + preview.platform.slice(1)}
                </button>
            </div>
        </div>
    `;
    
    content.innerHTML = html;
}

function copySocialPreview(platform) {
    const preview = currentPreviewData[platform];
    if (!preview) return;
    
    let textToCopy = preview.text;
    
    if (preview.hashtags && preview.hashtags.length > 0) {
        textToCopy += '\n\n' + preview.hashtags.join(' ');
    }
    
    navigator.clipboard.writeText(textToCopy).then(function() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: '{{ __("translation.content_generator.copied_success") }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        } else {
            alert('{{ __("translation.content_generator.copied_success") }}');
        }
    });
}

// Toggle functions for "See more" / "See less" on social media previews
function toggleFbText(el) {
    const hidden = document.getElementById('fb-hidden-text');
    if (hidden.style.display === 'none') {
        hidden.style.display = 'inline';
        el.textContent = socialMediaTranslations.seeLess;
    } else {
        hidden.style.display = 'none';
        el.textContent = socialMediaTranslations.seeMore;
    }
}

function toggleLiText(el) {
    const hidden = document.getElementById('li-hidden-text');
    if (hidden.style.display === 'none') {
        hidden.style.display = 'inline';
        el.textContent = socialMediaTranslations.liSeeLess;
    } else {
        hidden.style.display = 'none';
        el.textContent = socialMediaTranslations.liSeeMore;
    }
}

function toggleIgText(el) {
    const hidden = document.getElementById('ig-hidden-text');
    if (hidden.style.display === 'none') {
        hidden.style.display = 'inline';
        el.style.display = 'none';
    }
}

function toggleIgTags(el) {
    const moreTags = document.getElementById('ig-more-tags');
    if (moreTags.style.display === 'none') {
        moreTags.style.display = 'inline';
        el.style.display = 'none';
    }
}

// Load Facebook preview by default when modal opens
document.getElementById('socialPreviewModal').addEventListener('shown.bs.modal', function () {
    if (Object.keys(currentPreviewData).length === 0) {
        loadSocialPreview('facebook');
    }
});
</script>

<!-- AI Refinement Modal -->
<div class="modal fade" id="aiRefinementModal" tabindex="-1" aria-labelledby="aiRefinementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-ai text-white">
                <h5 class="modal-title" id="aiRefinementModalLabel">
                    <i class="bi bi-magic me-2"></i>{{ __('translation.content_generator.ai_refine') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">{{ __('translation.content_generator.ai_refine_description') }}</p>
                
                <!-- Refinement Actions -->
                <h6 class="fw-bold mb-3">{{ __('translation.content_generator.refinement_actions') }}</h6>
                <div class="list-group mb-4">
                    <button type="button" class="list-group-item list-group-item-action" onclick="applyRefinement('improve_clarity')">
                        <i class="bi bi-lightbulb text-warning me-2"></i>{{ __('translation.content_generator.improve_clarity') }}
                    </button>
                    <button type="button" class="list-group-item list-group-item-action" onclick="applyRefinement('enhance_medical_accuracy')">
                        <i class="bi bi-heart-pulse text-danger me-2"></i>{{ __('translation.content_generator.enhance_medical_accuracy') }}
                    </button>
                    <button type="button" class="list-group-item list-group-item-action" onclick="applyRefinement('simplify_language')">
                        <i class="bi bi-chat-dots text-success me-2"></i>{{ __('translation.content_generator.simplify_language') }}
                    </button>
                    <button type="button" class="list-group-item list-group-item-action" onclick="applyRefinement('add_examples')">
                        <i class="bi bi-list-check text-info me-2"></i>{{ __('translation.content_generator.add_examples') }}
                    </button>
                    <button type="button" class="list-group-item list-group-item-action" onclick="applyRefinement('expand_details')">
                        <i class="bi bi-zoom-in text-primary me-2"></i>{{ __('translation.content_generator.expand_details') }}
                    </button>
                    <button type="button" class="list-group-item list-group-item-action" onclick="applyRefinement('make_concise')">
                        <i class="bi bi-dash-circle text-secondary me-2"></i>{{ __('translation.content_generator.make_concise') }}
                    </button>
                </div>

                <!-- Tone Adjustment -->
                <h6 class="fw-bold mb-3">{{ __('translation.content_generator.adjust_tone') }}</h6>
                <div class="list-group">
                    <button type="button" class="list-group-item list-group-item-action" onclick="adjustTone('formal')">
                        <i class="bi bi-briefcase text-dark me-2"></i>{{ __('translation.content_generator.tone_formal') }}
                    </button>
                    <button type="button" class="list-group-item list-group-item-action" onclick="adjustTone('empathetic')">
                        <i class="bi bi-heart text-danger me-2"></i>{{ __('translation.content_generator.tone_empathetic') }}
                    </button>
                    <button type="button" class="list-group-item list-group-item-action" onclick="adjustTone('simple')">
                        <i class="bi bi-person-check text-success me-2"></i>{{ __('translation.content_generator.tone_simple') }}
                    </button>
                    <button type="button" class="list-group-item list-group-item-action" onclick="adjustTone('professional')">
                        <i class="bi bi-award text-primary me-2"></i>{{ __('translation.content_generator.tone_professional') }}
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>{{ __('translation.common.cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Version History Modal -->
<div class="modal fade" id="versionHistoryModal" tabindex="-1" aria-labelledby="versionHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="versionHistoryModalLabel">
                    <i class="bi bi-clock-history me-2"></i>{{ __('translation.content_generator.version_history') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="versionHistoryContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">{{ __('translation.content_generator.loading') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>{{ __('translation.common.close') }}
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.btn-gradient-ai {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    transition: all 0.3s ease;
}

.btn-gradient-ai:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.bg-gradient-ai {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.version-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

/* PHASE 4: Translate Button Gradient */
.btn-gradient-translate {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
    border: none;
    transition: all 0.3s ease;
}
.btn-gradient-translate:hover {
    background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(17, 153, 142, 0.4);
}
.bg-gradient-translate {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

/* PHASE 4: Template Button Gradient */
.btn-gradient-template {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    border: none;
    transition: all 0.3s ease;
}
.btn-gradient-template:hover {
    background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 87, 108, 0.4);
}
.bg-gradient-template {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

/* PHASE 4: Analytics Button Gradient */
.btn-gradient-analytics {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    border: none;
    transition: all 0.3s ease;
}
.btn-gradient-analytics:hover {
    background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(79, 172, 254, 0.4);
}
.bg-gradient-analytics {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}
</style>

<script>
/**
 * AI Refinement Functions - Uses centralized RefinementManager with fallback
 */
function applyRefinement(action) {
    const url = "{{ route('content.refine', ['locale' => app()->getLocale(), 'id' => $content->id]) }}";
    const callback = (data) => {
        if (data.data && data.data.redirect_url) {
            window.location.href = data.data.redirect_url;
        } else {
            window.location.reload();
        }
    };
    
    if (typeof RefinementManager !== 'undefined') {
        RefinementManager.applyRefinement(action, url, callback);
    } else {
        // Fallback
        Swal.fire({ title: '{{ __("translation.content_generator.refining") }}', html: '{{ __("translation.content_generator.refining_message") }}', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        
        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ action: action })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: '{{ __("translation.content_generator.refinement_success") }}', text: '{{ __("translation.content_generator.redirecting") }}', timer: 2000, showConfirmButton: false }).then(() => callback(data));
            } else {
                Swal.fire({ icon: 'error', title: '{{ __("translation.content_generator.refinement_failed") }}', text: data.message || '{{ __("translation.content_generator.error_occurred") }}' });
            }
        })
        .catch(() => Swal.fire({ icon: 'error', title: '{{ __("translation.content_generator.error") }}', text: '{{ __("translation.content_generator.network_error") }}' }));
    }
}

/**
 * Adjust Tone - Uses centralized RefinementManager with fallback
 */
function adjustTone(tone) {
    const url = "{{ route('content.adjust-tone', ['locale' => app()->getLocale(), 'id' => $content->id]) }}";
    const callback = (data) => {
        if (data.data && data.data.redirect_url) {
            window.location.href = data.data.redirect_url;
        } else {
            window.location.reload();
        }
    };
    
    if (typeof RefinementManager !== 'undefined') {
        RefinementManager.adjustTone(tone, url, callback);
    } else {
        // Fallback
        Swal.fire({ title: '{{ __("translation.content_generator.adjusting_tone") }}', html: '{{ __("translation.content_generator.adjusting_tone_message") }}', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        
        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ tone: tone })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: '{{ __("translation.content_generator.tone_adjustment_success") }}', text: '{{ __("translation.content_generator.redirecting") }}', timer: 2000, showConfirmButton: false }).then(() => callback(data));
            } else {
                Swal.fire({ icon: 'error', title: '{{ __("translation.content_generator.tone_adjustment_failed") }}', text: data.message || '{{ __("translation.content_generator.error_occurred") }}' });
            }
        })
        .catch(() => Swal.fire({ icon: 'error', title: '{{ __("translation.content_generator.error") }}', text: '{{ __("translation.content_generator.network_error") }}' }));
    }
}

/**
 * Version History Functions - Using centralized VersionManager where possible
 */
document.getElementById('versionHistoryModal')?.addEventListener('shown.bs.modal', function () {
    loadVersionHistory();
});

function loadVersionHistory() {
    const url = "{{ route('content.version-history', ['locale' => app()->getLocale(), 'id' => $content->id]) }}";
    
    if (typeof VersionManager !== 'undefined') {
        VersionManager.loadHistory(url, displayVersionHistory);
    } else {
        fetch(url, { headers: { 'Accept': 'application/json' } })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayVersionHistory(data.data);
            } else {
                document.getElementById('versionHistoryContent').innerHTML = '<div class="alert alert-danger">{{ __("translation.content_generator.error_loading_versions") }}</div>';
            }
        })
        .catch(() => {
            document.getElementById('versionHistoryContent').innerHTML = '<div class="alert alert-danger">{{ __("translation.content_generator.network_error") }}</div>';
        });
    }
}

function displayVersionHistory(versions) {
    const currentId = {{ $content->id }};
    let html = '<div class="timeline">';
    
    versions.forEach((version) => {
        const isActive = version.id === currentId;
        const statusClass = { 'draft': 'secondary', 'pending_review': 'warning', 'approved': 'success', 'rejected': 'danger' }[version.review_status] || 'secondary';
        
        html += `
            <div class="timeline-item ${isActive ? 'border-primary' : ''} mb-3 p-3 border rounded">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h6 class="mb-1">
                            <span class="badge bg-${statusClass}">Version ${version.version}</span>
                            ${isActive ? '<span class="badge bg-primary ms-2">Current</span>' : ''}
                        </h6>
                        <small class="text-muted">${new Date(version.created_at).toLocaleString()}</small>
                    </div>
                    ${!isActive ? `<button class="btn btn-sm btn-outline-primary" onclick="restoreVersion(${version.id})"><i class="bi bi-arrow-clockwise me-1"></i>Restore</button>` : ''}
                </div>
                ${version.review_notes ? `<p class="mb-0 small"><i class="bi bi-info-circle me-1"></i>${version.review_notes}</p>` : ''}
                <p class="mb-0 small text-muted mt-2">${version.word_count} words</p>
            </div>
        `;
    });
    
    html += '</div>';
    document.getElementById('versionHistoryContent').innerHTML = html;
}

/**
 * Restore version - Uses centralized VersionManager with fallback
 */
function restoreVersion(versionId) {
    const url = "{{ route('content.restore-version', ['locale' => app()->getLocale(), 'id' => $content->id]) }}";
    
    if (typeof VersionManager !== 'undefined') {
        VersionManager.restoreVersion(versionId, url);
    } else {
        Swal.fire({
            title: '{{ __("translation.content_generator.restore_version") }}',
            text: '{{ __("translation.content_generator.restore_version_confirm") }}',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '{{ __("translation.content_generator.restore") }}',
            cancelButtonText: '{{ __("translation.content_generator.cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                performRestore(versionId);
            }
        });
    }
}

function performRestore(versionId) {
    Swal.fire({ title: '{{ __("translation.content_generator.restoring") }}', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    fetch("{{ route('content.restore-version', ['locale' => app()->getLocale(), 'id' => $content->id]) }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ restore_to_id: versionId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({ icon: 'success', title: '{{ __("translation.content_generator.version_restored") }}', timer: 2000, showConfirmButton: false }).then(() => { window.location.href = data.data.redirect_url; });
        } else {
            Swal.fire({ icon: 'error', title: '{{ __("translation.content_generator.version_restore_failed") }}', text: data.message });
        }
    })
    .catch(() => Swal.fire({ icon: 'error', title: '{{ __("translation.content_generator.error") }}', text: '{{ __("translation.content_generator.network_error") }}' }));
}

/**
 * SEO Analysis Functions - Uses centralized SeoManager with fallback
 */
function analyzeSeo() {
    const focusKeyword = document.getElementById('seoFocusKeyword').value;
    const metaDescription = document.getElementById('seoMetaDescription').value;
    const url = "{{ route('content.seo.analyze', ['locale' => app()->getLocale(), 'id' => $content->id]) }}";
    
    if (typeof SeoManager !== 'undefined') {
        SeoManager.analyzeSeo(url, focusKeyword, metaDescription, displaySeoResults);
    } else {
        Swal.fire({ title: 'Analyzing SEO...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ focus_keyword: focusKeyword, meta_description: metaDescription })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { displaySeoResults(data.data); Swal.close(); }
            else { Swal.fire({ icon: 'error', title: 'SEO Analysis Failed', text: data.message }); }
        })
        .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Network error occurred' }));
    }
}

function displaySeoResults(data) {
    const overallScore = data.overall_score;
    const grade = data.grade;
    
    document.getElementById('seoOverallScore').textContent = overallScore;
    document.getElementById('seoGrade').textContent = grade;
    document.getElementById('seoGrade').className = `badge fs-5 ${getGradeClass(grade)}`;
    
    const progressBar = document.getElementById('seoProgressBar');
    progressBar.style.width = overallScore + '%';
    progressBar.className = `progress-bar ${getScoreColor(overallScore)}`;
    progressBar.setAttribute('aria-valuenow', overallScore);
    
    let scoresHtml = '';
    Object.entries(data.scores).forEach(([category, scoreData]) => {
        // scoreData is an object with { score, status, message, ... }
        const scoreValue = typeof scoreData === 'object' ? scoreData.score : scoreData;
        const message = typeof scoreData === 'object' ? scoreData.message : '';
        const colorClass = getScoreColor(scoreValue);
        const categoryName = category.replace(/_/g, ' ');
        scoresHtml += `
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-capitalize">${categoryName}</span>
                    <span class="fw-bold">${scoreValue}%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar ${colorClass}" role="progressbar" style="width: ${scoreValue}%" aria-valuenow="${scoreValue}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                ${message ? `<small class="text-muted">${message}</small>` : ''}
            </div>`;
    });
    document.getElementById('seoDetailedScores').innerHTML = scoresHtml;
    
    let recsHtml = '<ul class="list-unstyled">';
    data.recommendations.forEach(rec => {
        // rec is an object with { priority, category, message, action }
        const recMessage = typeof rec === 'object' ? rec.message : rec;
        const recAction = typeof rec === 'object' && rec.action ? rec.action : '';
        const priority = typeof rec === 'object' && rec.priority ? rec.priority : 'medium';
        const priorityClass = priority === 'high' ? 'text-danger' : (priority === 'medium' ? 'text-warning' : 'text-info');
        recsHtml += `<li class="mb-2"><i class="bi bi-lightbulb ${priorityClass} me-2"></i><strong>${recMessage}</strong>${recAction ? `<br><small class="text-muted ms-4">${recAction}</small>` : ''}</li>`;
    });
    recsHtml += '</ul>';
    document.getElementById('seoRecommendations').innerHTML = recsHtml;
    
    document.getElementById('seoResults').style.display = 'block';
    document.getElementById('seoAnalysisForm').style.display = 'none';
    // Toggle footer buttons
    document.getElementById('btnAnalyzeSeo').style.display = 'none';
    document.getElementById('btnAnalyzeAgain').style.display = 'inline-block';
}

function getScoreColor(score) {
    if (typeof SeoManager !== 'undefined') return SeoManager.getScoreColor(score);
    if (score >= 80) return 'bg-success';
    if (score >= 60) return 'bg-warning';
    return 'bg-danger';
}

function getGradeClass(grade) {
    if (typeof SeoManager !== 'undefined') return SeoManager.getGradeClass(grade);
    if (grade === 'A' || grade === 'B') return 'bg-success';
    if (grade === 'C') return 'bg-warning';
    return 'bg-danger';
}

function resetSeoAnalysis() {
    if (typeof SeoManager !== 'undefined') { SeoManager.resetAnalysis(); return; }
    document.getElementById('seoResults').style.display = 'none';
    document.getElementById('seoAnalysisForm').style.display = 'block';
    // Toggle footer buttons
    document.getElementById('btnAnalyzeSeo').style.display = 'inline-block';
    document.getElementById('btnAnalyzeAgain').style.display = 'none';
}

/**
 * Schedule Content Functions - Uses centralized CalendarManager with fallback
 */
function scheduleContent() {
    const scheduledAt = document.getElementById('scheduledAt').value;
    const platforms = Array.from(document.querySelectorAll('input[name="platforms[]"]:checked')).map(el => el.value);
    const notes = document.getElementById('scheduleNotes').value;
    const url = "{{ route('content.calendar.schedule', ['locale' => app()->getLocale(), 'id' => $content->id]) }}";
    
    if (!scheduledAt) { Swal.fire({ icon: 'warning', title: 'Missing Date', text: 'Please select a date and time' }); return; }
    
    if (typeof CalendarManager !== 'undefined') {
        CalendarManager.scheduleContent(url, { scheduled_at: scheduledAt, platforms: platforms, notes: notes });
    } else {
        Swal.fire({ title: 'Scheduling...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ scheduled_at: scheduledAt, platforms: platforms, notes: notes })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Content Scheduled!', text: 'Your content has been scheduled successfully', timer: 2000, showConfirmButton: false })
                .then(() => { bootstrap.Modal.getInstance(document.getElementById('scheduleModal')).hide(); location.reload(); });
            } else { Swal.fire({ icon: 'error', title: 'Scheduling Failed', text: data.message }); }
        })
        .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Network error occurred' }));
    }
}

function publishNow() {
    const url = "{{ route('content.calendar.publish', ['locale' => app()->getLocale(), 'id' => $content->id]) }}";
    
    if (typeof CalendarManager !== 'undefined') {
        CalendarManager.publishNow(url);
    } else {
        Swal.fire({ title: 'Publish Now?', text: 'This will mark the content as published immediately', icon: 'question', showCancelButton: true, confirmButtonText: 'Yes, Publish', cancelButtonText: 'Cancel' })
        .then((result) => { if (result.isConfirmed) performPublish(); });
    }
}

function performPublish() {
    Swal.fire({ title: 'Publishing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    fetch("{{ route('content.calendar.publish', ['locale' => app()->getLocale(), 'id' => $content->id]) }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) { Swal.fire({ icon: 'success', title: 'Published!', text: 'Your content has been published', timer: 2000, showConfirmButton: false }).then(() => location.reload()); }
        else { Swal.fire({ icon: 'error', title: 'Publishing Failed', text: data.message }); }
    })
    .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Network error occurred' }));
}
</script>

<!-- SEO Analysis Modal -->
<div class="modal fade" id="seoAnalysisModal" tabindex="-1" aria-labelledby="seoAnalysisModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-seo text-white">
                <h5 class="modal-title" id="seoAnalysisModalLabel">
                    <i class="bi bi-graph-up me-2"></i>SEO Analysis
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Analysis Form -->
                <div id="seoAnalysisForm">
                    <div class="mb-3">
                        <label for="seoFocusKeyword" class="form-label">Focus Keyword</label>
                        <input type="text" class="form-control" id="seoFocusKeyword" 
                               value="{{ $content->seo_focus_keyword ?? '' }}"
                               placeholder="Enter your target keyword">
                        <small class="text-muted">Main keyword you want to rank for</small>
                    </div>
                    <div class="mb-3">
                        <label for="seoMetaDescription" class="form-label">Meta Description</label>
                        <textarea class="form-control" id="seoMetaDescription" rows="3"
                                  placeholder="Enter meta description (150-160 characters)">{{ $content->seo_meta_description ?? '' }}</textarea>
                        <small class="text-muted">Optimal length: 150-160 characters</small>
                    </div>
                </div>
                
                <!-- SEO Results -->
                <div id="seoResults" style="display: none;">
                    <!-- Overall Score -->
                    <div class="text-center mb-4">
                        <h2 class="display-4 fw-bold mb-2">
                            <span id="seoOverallScore">0</span>/100
                        </h2>
                        <span id="seoGrade" class="badge bg-success fs-5">A</span>
                        <div class="progress mt-3" style="height: 20px;">
                            <div id="seoProgressBar" class="progress-bar bg-success" role="progressbar" 
                                 style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    
                    <!-- Detailed Scores -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Detailed Scores</h6>
                        <div id="seoDetailedScores"></div>
                    </div>
                    
                    <!-- Recommendations -->
                    <div class="mb-3">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-lightbulb text-warning me-2"></i>Recommendations
                        </h6>
                        <div id="seoRecommendations"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>{{ __('translation.common.close') }}
                </button>
                <!-- Form Actions -->
                <button type="button" class="btn btn-primary" id="btnAnalyzeSeo" onclick="analyzeSeo()">
                    <i class="bi bi-play-circle me-2"></i>Analyze SEO
                </button>
                <!-- Results Actions -->
                <button type="button" class="btn btn-outline-primary" id="btnAnalyzeAgain" style="display: none;" onclick="resetSeoAnalysis()">
                    <i class="bi bi-arrow-repeat me-2"></i>Analyze Again
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Content Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-calendar text-white">
                <h5 class="modal-title" id="scheduleModalLabel">
                    <i class="bi bi-calendar-check me-2"></i>Schedule Content
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="scheduledAt" class="form-label">Schedule Date & Time</label>
                    <input type="datetime-local" class="form-control" id="scheduledAt" 
                           min="{{ now()->format('Y-m-d\TH:i') }}"
                           value="{{ $content->scheduled_at ? $content->scheduled_at->format('Y-m-d\TH:i') : '' }}">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Publishing Platforms</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="blog" id="platformBlog">
                        <label class="form-check-label" for="platformBlog">
                            <i class="bi bi-newspaper text-primary me-1"></i>Blog
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="website" id="platformWebsite">
                        <label class="form-check-label" for="platformWebsite">
                            <i class="bi bi-globe text-info me-1"></i>Website
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="facebook" id="platformFacebook">
                        <label class="form-check-label" for="platformFacebook">
                            <i class="bi bi-facebook text-primary me-1"></i>Facebook
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="twitter" id="platformTwitter">
                        <label class="form-check-label" for="platformTwitter">
                            <i class="bi bi-twitter text-info me-1"></i>Twitter
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="linkedin" id="platformLinkedin">
                        <label class="form-check-label" for="platformLinkedin">
                            <i class="bi bi-linkedin text-primary me-1"></i>LinkedIn
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="instagram" id="platformInstagram">
                        <label class="form-check-label" for="platformInstagram">
                            <i class="bi bi-instagram text-danger me-1"></i>Instagram
                        </label>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="scheduleNotes" class="form-label">Publishing Notes</label>
                    <textarea class="form-control" id="scheduleNotes" rows="3" 
                              placeholder="Add notes for this scheduled content">{{ $content->publishing_notes ?? '' }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="publishNow()">
                    <i class="bi bi-send me-2"></i>Publish Now
                </button>
                <button type="button" class="btn btn-primary" onclick="scheduleContent()">
                    <i class="bi bi-calendar-check me-2"></i>Schedule
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- TEAM COLLABORATION MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="teamCollaborationModal" tabindex="-1" aria-labelledby="teamCollaborationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white;">
                <h5 class="modal-title" id="teamCollaborationModalLabel">
                    <i class="bi bi-people-fill me-2"></i>Team Collaboration
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Tabs -->
                <ul class="nav nav-tabs mb-3" id="teamTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="myTeamsTab" data-bs-toggle="tab" data-bs-target="#myTeamsPane" type="button" role="tab">
                            <i class="bi bi-people me-1"></i>My Teams
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="createTeamTab" data-bs-toggle="tab" data-bs-target="#createTeamPane" type="button" role="tab">
                            <i class="bi bi-plus-circle me-1"></i>Create Team
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="invitationsTab" data-bs-toggle="tab" data-bs-target="#invitationsPane" type="button" role="tab">
                            <i class="bi bi-envelope me-1"></i>Invitations
                            <span class="badge bg-danger ms-1 invitations-count" style="display: none;">0</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="teamTabsContent">
                    <!-- My Teams Pane -->
                    <div class="tab-pane fade show active" id="myTeamsPane" role="tabpanel">
                        <div id="teamsLoadingSpinner" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div id="teamsContainer" style="display: none;">
                            <div id="teamsList"></div>
                            <div id="noTeamsMessage" class="text-center py-4 text-muted" style="display: none;">
                                <i class="bi bi-people fs-1 mb-2 d-block"></i>
                                <p>You don't have any teams yet.</p>
                                <button class="btn btn-primary" onclick="document.getElementById('createTeamTab').click()">
                                    <i class="bi bi-plus-circle me-1"></i>Create Your First Team
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Create Team Pane -->
                    <div class="tab-pane fade" id="createTeamPane" role="tabpanel">
                        <form id="createTeamForm">
                            <div class="mb-3">
                                <label for="teamName" class="form-label">Team Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="teamName" placeholder="Enter team name" required maxlength="255">
                            </div>
                            <div class="mb-3">
                                <label for="teamDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="teamDescription" rows="3" placeholder="Describe your team's purpose" maxlength="1000"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="teamPlan" class="form-label">Plan</label>
                                <select class="form-select" id="teamPlan">
                                    <option value="free">Free</option>
                                    <option value="team">Team</option>
                                    <option value="enterprise">Enterprise</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" id="createTeamBtn">
                                <i class="bi bi-plus-circle me-2"></i>Create Team
                            </button>
                        </form>
                    </div>

                    <!-- Invitations Pane -->
                    <div class="tab-pane fade" id="invitationsPane" role="tabpanel">
                        <div id="invitationsLoadingSpinner" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div id="invitationsContainer" style="display: none;">
                            <div id="invitationsList"></div>
                            <div id="noInvitationsMessage" class="text-center py-4 text-muted" style="display: none;">
                                <i class="bi bi-envelope-open fs-1 mb-2 d-block"></i>
                                <p>No pending invitations</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- CONTENT COMMENTS MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="contentCommentsModal" tabindex="-1" aria-labelledby="contentCommentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #14b8a6, #06b6d4); color: white;">
                <h5 class="modal-title" id="contentCommentsModalLabel">
                    <i class="bi bi-chat-dots-fill me-2"></i>Comments & Discussion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add Comment Form -->
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex gap-3">
                            <div class="avatar-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="flex-grow-1">
                                <textarea class="form-control mb-2" id="newCommentText" rows="3" placeholder="Add a comment..."></textarea>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="annotateText">
                                        <label class="form-check-label small" for="annotateText">
                                            <i class="bi bi-highlighter me-1"></i>Add text annotation
                                        </label>
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="addComment()">
                                        <i class="bi bi-send me-1"></i>Post Comment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comments List -->
                <div id="commentsLoadingSpinner" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div id="commentsContainer" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted small"><span id="commentsCount">0</span> comments</span>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-secondary active" onclick="filterComments('all')">All</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="filterComments('unresolved')">Open</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="filterComments('resolved')">Resolved</button>
                        </div>
                    </div>
                    <div id="commentsList"></div>
                    <div id="noCommentsMessage" class="text-center py-4 text-muted" style="display: none;">
                        <i class="bi bi-chat-left-text fs-1 mb-2 d-block"></i>
                        <p>No comments yet. Start the conversation!</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- ASSIGN CONTENT MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="assignContentModal" tabindex="-1" aria-labelledby="assignContentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #f97316, #eab308); color: white;">
                <h5 class="modal-title" id="assignContentModalLabel">
                    <i class="bi bi-person-plus-fill me-2"></i>Assign Content
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Current Assignment Status -->
                <div id="currentAssignment" class="alert alert-info mb-3" style="display: none;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-check fs-4 me-2"></i>
                        <div>
                            <strong>Currently Assigned</strong>
                            <div class="small" id="currentAssignmentInfo"></div>
                        </div>
                    </div>
                </div>

                <form id="assignContentForm">
                    <div class="mb-3">
                        <label for="assignTeamSelect" class="form-label">Select Team <span class="text-danger">*</span></label>
                        <select class="form-select" id="assignTeamSelect" required>
                            <option value="">Choose a team...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="assignMemberSelect" class="form-label">Assign To <span class="text-danger">*</span></label>
                        <select class="form-select" id="assignMemberSelect" required disabled>
                            <option value="">First select a team...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="assignPriority" class="form-label">Priority</label>
                        <select class="form-select" id="assignPriority">
                            <option value="low">🟢 Low</option>
                            <option value="medium" selected>🟡 Medium</option>
                            <option value="high">🟠 High</option>
                            <option value="urgent">🔴 Urgent</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="assignDueDate" class="form-label">Due Date</label>
                        <input type="datetime-local" class="form-control" id="assignDueDate" min="{{ now()->format('Y-m-d\TH:i') }}">
                    </div>
                    <div class="mb-3">
                        <label for="assignNotes" class="form-label">Notes</label>
                        <textarea class="form-control" id="assignNotes" rows="3" placeholder="Add any instructions or notes..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning text-white" onclick="assignContent()" id="assignContentBtn">
                    <i class="bi bi-person-plus me-1"></i>Assign Content
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* SEO Analysis Gradient */
.btn-gradient-seo {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.btn-gradient-seo:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    color: white;
}

.bg-gradient-seo {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Calendar Gradient */
.btn-gradient-calendar {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    border: none;
}

.btn-gradient-calendar:hover {
    background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
    color: white;
}

.bg-gradient-calendar {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}
</style>

<!-- ============================================================== -->
<!-- PHASE 4: TRANSLATE MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="translateModal" tabindex="-1" aria-labelledby="translateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-translate text-white">
                <h5 class="modal-title" id="translateModalLabel">
                    <i class="bi bi-translate me-2"></i>Translate Content
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">Translate your content to multiple languages while preserving medical terminology accuracy.</p>
                
                <!-- Current Language -->
                <div class="alert alert-info d-flex align-items-center mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <div>
                        <strong>Current Language:</strong> 
                        @php
                            $currentLang = $content->language ?? 'en';
                            $languages = config('languages', []);
                            $currentLangName = $languages[$currentLang]['display'] ?? $currentLang;
                        @endphp
                        {{ $currentLangName }}
                    </div>
                </div>
                
                <!-- Target Language Selection -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Select Target Language</label>
                    <div class="row g-2" id="targetLanguages">
                        @php
                            $supportedLanguages = [
                                'en' => ['name' => 'English', 'flag' => '🇬🇧'],
                                'ar' => ['name' => 'Arabic', 'flag' => '🇸🇦'],
                                'de' => ['name' => 'German', 'flag' => '🇩🇪'],
                                'fr' => ['name' => 'French', 'flag' => '🇫🇷'],
                                'es' => ['name' => 'Spanish', 'flag' => '🇪🇸'],
                                'tr' => ['name' => 'Turkish', 'flag' => '🇹🇷'],
                                'it' => ['name' => 'Italian', 'flag' => '🇮🇹'],
                                'pt' => ['name' => 'Portuguese', 'flag' => '🇵🇹'],
                                'ru' => ['name' => 'Russian', 'flag' => '🇷🇺'],
                                'zh' => ['name' => 'Chinese', 'flag' => '🇨🇳'],
                            ];
                        @endphp
                        @foreach($supportedLanguages as $code => $lang)
                            @if($code !== $currentLang)
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="target_languages[]" 
                                           value="{{ $code }}" id="lang_{{ $code }}">
                                    <label class="form-check-label" for="lang_{{ $code }}">
                                        <span class="me-1">{{ $lang['flag'] }}</span> {{ $lang['name'] }}
                                    </label>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                
                <!-- Translation Options -->
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="preserveMedicalTerms" checked>
                        <label class="form-check-label" for="preserveMedicalTerms">
                            <i class="bi bi-heart-pulse text-danger me-1"></i>
                            Preserve medical terminology (recommended)
                        </label>
                    </div>
                </div>
                
                <!-- Existing Translations -->
                <div id="existingTranslations" class="mb-3" style="display: none;">
                    <label class="form-label fw-bold">Existing Translations</label>
                    <div id="translationsList" class="list-group"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-success" onclick="translateContent()">
                    <i class="bi bi-translate me-2"></i>Translate
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- PHASE 4: SAVE AS TEMPLATE MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="saveTemplateModal" tabindex="-1" aria-labelledby="saveTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-template text-white">
                <h5 class="modal-title" id="saveTemplateModalLabel">
                    <i class="bi bi-file-earmark-plus me-2"></i>Save as Template
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">Save this content structure as a reusable template for future use.</p>
                
                <!-- Template Name -->
                <div class="mb-3">
                    <label for="templateName" class="form-label fw-bold">Template Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="templateName" 
                           placeholder="e.g., Patient Education Article" required>
                </div>
                
                <!-- Template Description -->
                <div class="mb-3">
                    <label for="templateDescription" class="form-label fw-bold">Description</label>
                    <textarea class="form-control" id="templateDescription" rows="3" 
                              placeholder="Describe when to use this template..."></textarea>
                </div>
                
                <!-- Template Category -->
                <div class="mb-3">
                    <label for="templateCategory" class="form-label fw-bold">Category</label>
                    <select class="form-select" id="templateCategory">
                        <option value="general">General</option>
                        <option value="patient_education">Patient Education</option>
                        <option value="clinical_documentation">Clinical Documentation</option>
                        <option value="research">Research & Studies</option>
                        <option value="marketing">Healthcare Marketing</option>
                        <option value="social_media">Social Media</option>
                    </select>
                </div>
                
                <!-- Template Options -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Include in Template</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="includeStructure" checked>
                        <label class="form-check-label" for="includeStructure">
                            Content structure (headings, sections)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="includeTone" checked>
                        <label class="form-check-label" for="includeTone">
                            Tone and style settings
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="includePromptHints">
                        <label class="form-check-label" for="includePromptHints">
                            Topic/prompt hints
                        </label>
                    </div>
                </div>
                
                <!-- Share with Team -->
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="shareWithTeam">
                        <label class="form-check-label" for="shareWithTeam">
                            <i class="bi bi-people me-1"></i>Share with my team
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="saveAsTemplate()">
                    <i class="bi bi-save me-2"></i>Save Template
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- PHASE 4: ANALYTICS MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="analyticsModal" tabindex="-1" aria-labelledby="analyticsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-analytics text-white">
                <h5 class="modal-title" id="analyticsModalLabel">
                    <i class="bi bi-bar-chart-line me-2"></i>Content Analytics
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Analytics Loading -->
                <div id="analyticsLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mt-2">Loading analytics...</p>
                </div>
                
                <!-- Analytics Content -->
                <div id="analyticsContent" style="display: none;">
                    <!-- Performance Overview -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <i class="bi bi-eye fs-2 mb-2"></i>
                                    <h3 class="mb-0" id="analyticsViews">0</h3>
                                    <small>Views</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <i class="bi bi-clock fs-2 mb-2"></i>
                                    <h3 class="mb-0" id="analyticsReadTime">0m</h3>
                                    <small>Avg. Read Time</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <i class="bi bi-hand-thumbs-up fs-2 mb-2"></i>
                                    <h3 class="mb-0" id="analyticsEngagement">0%</h3>
                                    <small>Engagement</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <i class="bi bi-share fs-2 mb-2"></i>
                                    <h3 class="mb-0" id="analyticsShares">0</h3>
                                    <small>Shares</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content Statistics -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-file-text me-2"></i>Content Statistics</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex justify-content-between py-2 border-bottom">
                                            <span class="text-muted">Word Count</span>
                                            <span class="fw-bold" id="statsWordCount">{{ str_word_count(strip_tags($content->output_text ?? '')) }}</span>
                                        </li>
                                        <li class="d-flex justify-content-between py-2 border-bottom">
                                            <span class="text-muted">Character Count</span>
                                            <span class="fw-bold" id="statsCharCount">{{ strlen($content->output_text ?? '') }}</span>
                                        </li>
                                        <li class="d-flex justify-content-between py-2">
                                            <span class="text-muted">Reading Time</span>
                                            <span class="fw-bold" id="statsReadTime">{{ ceil(str_word_count(strip_tags($content->output_text ?? '')) / 200) }} min</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex justify-content-between py-2 border-bottom">
                                            <span class="text-muted">Created</span>
                                            <span class="fw-bold">{{ $content->created_at->diffForHumans() }}</span>
                                        </li>
                                        <li class="d-flex justify-content-between py-2 border-bottom">
                                            <span class="text-muted">Last Updated</span>
                                            <span class="fw-bold">{{ $content->updated_at->diffForHumans() }}</span>
                                        </li>
                                        <li class="d-flex justify-content-between py-2">
                                            <span class="text-muted">Version</span>
                                            <span class="fw-bold">{{ $content->version ?? 1 }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- SEO Score Summary -->
                    @if($content->seo_overall_score)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>SEO Performance</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="display-4 fw-bold text-{{ $content->seo_overall_score >= 80 ? 'success' : ($content->seo_overall_score >= 60 ? 'warning' : 'danger') }} me-3">
                                    {{ $content->seo_overall_score }}
                                </div>
                                <div>
                                    <div class="progress" style="width: 200px; height: 10px;">
                                        <div class="progress-bar bg-{{ $content->seo_overall_score >= 80 ? 'success' : ($content->seo_overall_score >= 60 ? 'warning' : 'danger') }}" 
                                             style="width: {{ $content->seo_overall_score }}%"></div>
                                    </div>
                                    <small class="text-muted">SEO Score</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Activity Timeline -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Activity Timeline</h6>
                        </div>
                        <div class="card-body">
                            <div id="activityTimeline">
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-success rounded-pill">Created</span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0">Content created</p>
                                        <small class="text-muted">{{ $content->created_at->format('M d, Y H:i') }}</small>
                                    </div>
                                </div>
                                @if($content->updated_at != $content->created_at)
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-info rounded-pill">Updated</span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0">Content updated</p>
                                        <small class="text-muted">{{ $content->updated_at->format('M d, Y H:i') }}</small>
                                    </div>
                                </div>
                                @endif
                                @if($content->last_seo_check)
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-primary rounded-pill">SEO</span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0">SEO analysis performed</p>
                                        <small class="text-muted">{{ $content->last_seo_check->format('M d, Y H:i') }}</small>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Close
                </button>
                <a href="{{ route('analytics.content', ['locale' => app()->getLocale(), 'id' => $content->id]) }}" class="btn btn-primary">
                    <i class="bi bi-box-arrow-up-right me-2"></i>Full Analytics
                </a>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * PHASE 4: Translation Functions
 */
function translateContent() {
    const selectedLanguages = Array.from(document.querySelectorAll('input[name="target_languages[]"]:checked')).map(el => el.value);
    const preserveMedicalTerms = document.getElementById('preserveMedicalTerms').checked;
    
    if (selectedLanguages.length === 0) {
        Swal.fire({ icon: 'warning', title: 'No Language Selected', text: 'Please select at least one target language.' });
        return;
    }
    
    Swal.fire({
        title: 'Translating Content...',
        html: `Translating to ${selectedLanguages.length} language(s).<br><small>This may take a moment.</small>`,
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    fetch("{{ route('content.translate', ['id' => $content->id]) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            target_languages: selectedLanguages,
            preserve_medical_terms: preserveMedicalTerms
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Translation Complete!',
                html: `Successfully translated to ${data.data?.translations?.length || selectedLanguages.length} language(s).`,
                confirmButtonText: 'View Translations',
                showCancelButton: true,
                cancelButtonText: 'Close'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Keep modal open and show translations
                    loadExistingTranslations();
                    loadSidebarTranslations();
                    // Scroll to translations section
                    document.getElementById('existingTranslations')?.scrollIntoView({ behavior: 'smooth' });
                } else {
                    bootstrap.Modal.getInstance(document.getElementById('translateModal'))?.hide();
                    // Still update sidebar
                    loadSidebarTranslations();
                }
            });
        } else {
            Swal.fire({ icon: 'error', title: 'Translation Failed', text: data.message || 'Failed to translate content.' });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({ icon: 'error', title: 'Error', text: 'Network error occurred.' });
    });
}

function loadExistingTranslations() {
    fetch("{{ route('content.translations.list', ['id' => $content->id]) }}", {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.data?.length > 0) {
            const container = document.getElementById('translationsList');
            const wrapper = document.getElementById('existingTranslations');
            wrapper.style.display = 'block';
            
            container.innerHTML = data.data.map(t => `
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${t.language_name}</strong>
                        ${t.quality_score ? `<span class="badge bg-info ms-2">${t.quality_score}% quality</span>` : ''}
                        <br><small class="text-muted">${t.preview || 'Translation available'}</small>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="viewTranslation('${t.language}', '${t.language_name}')">
                            <i class="bi bi-eye"></i> View
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="copyTranslation('${t.language}')">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>
            `).join('');
        } else {
            document.getElementById('existingTranslations').style.display = 'none';
        }
    })
    .catch(console.error);
}

function viewTranslation(langCode, langName) {
    Swal.fire({
        title: `<i class="bi bi-translate me-2"></i>Translation: ${langName}`,
        html: '<div class="text-center"><div class="spinner-border text-primary"></div><p class="mt-2">Loading...</p></div>',
        showConfirmButton: false,
        allowOutsideClick: false,
        width: '80%',
        customClass: { popup: 'text-start' }
    });
    
    fetch(`{{ url('/' . app()->getLocale() . '/content/' . $content->id . '/translation') }}/${langCode}`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.data?.translation) {
            Swal.fire({
                title: `<i class="bi bi-translate me-2"></i>Translation: ${langName}`,
                html: `
                    <div class="text-start" style="max-height: 60vh; overflow-y: auto;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            ${data.data.quality_score ? `<span class="badge bg-success">Quality: ${data.data.quality_score}%</span>` : ''}
                            <button class="btn btn-sm btn-outline-primary" onclick="navigator.clipboard.writeText(document.getElementById('translationText').innerText).then(() => Swal.showValidationMessage('Copied!'))">
                                <i class="bi bi-clipboard me-1"></i>Copy
                            </button>
                        </div>
                        <div id="translationText" class="border rounded p-3 bg-light" style="white-space: pre-wrap; direction: ${langCode === 'ar' ? 'rtl' : 'ltr'};">
                            ${data.data.translation.replace(/\n/g, '<br>')}
                        </div>
                    </div>
                `,
                width: '80%',
                showCloseButton: true,
                showConfirmButton: true,
                confirmButtonText: '<i class="bi bi-x-circle me-1"></i>Close',
                customClass: { popup: 'text-start' }
            });
        } else {
            Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Translation not found' });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load translation' });
    });
}

function copyTranslation(langCode) {
    fetch(`{{ url('/' . app()->getLocale() . '/content/' . $content->id . '/translation') }}/${langCode}`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.data?.translation) {
            navigator.clipboard.writeText(data.data.translation).then(() => {
                Swal.fire({ icon: 'success', title: 'Copied!', text: 'Translation copied to clipboard', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
            });
        }
    })
    .catch(console.error);
}

/**
 * PHASE 4: Template Functions
 */
function saveAsTemplate() {
    const name = document.getElementById('templateName').value.trim();
    const description = document.getElementById('templateDescription').value.trim();
    const category = document.getElementById('templateCategory').value;
    const includeStructure = document.getElementById('includeStructure').checked;
    const includeTone = document.getElementById('includeTone').checked;
    const includePromptHints = document.getElementById('includePromptHints').checked;
    const shareWithTeam = document.getElementById('shareWithTeam').checked;
    
    if (!name) {
        Swal.fire({ icon: 'warning', title: 'Name Required', text: 'Please enter a template name.' });
        return;
    }
    
    Swal.fire({
        title: 'Saving Template...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    fetch("{{ route('templates.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            name: name,
            description: description,
            category: category,
            content_id: {{ $content->id }},
            include_structure: includeStructure,
            include_tone: includeTone,
            include_prompt_hints: includePromptHints,
            share_with_team: shareWithTeam
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Template Saved!',
                html: `
                    <p>Your template "<strong>${name}</strong>" has been saved successfully.</p>
                    <p class="text-muted small">You can use this template when creating new content.</p>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-collection me-1"></i> View My Templates',
                cancelButtonText: 'Close',
                confirmButtonColor: '#4facfe'
            }).then((result) => {
                bootstrap.Modal.getInstance(document.getElementById('saveTemplateModal')).hide();
                document.getElementById('templateName').value = '';
                document.getElementById('templateDescription').value = '';
                
                if (result.isConfirmed) {
                    window.location.href = "{{ route('templates.index') }}";
                }
            });
        } else {
            Swal.fire({ icon: 'error', title: 'Save Failed', text: data.message || 'Failed to save template.' });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({ icon: 'error', title: 'Error', text: 'Network error occurred.' });
    });
}

/**
 * PHASE 4: Analytics Functions
 */
document.getElementById('analyticsModal')?.addEventListener('shown.bs.modal', function() {
    loadContentAnalytics();
});

function loadContentAnalytics() {
    const loading = document.getElementById('analyticsLoading');
    const content = document.getElementById('analyticsContent');
    
    loading.style.display = 'block';
    content.style.display = 'none';
    
    fetch("{{ route('analytics.content', ['id' => $content->id]) }}", {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        loading.style.display = 'none';
        content.style.display = 'block';
        
        if (data.success && data.data) {
            const analytics = data.data;
            document.getElementById('analyticsViews').textContent = analytics.views || 0;
            document.getElementById('analyticsReadTime').textContent = (analytics.avg_read_time || 0) + 'm';
            document.getElementById('analyticsEngagement').textContent = (analytics.engagement_rate || 0) + '%';
            document.getElementById('analyticsShares').textContent = analytics.shares || 0;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        loading.style.display = 'none';
        content.style.display = 'block';
    });
}

/**
 * FLOATING ACTION BUTTON Toggle
 */
function toggleFab() {
    document.getElementById('fabContainer').classList.toggle('active');
}

// Close FAB when clicking outside
document.addEventListener('click', function(e) {
    const fab = document.getElementById('fabContainer');
    if (fab && !fab.contains(e.target)) {
        fab.classList.remove('active');
    }
});

/**
 * Load Translations for Sidebar Panel
 */
function loadSidebarTranslations() {
    const listContainer = document.getElementById('sidebarTranslationsList');
    const noTranslationsMsg = document.getElementById('noTranslationsMessage');
    
    fetch("{{ route('content.translations.list', ['id' => $content->id]) }}", {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.data?.length > 0) {
            noTranslationsMsg.style.display = 'none';
            
            const flagMap = {
                'en': '🇬🇧', 'ar': '🇸🇦', 'de': '🇩🇪', 'fr': '🇫🇷', 'es': '🇪🇸',
                'tr': '🇹🇷', 'it': '🇮🇹', 'pt': '🇵🇹', 'ru': '🇷🇺', 'zh': '🇨🇳',
                'ja': '🇯🇵', 'ko': '🇰🇷', 'nl': '🇳🇱', 'pl': '🇵🇱', 'hi': '🇮🇳'
            };
            
            let html = data.data.map(t => `
                <div class="translation-item">
                    <div class="translation-lang">
                        <span class="translation-flag">${flagMap[t.language] || '🌐'}</span>
                        <span class="translation-name">${t.language_name}</span>
                        ${t.quality_score ? `<span class="translation-quality">${t.quality_score}%</span>` : ''}
                    </div>
                    <div class="translation-actions">
                        <button class="translation-btn translation-btn-view" onclick="viewTranslation('${t.language}', '${t.language_name}')" title="View">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="translation-btn translation-btn-copy" onclick="copyTranslation('${t.language}')" title="Copy">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>
            `).join('');
            
            listContainer.innerHTML = html + `<div id="noTranslationsMessage" class="no-translations" style="display:none;"><i class="bi bi-translate d-block"></i><p class="mb-2">No translations yet</p></div>`;
        } else {
            listContainer.innerHTML = `
                <div class="no-translations" id="noTranslationsMessage">
                    <i class="bi bi-translate d-block"></i>
                    <p class="mb-2">No translations yet</p>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#translateModal">
                        <i class="bi bi-plus me-1"></i> Create First Translation
                    </button>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error loading sidebar translations:', error);
    });
}

// Load sidebar translations on page load
document.addEventListener('DOMContentLoaded', function() {
    loadSidebarTranslations();
    loadTeams();
    loadComments();
    loadCommentsCount();
});

// Load existing translations when translate modal opens
document.getElementById('translateModal')?.addEventListener('shown.bs.modal', function() {
    loadExistingTranslations();
    loadSidebarTranslations();
});

// ============================================================
// TEAM COLLABORATION FUNCTIONS
// ============================================================

/**
 * Load user's teams
 */
function loadTeams() {
    const spinner = document.getElementById('teamsLoadingSpinner');
    const container = document.getElementById('teamsContainer');
    const teamsList = document.getElementById('teamsList');
    const noTeamsMsg = document.getElementById('noTeamsMessage');

    fetch("{{ route('teams.index') }}", {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        spinner.style.display = 'none';
        container.style.display = 'block';

        if (data.success && data.data?.length > 0) {
            noTeamsMsg.style.display = 'none';
            teamsList.innerHTML = data.data.map(team => {
                const canInvite = ['owner', 'admin'].includes(team.role);
                return `
                <div class="card mb-2 border-0 shadow-sm">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 fw-bold">${team.name}</h6>
                                <small class="text-muted">
                                    <i class="bi bi-people me-1"></i>${team.member_count} members
                                    <span class="mx-2">•</span>
                                    <i class="bi bi-file-text me-1"></i>${team.content_count} contents
                                </small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge ${team.role === 'owner' ? 'bg-primary' : team.role === 'admin' ? 'bg-success' : 'bg-secondary'}">${team.role}</span>
                                ${canInvite ? `
                                <button class="btn btn-sm btn-success" onclick="inviteMemberToTeam(${team.id}, '${team.name.replace(/'/g, "\\'")}')" title="Invite Member">
                                    <i class="bi bi-person-plus"></i>
                                </button>
                                ` : ''}
                                <button class="btn btn-sm btn-outline-primary" onclick="viewTeam(${team.id})" title="View Team">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `}).join('');

            // Also populate the assign team select
            populateAssignTeamSelect(data.data);
        } else {
            noTeamsMsg.style.display = 'block';
            teamsList.innerHTML = '';
        }
    })
    .catch(err => {
        spinner.style.display = 'none';
        container.style.display = 'block';
        noTeamsMsg.style.display = 'block';
        console.error('Error loading teams:', err);
    });
}

/**
 * Populate team select in assign modal
 */
function populateAssignTeamSelect(teams) {
    const select = document.getElementById('assignTeamSelect');
    select.innerHTML = '<option value="">Choose a team...</option>';
    teams.forEach(team => {
        select.innerHTML += `<option value="${team.id}">${team.name}</option>`;
    });
}

/**
 * View team details
 */
function viewTeam(teamId) {
    fetch(`{{ url(app()->getLocale()) }}/teams/${teamId}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const team = data.data;
            const canInvite = ['owner', 'admin'].includes(team.user_role);
            
            // Build members list
            let membersHtml = '<div class="list-group list-group-flush">';
            if (team.members && team.members.length > 0) {
                team.members.forEach(member => {
                    const roleClass = member.role === 'owner' ? 'bg-primary' : 
                                     member.role === 'admin' ? 'bg-success' : 
                                     member.role === 'editor' ? 'bg-info' : 'bg-secondary';
                    const statusBadge = member.status === 'pending' ? 
                        '<span class="badge bg-warning ms-1">Pending</span>' : '';
                    membersHtml += `
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <strong>${member.user?.name || member.pending_email || 'Unknown'}</strong>
                                <br><small class="text-muted">${member.user?.email || ''}</small>
                            </div>
                            <div>
                                <span class="badge ${roleClass}">${member.role}</span>
                                ${statusBadge}
                            </div>
                        </div>
                    `;
                });
            } else {
                membersHtml += '<div class="text-muted text-center py-2">No members yet</div>';
            }
            membersHtml += '</div>';

            Swal.fire({
                title: `<i class="bi bi-people-fill text-primary"></i> ${team.name}`,
                html: `
                    <div class="text-start">
                        ${team.description ? `<p class="text-muted">${team.description}</p>` : ''}
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body py-2 text-center">
                                        <div class="fs-4 fw-bold text-primary">${team.members?.length || 0}</div>
                                        <small class="text-muted">Members</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body py-2 text-center">
                                        <div class="fs-4 fw-bold text-success">${team.statistics?.content?.total || 0}</div>
                                        <small class="text-muted">Contents</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-2"><i class="bi bi-people me-2"></i>Team Members</h6>
                        ${membersHtml}
                    </div>
                `,
                width: 500,
                showConfirmButton: canInvite,
                confirmButtonText: '<i class="bi bi-person-plus me-2"></i>Invite Member',
                confirmButtonColor: '#6366f1',
                showCancelButton: true,
                cancelButtonText: 'Close',
            }).then((result) => {
                if (result.isConfirmed) {
                    inviteMemberToTeam(teamId, team.name);
                }
            });
        }
    });
}

/**
 * Invite a member to a team
 */
function inviteMemberToTeam(teamId, teamName) {
    // Close the team modal first to avoid z-index issues
    const teamModal = document.getElementById('teamCollaborationModal');
    if (teamModal) {
        const modalInstance = bootstrap.Modal.getInstance(teamModal);
        if (modalInstance) {
            modalInstance.hide();
        }
    }

    // Remove modal backdrop if still present
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';

    // Small delay to ensure modal is fully closed
    setTimeout(() => {
        // Step 1: Get email
        Swal.fire({
            title: 'Invite to ' + teamName,
            text: 'Enter the email address of the person you want to invite:',
            input: 'email',
            inputPlaceholder: 'email@example.com',
            showCancelButton: true,
            confirmButtonText: 'Next',
            confirmButtonColor: '#6366f1',
            cancelButtonText: 'Cancel',
            inputValidator: (value) => {
                if (!value) {
                    return 'Please enter an email address';
                }
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    return 'Please enter a valid email address';
                }
            }
        }).then((emailResult) => {
            if (emailResult.isConfirmed && emailResult.value) {
                const email = emailResult.value;
                
                // Step 2: Get role
                Swal.fire({
                    title: 'Select Role',
                    text: 'Choose the role for ' + email,
                    input: 'select',
                    inputOptions: {
                        'editor': 'Editor - Can create and edit content',
                        'reviewer': 'Reviewer - Can view and comment',
                        'viewer': 'Viewer - Can only view content',
                        'admin': 'Admin - Full access except ownership'
                    },
                    inputPlaceholder: 'Select a role',
                    showCancelButton: true,
                    confirmButtonText: '<i class="bi bi-send me-2"></i>Send Invitation',
                    confirmButtonColor: '#6366f1',
                    cancelButtonText: 'Back',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Please select a role';
                        }
                    },
                    showLoaderOnConfirm: true,
                    preConfirm: (role) => {
                        return fetch(`{{ url(app()->getLocale()) }}/teams/${teamId}/invite`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ email: email, role: role })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                throw new Error(data.message || 'Failed to send invitation');
                            }
                            return data;
                        })
                        .catch(error => {
                            Swal.showValidationMessage(error.message);
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((roleResult) => {
                    if (roleResult.isConfirmed && roleResult.value?.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Invitation Sent!',
                            text: 'The invitation email has been sent to ' + email,
                            confirmButtonColor: '#6366f1'
                        });
                    } else if (roleResult.dismiss === Swal.DismissReason.cancel) {
                        // Go back to email input
                        inviteMemberToTeam(teamId, teamName);
                    }
                });
            }
        });
    }, 350);
}

/**
 * Load pending invitations
 */
function loadInvitations() {
    const spinner = document.getElementById('invitationsLoadingSpinner');
    const container = document.getElementById('invitationsContainer');
    const list = document.getElementById('invitationsList');
    const noMsg = document.getElementById('noInvitationsMessage');
    const countBadge = document.querySelector('.invitations-count');

    fetch("{{ route('teams.invitations.pending') }}", {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        spinner.style.display = 'none';
        container.style.display = 'block';

        if (data.success && data.data?.length > 0) {
            noMsg.style.display = 'none';
            countBadge.textContent = data.data.length;
            countBadge.style.display = 'inline';

            list.innerHTML = data.data.map(inv => `
                <div class="card mb-2 border-0 shadow-sm ${inv.is_expired ? 'opacity-50' : ''}">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 fw-bold">${inv.workspace?.name || 'Unknown Team'}</h6>
                                <small class="text-muted">
                                    <i class="bi bi-person me-1"></i>Invited by ${inv.inviter?.name || 'Unknown'}
                                    <span class="mx-2">•</span>
                                    ${inv.invited_at}
                                </small>
                                <div class="mt-1">
                                    <span class="badge bg-info">${inv.role}</span>
                                    ${inv.is_expired ? '<span class="badge bg-danger ms-1">Expired</span>' : ''}
                                </div>
                            </div>
                            ${!inv.is_expired ? `
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-success" onclick="acceptInvitation(${inv.id})">
                                        <i class="bi bi-check-lg"></i> Accept
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="declineInvitation(${inv.id})">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            noMsg.style.display = 'block';
            list.innerHTML = '';
            countBadge.style.display = 'none';
        }
    })
    .catch(err => {
        spinner.style.display = 'none';
        container.style.display = 'block';
        noMsg.style.display = 'block';
        console.error('Error loading invitations:', err);
    });
}

/**
 * Accept an invitation
 */
function acceptInvitation(invitationId) {
    Swal.fire({
        title: 'Accept Invitation?',
        text: 'You will join this team and be able to collaborate on content.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Accept',
        confirmButtonColor: '#10b981'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ url(app()->getLocale()) }}/invitations/${invitationId}/accept`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Joined!', 'You are now a team member.', 'success');
                    loadInvitations();
                    loadTeams();
                } else {
                    Swal.fire('Error', data.message || 'Failed to accept invitation', 'error');
                }
            });
        }
    });
}

/**
 * Decline an invitation
 */
function declineInvitation(invitationId) {
    Swal.fire({
        title: 'Decline Invitation?',
        text: 'You will not be able to join this team unless invited again.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Decline',
        confirmButtonColor: '#ef4444'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ url(app()->getLocale()) }}/invitations/${invitationId}/decline`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Declined', 'Invitation has been declined.', 'info');
                    loadInvitations();
                } else {
                    Swal.fire('Error', data.message || 'Failed to decline invitation', 'error');
                }
            });
        }
    });
}

/**
 * Create new team
 */
document.getElementById('createTeamForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('createTeamBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';

    fetch("{{ route('teams.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            name: document.getElementById('teamName').value,
            description: document.getElementById('teamDescription').value,
            plan: document.getElementById('teamPlan').value
        })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Create Team';

        if (data.success) {
            Swal.fire('Success!', 'Team created successfully', 'success');
            document.getElementById('createTeamForm').reset();
            document.getElementById('myTeamsTab').click();
            loadTeams();
        } else {
            Swal.fire('Error', data.message || 'Failed to create team', 'error');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Create Team';
        Swal.fire('Error', 'An error occurred', 'error');
    });
});

// ============================================================
// COMMENTS FUNCTIONS
// ============================================================

/**
 * Load comments for this content
 */
function loadComments() {
    const spinner = document.getElementById('commentsLoadingSpinner');
    const container = document.getElementById('commentsContainer');
    const commentsList = document.getElementById('commentsList');
    const noCommentsMsg = document.getElementById('noCommentsMessage');
    const countSpan = document.getElementById('commentsCount');

    fetch("{{ url(app()->getLocale()) }}/content/{{ $content->id }}/comments", {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        spinner.style.display = 'none';
        container.style.display = 'block';

        if (data.success && data.data?.length > 0) {
            noCommentsMsg.style.display = 'none';
            countSpan.textContent = data.data.length;
            
            commentsList.innerHTML = data.data.map(comment => renderComment(comment)).join('');
        } else {
            noCommentsMsg.style.display = 'block';
            commentsList.innerHTML = '';
            countSpan.textContent = '0';
        }
    })
    .catch(err => {
        spinner.style.display = 'none';
        container.style.display = 'block';
        console.error('Error loading comments:', err);
    });
}

/**
 * Load comments count for badge
 */
function loadCommentsCount() {
    fetch("{{ url(app()->getLocale()) }}/content/{{ $content->id }}/comments", {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.data?.length > 0) {
            const badge = document.querySelector('.comments-count-badge');
            if (badge) {
                badge.textContent = data.data.length;
                badge.style.display = 'inline';
            }
        }
    })
    .catch(err => console.log('Could not load comments count'));
}

/**
 * Render a comment HTML
 */
function renderComment(comment) {
    const isResolved = comment.is_resolved;
    return `
        <div class="card mb-2 ${isResolved ? 'border-success bg-light' : ''}">
            <div class="card-body py-2">
                <div class="d-flex gap-2">
                    <div class="avatar-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 50%; font-size: 12px;">
                        ${comment.user?.name?.charAt(0)?.toUpperCase() || 'U'}
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong class="small">${comment.user?.name || 'Unknown'}</strong>
                                <span class="text-muted small ms-2">${comment.created_at_human || ''}</span>
                                ${isResolved ? '<span class="badge bg-success ms-2 small">Resolved</span>' : ''}
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-link text-muted p-0" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item small" href="#" onclick="replyToComment(${comment.id})"><i class="bi bi-reply me-2"></i>Reply</a></li>
                                    ${!isResolved ? `<li><a class="dropdown-item small" href="#" onclick="resolveComment(${comment.id})"><i class="bi bi-check-circle me-2"></i>Resolve</a></li>` : ''}
                                </ul>
                            </div>
                        </div>
                        <p class="mb-1 small">${comment.comment}</p>
                        ${comment.annotated_text ? `<div class="bg-warning-subtle px-2 py-1 rounded small"><i class="bi bi-highlighter me-1"></i>"${comment.annotated_text}"</div>` : ''}
                        ${comment.replies?.length > 0 ? `
                            <div class="ms-3 mt-2 border-start ps-2">
                                ${comment.replies.map(reply => renderComment(reply)).join('')}
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Add new comment
 */
function addComment() {
    const commentText = document.getElementById('newCommentText').value.trim();
    if (!commentText) {
        Swal.fire('Error', 'Please enter a comment', 'warning');
        return;
    }

    const payload = { comment: commentText };
    
    // Check if annotation mode is enabled
    if (document.getElementById('annotateText').checked) {
        const selection = window.getSelection();
        if (selection.toString().trim()) {
            payload.annotated_text = selection.toString();
        }
    }

    fetch("{{ route('content.comments.add', ['id' => $content->id]) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('newCommentText').value = '';
            document.getElementById('annotateText').checked = false;
            loadComments();
            loadCommentsCount();
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Comment added!',
                showConfirmButton: false,
                timer: 2000
            });
        } else {
            Swal.fire('Error', data.message || 'Failed to add comment', 'error');
        }
    })
    .catch(err => {
        Swal.fire('Error', 'An error occurred', 'error');
    });
}

/**
 * Reply to a comment
 */
function replyToComment(commentId) {
    Swal.fire({
        title: 'Reply to Comment',
        input: 'textarea',
        inputPlaceholder: 'Type your reply...',
        showCancelButton: true,
        confirmButtonText: 'Reply',
        confirmButtonColor: '#14b8a6'
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            fetch(`{{ url(app()->getLocale()) }}/comments/${commentId}/reply`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ comment: result.value })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    loadComments();
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Reply added!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        }
    });
}

/**
 * Resolve a comment
 */
function resolveComment(commentId) {
    fetch(`{{ url(app()->getLocale()) }}/comments/${commentId}/resolve`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            loadComments();
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Comment resolved!',
                showConfirmButton: false,
                timer: 2000
            });
        }
    });
}

/**
 * Filter comments
 */
function filterComments(filter) {
    const buttons = document.querySelectorAll('#commentsContainer .btn-group .btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    const cards = document.querySelectorAll('#commentsList .card');
    cards.forEach(card => {
        const isResolved = card.classList.contains('border-success');
        if (filter === 'all') {
            card.style.display = 'block';
        } else if (filter === 'resolved') {
            card.style.display = isResolved ? 'block' : 'none';
        } else if (filter === 'unresolved') {
            card.style.display = !isResolved ? 'block' : 'none';
        }
    });
}

// ============================================================
// ASSIGNMENT FUNCTIONS
// ============================================================

/**
 * Team select change - load members
 */
document.getElementById('assignTeamSelect')?.addEventListener('change', function() {
    const teamId = this.value;
    const memberSelect = document.getElementById('assignMemberSelect');
    
    if (!teamId) {
        memberSelect.innerHTML = '<option value="">First select a team...</option>';
        memberSelect.disabled = true;
        return;
    }

    memberSelect.innerHTML = '<option value="">Loading members...</option>';
    memberSelect.disabled = true;

    fetch(`{{ url(app()->getLocale()) }}/teams/${teamId}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.data?.members?.length > 0) {
            memberSelect.innerHTML = '<option value="">Select a member...</option>';
            data.data.members.forEach(member => {
                memberSelect.innerHTML += `<option value="${member.user_id}">${member.user?.name || 'Unknown'} (${member.role})</option>`;
            });
            memberSelect.disabled = false;
        } else {
            memberSelect.innerHTML = '<option value="">No members found</option>';
        }
    })
    .catch(err => {
        memberSelect.innerHTML = '<option value="">Error loading members</option>';
    });
});

/**
 * Assign content to team member
 */
function assignContent() {
    const teamId = document.getElementById('assignTeamSelect').value;
    const memberId = document.getElementById('assignMemberSelect').value;
    
    if (!teamId || !memberId) {
        Swal.fire('Error', 'Please select a team and member', 'warning');
        return;
    }

    const btn = document.getElementById('assignContentBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Assigning...';

    fetch("{{ route('content.assign', ['id' => $content->id]) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            team_workspace_id: teamId,
            assigned_to: memberId,
            priority: document.getElementById('assignPriority').value,
            due_date: document.getElementById('assignDueDate').value,
            notes: document.getElementById('assignNotes').value
        })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-person-plus me-1"></i>Assign Content';

        if (data.success) {
            Swal.fire('Success!', 'Content assigned successfully', 'success');
            bootstrap.Modal.getInstance(document.getElementById('assignContentModal')).hide();
        } else {
            Swal.fire('Error', data.message || 'Failed to assign content', 'error');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-person-plus me-1"></i>Assign Content';
        Swal.fire('Error', 'An error occurred', 'error');
    });
}

// Load teams when modal opens
document.getElementById('teamCollaborationModal')?.addEventListener('shown.bs.modal', function() {
    loadTeams();
    loadInvitations();
});

// Load invitations when tab is clicked
document.getElementById('invitationsTab')?.addEventListener('click', function() {
    loadInvitations();
});

// Load comments when modal opens
document.getElementById('contentCommentsModal')?.addEventListener('shown.bs.modal', function() {
    loadComments();
});

// Load teams for assign modal
document.getElementById('assignContentModal')?.addEventListener('shown.bs.modal', function() {
    loadTeams();
});
</script>

<!-- ==========================================
     PowerPoint Export Modal - Like Gamma/Beautiful.ai
     ========================================== -->
<div class="modal fade" id="pptxThemeModal" tabindex="-1" aria-labelledby="pptxThemeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="pptxThemeModalLabel">
                    <i class="bi bi-file-earmark-ppt me-2"></i>{{ __('translation.content_generator.pptx.modal_title') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Progress Steps -->
                <div class="d-flex justify-content-center mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary rounded-pill pptx-step-badge active" data-step="1">1</span>
                        <small class="text-muted d-none d-sm-inline">{{ __('translation.content_generator.pptx.step_style') }}</small>
                        <i class="bi bi-chevron-right text-muted mx-2"></i>
                        <span class="badge bg-secondary rounded-pill pptx-step-badge" data-step="2">2</span>
                        <small class="text-muted d-none d-sm-inline">{{ __('translation.content_generator.pptx.step_theme') }}</small>
                        <i class="bi bi-chevron-right text-muted mx-2"></i>
                        <span class="badge bg-secondary rounded-pill pptx-step-badge" data-step="3">3</span>
                        <small class="text-muted d-none d-sm-inline">{{ __('translation.content_generator.pptx.step_export') }}</small>
                    </div>
                </div>
                
                <!-- Hidden Inputs -->
                <input type="hidden" id="selectedPptxTheme" value="professional_blue">
                <input type="hidden" id="selectedPptxStyle" value="educational">
                <input type="hidden" id="selectedDetailLevel" value="standard">
                
                <!-- Step 1: Presentation Style -->
                <div id="pptxStep1">
                    <h6 class="fw-bold mb-3">{{ __('translation.content_generator.pptx.choose_style') }}</h6>
                    
                    <div class="row g-2 mb-4">
                        @php $firstStyle = true; @endphp
                        @foreach($pptxStyles as $styleKey => $style)
                            <div class="col-md-4 col-6">
                                <div class="card h-100 {{ $firstStyle ? 'border-primary' : '' }} pptx-style-card" data-style="{{ $styleKey }}" onclick="selectPptxStyle('{{ $styleKey }}')">
                                    <div class="card-body text-center p-3">
                                        <div class="fs-2 mb-2">{{ $style['icon'] }}</div>
                                        <h6 class="card-title mb-1">{{ $style['name'][$currentLocale] ?? $style['name']['en'] }}</h6>
                                        <small class="text-muted">{{ $style['description'][$currentLocale] ?? $style['description']['en'] }}</small>
                                        <i class="bi bi-check-circle-fill text-primary position-absolute top-0 end-0 m-2 pptx-check-icon {{ !$firstStyle ? 'd-none' : '' }}"></i>
                                    </div>
                                </div>
                            </div>
                            @php $firstStyle = false; @endphp
                        @endforeach
                    </div>
                    
                    <!-- Detail Level -->
                    <h6 class="fw-bold mb-3">{{ __('translation.content_generator.pptx.detail_level') }}</h6>
                    <div class="btn-group w-100" role="group">
                        @php 
                            $detailIcons = ['brief' => 'bi-lightning-charge', 'standard' => 'bi-check2-circle', 'detailed' => 'bi-list-check', 'comprehensive' => 'bi-book'];
                            $detailSlides = ['brief' => '5-10', 'standard' => '10-15', 'detailed' => '15-25', 'comprehensive' => '25+'];
                        @endphp
                        @foreach($pptxDetails as $detailKey => $detail)
                            <input type="radio" class="btn-check" name="detailLevel" id="level{{ ucfirst($detailKey) }}" value="{{ $detailKey }}" {{ $detailKey === 'standard' ? 'checked' : '' }} onclick="selectDetailLevel('{{ $detailKey }}')">
                            <label class="btn btn-outline-primary" for="level{{ ucfirst($detailKey) }}">
                                <i class="bi {{ $detailIcons[$detailKey] ?? 'bi-circle' }}"></i><br>
                                <small>{{ $detail['name'][$currentLocale] ?? $detail['name']['en'] }}<br>{{ $detailSlides[$detailKey] ?? '' }} {{ __('translation.content_generator.pptx.slides') }}</small>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <!-- Step 2: Theme Selection -->
                <div id="pptxStep2" class="d-none">
                    <h6 class="fw-bold mb-3">{{ __('translation.content_generator.pptx.choose_theme') }}</h6>
                    
                    <div class="row g-2">
                        @php
                            $themeGradients = [
                                'professional_blue' => 'linear-gradient(135deg,#1D4ED8,#3B82F6)',
                                'medical_green' => 'linear-gradient(135deg,#047857,#10B981)',
                                'academic_purple' => 'linear-gradient(135deg,#6D28D9,#8B5CF6)',
                                'modern_dark' => 'linear-gradient(135deg,#18181B,#27272A)',
                                'clean_minimal' => 'linear-gradient(135deg,#FAFAFA,#F4F4F5)',
                                'healthcare_teal' => 'linear-gradient(135deg,#0F766E,#14B8A6)',
                                'gradient_sunset' => 'linear-gradient(135deg,#DC2626,#F97316)',
                                'scientific_navy' => 'linear-gradient(135deg,#1E3A8A,#3B82F6)',
                            ];
                            $firstTheme = true;
                        @endphp
                        @foreach($pptxThemes as $themeKey => $theme)
                            <div class="col-lg-3 col-md-4 col-6">
                                <div class="card h-100 {{ $firstTheme ? 'border-primary' : '' }} pptx-theme-card" data-theme="{{ $themeKey }}" onclick="selectPptxTheme('{{ $themeKey }}')">
                                    <div class="card-body p-2 text-center">
                                        <div class="rounded mb-2 {{ $themeKey === 'clean_minimal' ? 'border' : '' }}" style="height:60px;background:{{ $themeGradients[$themeKey] ?? 'linear-gradient(135deg,#2563EB,#3B82F6)' }};"></div>
                                        <small class="fw-semibold">{{ $theme['name'] }}</small>
                                        <i class="bi bi-check-circle-fill text-primary position-absolute top-0 end-0 m-1 pptx-check-icon {{ !$firstTheme ? 'd-none' : '' }}"></i>
                                    </div>
                                </div>
                            </div>
                            @php $firstTheme = false; @endphp
                        @endforeach
                    </div>
                </div>
                
                <!-- Step 3: Export Preview -->
                <div id="pptxStep3" class="d-none">
                    <h6 class="fw-bold mb-3">{{ __('translation.content_generator.pptx.preview_title') }}</h6>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="rounded p-3 text-white text-center" id="slidePreview" style="background:linear-gradient(135deg,#1D4ED8,#3B82F6);min-height:150px;">
                                        <div class="fs-4 mb-1" id="previewStyleIcon">🎓</div>
                                        <h6 id="previewTitle" class="mb-1">{{ Str::limit($content->title ?? 'Presentation', 35) }}</h6>
                                        <small id="previewSpecialty" class="opacity-75">{{ $content->specialty?->name ?? '' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex align-items-center">
                                    <i class="bi bi-file-earmark-ppt text-danger me-3 fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block">{{ __('translation.content_generator.pptx.file_name') }}</small>
                                        <span class="fw-semibold text-truncate d-block" id="exportFileName2" style="max-width:200px;">{{ Str::limit($content->title ?? 'Presentation', 25) }}.pptx</span>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <span class="me-3 fs-4" id="styleInfoIcon">🎓</span>
                                    <div>
                                        <small class="text-muted d-block">{{ __('translation.content_generator.pptx.presentation_style') }}</small>
                                        <span class="fw-semibold" id="exportStyleName">{{ __('translation.content_generator.pptx.style_educational') }}</span>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <i class="bi bi-palette2 text-primary me-3 fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block">{{ __('translation.content_generator.pptx.selected_theme') }}</small>
                                        <span class="fw-semibold" id="exportThemeName">{{ __('translation.content_generator.pptx.theme_professional_blue') }}</span>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <i class="bi bi-layers text-success me-3 fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block">{{ __('translation.content_generator.pptx.detail_level') }}</small>
                                        <span class="fw-semibold" id="exportDetailName">{{ __('translation.content_generator.pptx.detail_standard') }} (10-15 {{ __('translation.content_generator.pptx.slides') }})</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-success-subtle text-success me-1"><i class="bi bi-check2 me-1"></i>{{ __('translation.content_generator.pptx.feature_professional') }}</span>
                                <span class="badge bg-success-subtle text-success me-1"><i class="bi bi-check2 me-1"></i>{{ __('translation.content_generator.pptx.feature_editable') }}</span>
                                <span class="badge bg-success-subtle text-success"><i class="bi bi-check2 me-1"></i>{{ __('translation.content_generator.pptx.feature_office') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="pptxBackBtn" onclick="pptxPrevStep()" style="display:none;">
                    <i class="bi bi-arrow-left me-1"></i>{{ __('translation.content_generator.pptx.btn_back') }}
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>{{ __('translation.content_generator.pptx.btn_cancel') }}
                </button>
                <button type="button" class="btn btn-primary" id="pptxNextBtn" onclick="pptxNextStep()">
                    {{ __('translation.content_generator.pptx.btn_next') }}<i class="bi bi-arrow-right ms-1"></i>
                </button>
                <button type="button" class="btn btn-success d-none" id="exportPptxBtn" onclick="exportPowerPoint()">
                    <i class="bi bi-download me-2"></i>{{ __('translation.content_generator.pptx.btn_export') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// PowerPoint Export Wizard
let currentPptxStep = 1;
const totalPptxSteps = 3;

// Theme gradients for preview
const themeGradients = {
    'professional_blue': 'linear-gradient(135deg, #1D4ED8, #3B82F6)',
    'medical_green': 'linear-gradient(135deg, #047857, #10B981)',
    'academic_purple': 'linear-gradient(135deg, #6D28D9, #8B5CF6)',
    'modern_dark': 'linear-gradient(135deg, #18181B, #27272A)',
    'clean_minimal': 'linear-gradient(135deg, #FAFAFA, #F4F4F5)',
    'healthcare_teal': 'linear-gradient(135deg, #0F766E, #14B8A6)',
    'gradient_sunset': 'linear-gradient(135deg, #DC2626, #F97316)',
    'scientific_navy': 'linear-gradient(135deg, #1E3A8A, #3B82F6)'
};

// Style icons - dynamically from PHP
const styleIcons = @json(collect($pptxStyles)->mapWithKeys(fn($s, $k) => [$k => $s['icon']]));

// Detail level slide counts
const detailSlideCounts = {
    'brief': '5-10',
    'standard': '10-15',
    'detailed': '15-25',
    'comprehensive': '25+'
};

function pptxNextStep() {
    if (currentPptxStep < totalPptxSteps) {
        document.getElementById('pptxStep' + currentPptxStep).classList.add('d-none');
        currentPptxStep++;
        document.getElementById('pptxStep' + currentPptxStep).classList.remove('d-none');
        updatePptxNavigation();
        updatePptxStepBadges();
        
        if (currentPptxStep === 3) {
            updateExportPreview();
        }
    }
}

function pptxPrevStep() {
    if (currentPptxStep > 1) {
        document.getElementById('pptxStep' + currentPptxStep).classList.add('d-none');
        currentPptxStep--;
        document.getElementById('pptxStep' + currentPptxStep).classList.remove('d-none');
        updatePptxNavigation();
        updatePptxStepBadges();
    }
}

function updatePptxNavigation() {
    const backBtn = document.getElementById('pptxBackBtn');
    const nextBtn = document.getElementById('pptxNextBtn');
    const exportBtn = document.getElementById('exportPptxBtn');
    
    backBtn.style.display = currentPptxStep > 1 ? 'inline-block' : 'none';
    
    if (currentPptxStep === totalPptxSteps) {
        nextBtn.classList.add('d-none');
        exportBtn.classList.remove('d-none');
    } else {
        nextBtn.classList.remove('d-none');
        exportBtn.classList.add('d-none');
    }
}

function updatePptxStepBadges() {
    document.querySelectorAll('.pptx-step-badge').forEach((badge, index) => {
        const stepNum = index + 1;
        badge.classList.remove('bg-primary', 'bg-secondary', 'bg-success');
        if (stepNum < currentPptxStep) {
            badge.classList.add('bg-success');
        } else if (stepNum === currentPptxStep) {
            badge.classList.add('bg-primary');
        } else {
            badge.classList.add('bg-secondary');
        }
    });
}

function selectPptxStyle(style) {
    // Update cards
    document.querySelectorAll('.pptx-style-card').forEach(card => {
        card.classList.remove('border-primary');
        card.querySelector('.pptx-check-icon')?.classList.add('d-none');
    });
    const selectedCard = document.querySelector(`.pptx-style-card[data-style="${style}"]`);
    if (selectedCard) {
        selectedCard.classList.add('border-primary');
        selectedCard.querySelector('.pptx-check-icon')?.classList.remove('d-none');
    }
    document.getElementById('selectedPptxStyle').value = style;
}

function selectDetailLevel(level) {
    document.getElementById('selectedDetailLevel').value = level;
}

function selectPptxTheme(themeKey) {
    // Update cards
    document.querySelectorAll('.pptx-theme-card').forEach(card => {
        card.classList.remove('border-primary');
        card.querySelector('.pptx-check-icon')?.classList.add('d-none');
    });
    const selectedCard = document.querySelector(`.pptx-theme-card[data-theme="${themeKey}"]`);
    if (selectedCard) {
        selectedCard.classList.add('border-primary');
        selectedCard.querySelector('.pptx-check-icon')?.classList.remove('d-none');
    }
    document.getElementById('selectedPptxTheme').value = themeKey;
}

function updateExportPreview() {
    const styles = @json(collect($pptxStyles)->mapWithKeys(fn($s, $k) => [$k => $s['name'][$currentLocale] ?? $s['name']['en']]));
    
    const themes = @json(collect($pptxThemes)->mapWithKeys(fn($t, $k) => [$k => $t['name']]));
    
    const details = @json(collect($pptxDetails)->mapWithKeys(fn($d, $k) => [$k => $d['name'][$currentLocale] ?? $d['name']['en']]));
    
    const style = document.getElementById('selectedPptxStyle').value;
    const theme = document.getElementById('selectedPptxTheme').value;
    const detail = document.getElementById('selectedDetailLevel').value;
    
    // Update slide preview
    const slidePreview = document.getElementById('slidePreview');
    if (slidePreview) {
        slidePreview.style.background = themeGradients[theme] || themeGradients['professional_blue'];
        
        // Adjust text color for light themes
        if (theme === 'clean_minimal') {
            slidePreview.querySelectorAll('h6, small').forEach(el => {
                el.style.color = '#1f2937';
            });
        } else {
            slidePreview.querySelectorAll('h6').forEach(el => el.style.color = 'white');
            slidePreview.querySelectorAll('small').forEach(el => el.style.color = 'rgba(255,255,255,0.8)');
        }
    }
    
    // Update style icon in preview
    const previewStyleIcon = document.getElementById('previewStyleIcon');
    if (previewStyleIcon) {
        previewStyleIcon.textContent = styleIcons[style] || '🎓';
    }
    
    // Update info panel
    const styleName = document.getElementById('exportStyleName');
    const themeName = document.getElementById('exportThemeName');
    const detailName = document.getElementById('exportDetailName');
    const styleIcon = document.getElementById('styleInfoIcon');
    
    if (styleName) styleName.textContent = styles[style] || style;
    if (themeName) themeName.textContent = themes[theme] || theme;
    if (detailName) detailName.textContent = (details[detail] || detail) + ' (' + (detailSlideCounts[detail] || '10-15') + ' {{ __("translation.content_generator.pptx.slides") }})';
    if (styleIcon) styleIcon.textContent = styleIcons[style] || '🎓';
}

function exportPowerPoint() {
    const theme = document.getElementById('selectedPptxTheme').value;
    const style = document.getElementById('selectedPptxStyle').value;
    const detail = document.getElementById('selectedDetailLevel').value;
    
    const exportUrl = "{{ route('content.export.pptx', $content->id) }}?theme=" + theme + "&style=" + style + "&detail=" + detail;
    
    const btn = document.getElementById('exportPptxBtn');
    const originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __("translation.content_generator.pptx.generating") }}';
    
    // Download
    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.src = exportUrl;
    document.body.appendChild(iframe);
    
    setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = originalContent;
        bootstrap.Modal.getInstance(document.getElementById('pptxThemeModal')).hide();
        
        // Reset wizard
        currentPptxStep = 1;
        document.getElementById('pptxStep1').classList.remove('d-none');
        document.getElementById('pptxStep2').classList.add('d-none');
        document.getElementById('pptxStep3').classList.add('d-none');
        updatePptxNavigation();
        updatePptxStepBadges();
        
        setTimeout(() => iframe.remove(), 5000);
    }, 3000);
}

// Reset wizard when modal opens
document.getElementById('pptxThemeModal')?.addEventListener('show.bs.modal', function() {
    currentPptxStep = 1;
    document.getElementById('pptxStep1').classList.remove('d-none');
    document.getElementById('pptxStep2').classList.add('d-none');
    document.getElementById('pptxStep3').classList.add('d-none');
    updatePptxNavigation();
    updatePptxStepBadges();
});
</script>

@endsection
