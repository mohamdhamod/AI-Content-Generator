@extends('layout.home.main')

@section('title', __('translation.content_generator.analytics_page.title'))

@section('content')
@php
    // Calculate all metrics
    $wordCount = str_word_count(strip_tags($content->output_text ?? ''));
    $charCount = strlen($content->output_text ?? '');
    $readingTime = ceil($wordCount / 200);
    $paragraphCount = substr_count($content->output_text ?? '', "\n\n") + 1;
    
    // Get proper title
    $inputData = $content->input_data ?? [];
    $contentTitle = $content->title 
        ?? ($inputData['topic'] ?? null)
        ?? Str::limit(strip_tags(strtok($content->output_text ?? '', "\n")), 50)
        ?? 'Untitled Content';
    
    // SEO Data
    $seoScore = $performance['seo']['score'] ?? $content->seo_overall_score ?? 0;
    $seoGrade = $performance['seo']['grade'] ?? $content->seo_grade ?? 'N/A';
    $seoBreakdown = $performance['seo']['breakdown'] ?? $content->seo_score_data['scores'] ?? [];
    
    // Performance
    $perfScore = $performance['performance_score'] ?? 0;
    
    // Engagement
    $views = $performance['engagement']['views'] ?? 0;
    $shares = $performance['engagement']['shares'] ?? 0;
    $downloads = $performance['engagement']['downloads'] ?? 0;
    $clicks = $performance['engagement']['clicks'] ?? 0;
    
    // Actions
    $actions = $performance['actions'] ?? [];
    $totalActions = is_array($actions) ? array_sum($actions) : 0;
    
    // Translations
    $translations = $content->translations ?? [];
    $translationCount = is_array($translations) ? count($translations) : 0;
    
    // Content type & tone
    $contentType = $content->content_type ?? ($inputData['content_type'] ?? 'article');
    $tone = $inputData['tone'] ?? $content->tone ?? 'professional';
    
    // Colors
    $seoColor = $seoScore >= 80 ? 'success' : ($seoScore >= 60 ? 'warning' : 'danger');
    $seoStroke = $seoScore >= 80 ? '#38ef7d' : ($seoScore >= 60 ? '#f5af19' : '#f45c43');
@endphp

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('content.index', ['locale' => app()->getLocale()]) }}">{{ __('translation.content_generator.analytics_page.my_content') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('content.show', ['locale' => app()->getLocale(), 'id' => $content->id]) }}">{{ Str::limit($contentTitle, 25) }}</a></li>
            <li class="breadcrumb-item active">{{ __('translation.content_generator.analytics_page.analytics_breadcrumb') }}</li>
        </ol>
    </nav>

    <!-- Hero Section -->
    <div class="analytics-hero">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge bg-white text-dark">
                        <i class="bi bi-file-earmark-text me-1"></i>{{ ucfirst($contentType) }}
                    </span>
                    <span class="badge bg-{{ ($content->status ?? 'draft') === 'published' ? 'success' : 'warning' }}">
                        {{ ucfirst($content->status ?? 'draft') }}
                    </span>
                    @if($seoScore > 0)
                    <span class="badge bg-white text-{{ $seoColor }}">
                        <i class="bi bi-graph-up me-1"></i>SEO: {{ $seoGrade }}
                    </span>
                    @endif
                </div>
                <h1 class="analytics-hero-title">{{ $contentTitle }}</h1>
                <p class="analytics-hero-meta mb-0">
                    <i class="bi bi-calendar3 me-1"></i> {{ $content->created_at->format('M d, Y') }}
                    <span class="mx-2">•</span>
                    <i class="bi bi-clock me-1"></i> {{ $readingTime }} min read
                    <span class="mx-2">•</span>
                    <i class="bi bi-translate me-1"></i> {{ strtoupper($content->language ?? 'EN') }}
                    @if($translationCount > 0)
                    <span class="mx-2">•</span>
                    <i class="bi bi-globe me-1"></i> +{{ $translationCount }} translations
                    @endif
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="health-score-box d-inline-block">
                    <div class="health-score-value">{{ $perfScore }}%</div>
                    <div class="health-score-label">{{ __('translation.content_generator.analytics_page.health_score') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="quick-stats">
        <div class="quick-stat">
            <div class="quick-stat-icon views"><i class="bi bi-eye"></i></div>
            <div class="quick-stat-value">{{ number_format($views) }}</div>
            <div class="quick-stat-label">{{ __('translation.content_generator.analytics_page.views') }}</div>
        </div>
        <div class="quick-stat">
            <div class="quick-stat-icon shares"><i class="bi bi-share"></i></div>
            <div class="quick-stat-value">{{ number_format($shares) }}</div>
            <div class="quick-stat-label">{{ __('translation.content_generator.analytics_page.shares') }}</div>
        </div>
        <div class="quick-stat">
            <div class="quick-stat-icon downloads"><i class="bi bi-download"></i></div>
            <div class="quick-stat-value">{{ number_format($downloads) }}</div>
            <div class="quick-stat-label">{{ __('translation.content_generator.analytics_page.downloads') }}</div>
        </div>
        <div class="quick-stat">
            <div class="quick-stat-icon actions"><i class="bi bi-lightning"></i></div>
            <div class="quick-stat-value">{{ $totalActions }}</div>
            <div class="quick-stat-label">{{ __('translation.content_generator.analytics_page.total_actions') }}</div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content Column -->
        <div class="col-lg-8">
            <!-- SEO Performance -->
            <div class="analytics-card">
                <div class="analytics-card-header">
                    <h3 class="analytics-card-title">
                        <i class="bi bi-graph-up-arrow text-success"></i> {{ __('translation.content_generator.analytics_page.seo_performance') }}
                    </h3>
                    @if($seoScore > 0)
                    <span class="badge bg-{{ $seoColor }} px-3 py-2">{{ __('translation.content_generator.analytics_page.grade') }}: {{ $seoGrade }}</span>
                    @endif
                </div>
                <div class="analytics-card-body">
                    @if($seoScore > 0)
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center mb-4 mb-md-0">
                            <div class="seo-progress-container">
                                <svg class="seo-progress-svg" width="160" height="160" viewBox="0 0 160 160">
                                    <circle class="seo-progress-bg" cx="80" cy="80" r="70"/>
                                    <circle class="seo-progress-bar" cx="80" cy="80" r="70" 
                                            stroke="{{ $seoStroke }}"
                                            stroke-dasharray="{{ 440 * $seoScore / 100 }} 440"/>
                                </svg>
                                <div class="seo-progress-text">
                                    <div class="seo-progress-value text-{{ $seoColor }}">{{ $seoScore }}</div>
                                    <div class="seo-progress-label">{{ __('translation.content_generator.analytics_page.out_of_100') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            @if(!empty($seoBreakdown))
                                @foreach($seoBreakdown as $category => $data)
                                    @php
                                        $score = is_array($data) ? ($data['score'] ?? 0) : $data;
                                        $barClass = $score >= 80 ? 'success' : ($score >= 60 ? 'warning' : 'danger');
                                    @endphp
                                    <div class="score-item">
                                        <div class="score-item-header">
                                            <span class="score-item-label">{{ str_replace('_', ' ', $category) }}</span>
                                            <span class="score-item-value text-{{ $barClass }}">{{ $score }}%</span>
                                        </div>
                                        <div class="score-bar">
                                            <div class="score-bar-fill {{ $barClass }}" style="width: {{ $score }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-light mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    {{ __('translation.content_generator.analytics_page.run_seo_analysis_detailed') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="bi bi-graph-up"></i></div>
                        <p class="empty-state-text mb-3">{{ __('translation.content_generator.analytics_page.no_seo_analysis') }}</p>
                        <a href="{{ route('content.show', ['locale' => app()->getLocale(), 'id' => $content->id]) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-play-circle me-1"></i>{{ __('translation.content_generator.analytics_page.run_seo_analysis') }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Content Statistics -->
            <div class="analytics-card">
                <div class="analytics-card-header">
                    <h3 class="analytics-card-title">
                        <i class="bi bi-file-earmark-text text-primary"></i> {{ __('translation.content_generator.analytics_page.content_statistics') }}
                    </h3>
                </div>
                <div class="analytics-card-body">
                    <div class="stats-grid">
                        <div class="stat-box">
                            <div class="stat-box-icon text-primary"><i class="bi bi-fonts"></i></div>
                            <div class="stat-box-value">{{ number_format($wordCount) }}</div>
                            <div class="stat-box-label">{{ __('translation.content_generator.analytics_page.words') }}</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-box-icon text-info"><i class="bi bi-type"></i></div>
                            <div class="stat-box-value">{{ number_format($charCount) }}</div>
                            <div class="stat-box-label">{{ __('translation.content_generator.analytics_page.characters') }}</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-box-icon text-success"><i class="bi bi-text-paragraph"></i></div>
                            <div class="stat-box-value">{{ $paragraphCount }}</div>
                            <div class="stat-box-label">{{ __('translation.content_generator.analytics_page.paragraphs') }}</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-box-icon text-warning"><i class="bi bi-hourglass-split"></i></div>
                            <div class="stat-box-value">{{ $readingTime }}</div>
                            <div class="stat-box-label">{{ __('translation.content_generator.analytics_page.min_read') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Actions -->
            @if($totalActions > 0)
            <div class="analytics-card">
                <div class="analytics-card-header">
                    <h3 class="analytics-card-title">
                        <i class="bi bi-activity text-danger"></i> {{ __('translation.content_generator.analytics_page.user_actions') }}
                    </h3>
                    <span class="badge bg-primary rounded-pill">{{ $totalActions }} {{ __('translation.content_generator.analytics_page.total') }}</span>
                </div>
                <div class="analytics-card-body">
                    <div class="actions-grid">
                        @php
                            $actionConfig = [
                                'view' => ['icon' => 'bi-eye', 'bg' => '#4A90D9', 'label' => __('translation.content_generator.analytics_page.views_label')],
                                'copy' => ['icon' => 'bi-clipboard', 'bg' => '#17a2b8', 'label' => __('translation.content_generator.analytics_page.copies_label')],
                                'share' => ['icon' => 'bi-share', 'bg' => '#28a745', 'label' => __('translation.content_generator.analytics_page.shares_label')],
                                'download' => ['icon' => 'bi-download', 'bg' => '#ffc107', 'label' => __('translation.content_generator.analytics_page.downloads_label')],
                                'export_pdf' => ['icon' => 'bi-file-pdf', 'bg' => '#dc3545', 'label' => __('translation.content_generator.analytics_page.pdf_exports_label')],
                                'export_word' => ['icon' => 'bi-file-word', 'bg' => '#2b579a', 'label' => __('translation.content_generator.analytics_page.word_exports_label')],
                                'translate' => ['icon' => 'bi-translate', 'bg' => '#6f42c1', 'label' => __('translation.content_generator.analytics_page.translations_label')],
                                'refine' => ['icon' => 'bi-magic', 'bg' => '#e83e8c', 'label' => __('translation.content_generator.analytics_page.refinements_label')],
                                'seo_analyze' => ['icon' => 'bi-graph-up', 'bg' => '#20c997', 'label' => __('translation.content_generator.analytics_page.seo_checks_label')],
                            ];
                        @endphp
                        @foreach($actions as $action => $count)
                            @if($count > 0)
                                @php
                                    $config = $actionConfig[$action] ?? ['icon' => 'bi-lightning', 'bg' => '#6c757d', 'label' => ucfirst(str_replace('_', ' ', $action))];
                                @endphp
                                <div class="action-stat">
                                    <div class="action-stat-icon" style="background: {{ $config['bg'] }}">
                                        <i class="bi {{ $config['icon'] }}"></i>
                                    </div>
                                    <div class="action-stat-info">
                                        <div class="action-stat-value">{{ number_format($count) }}</div>
                                        <div class="action-stat-label">{{ $config['label'] }}</div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Content Details -->
            <div class="sidebar-widget">
                <h4 class="sidebar-widget-title">
                    <i class="bi bi-info-circle text-info"></i> {{ __('translation.content_generator.analytics_page.content_details') }}
                </h4>
                <ul class="detail-list">
                    <li>
                        <span class="detail-label">{{ __('translation.content_generator.analytics_page.type') }}</span>
                        <span class="detail-value">{{ ucfirst($contentType) }}</span>
                    </li>
                    <li>
                        <span class="detail-label">{{ __('translation.content_generator.analytics_page.language') }}</span>
                        <span class="detail-value">{{ strtoupper($content->language ?? 'EN') }}</span>
                    </li>
                    <li>
                        <span class="detail-label">{{ __('translation.content_generator.analytics_page.tone') }}</span>
                        <span class="detail-value">{{ ucfirst($tone) }}</span>
                    </li>
                    <li>
                        <span class="detail-label">{{ __('translation.content_generator.analytics_page.version') }}</span>
                        <span class="badge bg-secondary">v{{ $content->version ?? 1 }}</span>
                    </li>
                    <li>
                        <span class="detail-label">{{ __('translation.content_generator.analytics_page.status') }}</span>
                        <span class="badge bg-{{ ($content->status ?? 'draft') === 'published' ? 'success' : 'warning' }}">
                            {{ ucfirst($content->status ?? 'Draft') }}
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Translations -->
            <div class="sidebar-widget">
                <h4 class="sidebar-widget-title">
                    <i class="bi bi-translate text-primary"></i> {{ __('translation.content_generator.analytics_page.translations') }}
                    <span class="badge bg-primary rounded-pill ms-auto">{{ $translationCount }}</span>
                </h4>
                @if($translationCount > 0)
                <div class="translation-badges">
                    @foreach($translations as $lang => $text)
                        <span class="translation-badge">
                            <i class="bi bi-check-circle-fill"></i>
                            {{ strtoupper($lang) }}
                        </span>
                    @endforeach
                </div>
                @else
                <div class="empty-state py-3">
                    <div class="empty-state-icon"><i class="bi bi-translate"></i></div>
                    <p class="empty-state-text small mb-0">{{ __('translation.content_generator.analytics_page.no_translations_yet') }}</p>
                </div>
                @endif
            </div>

            <!-- Timeline -->
            <div class="sidebar-widget">
                <h4 class="sidebar-widget-title">
                    <i class="bi bi-clock-history text-warning"></i> {{ __('translation.content_generator.analytics_page.timeline') }}
                </h4>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-dot created"></div>
                        <div class="timeline-content">
                            <div class="timeline-title">{{ __('translation.content_generator.analytics_page.created') }}</div>
                            <div class="timeline-date">{{ $content->created_at->format('M d, Y \a\t H:i') }}</div>
                        </div>
                    </div>
                    
                    @if($content->updated_at != $content->created_at)
                    <div class="timeline-item">
                        <div class="timeline-dot updated"></div>
                        <div class="timeline-content">
                            <div class="timeline-title">{{ __('translation.content_generator.analytics_page.last_updated') }}</div>
                            <div class="timeline-date">{{ $content->updated_at->format('M d, Y \a\t H:i') }}</div>
                        </div>
                    </div>
                    @endif

                    @if($content->last_seo_check)
                    <div class="timeline-item">
                        <div class="timeline-dot seo"></div>
                        <div class="timeline-content">
                            <div class="timeline-title">{{ __('translation.content_generator.analytics_page.seo_analyzed') }}</div>
                            <div class="timeline-date">{{ $content->last_seo_check->format('M d, Y \a\t H:i') }}</div>
                        </div>
                    </div>
                    @endif

                    @if($translationCount > 0)
                    <div class="timeline-item">
                        <div class="timeline-dot translated"></div>
                        <div class="timeline-content">
                            <div class="timeline-title">{{ __('translation.content_generator.analytics_page.translated') }}</div>
                            <div class="timeline-date">{{ $translationCount }} {{ __('translation.content_generator.analytics_page.languages') }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="page-actions">
        <a href="{{ route('content.show', ['locale' => app()->getLocale(), 'id' => $content->id]) }}" class="btn-action btn-action-outline">
            <i class="bi bi-arrow-left"></i> {{ __('translation.content_generator.analytics_page.back_to_content') }}
        </a>
        <div class="d-flex gap-2">
            <a href="{{ route('content.show', ['locale' => app()->getLocale(), 'id' => $content->id]) }}" class="btn-action btn-action-primary">
                <i class="bi bi-pencil"></i> {{ __('translation.content_generator.analytics_page.edit_content') }}
            </a>
        </div>
    </div>
</div>
@endsection

