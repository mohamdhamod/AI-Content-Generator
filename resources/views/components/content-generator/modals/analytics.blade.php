{{-- Analytics Modal Component --}}
@props(['content'])

<div class="modal fade" id="analyticsModal" tabindex="-1" aria-labelledby="analyticsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-analytics text-white">
                <h5 class="modal-title" id="analyticsModalLabel">
                    <i class="bi bi-bar-chart-line me-2"></i>{{ __('translation.content_generator.content_analytics') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Analytics Loading -->
                <div id="analyticsLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{ __('translation.content_generator.loading') }}</span>
                    </div>
                    <p class="text-muted mt-2">{{ __('translation.content_generator.loading_analytics') }}</p>
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
                                    <small>{{ __('translation.content_generator.views') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <i class="bi bi-clock fs-2 mb-2"></i>
                                    <h3 class="mb-0" id="analyticsReadTime">0m</h3>
                                    <small>{{ __('translation.content_generator.avg_read_time') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <i class="bi bi-hand-thumbs-up fs-2 mb-2"></i>
                                    <h3 class="mb-0" id="analyticsEngagement">0%</h3>
                                    <small>{{ __('translation.content_generator.engagement') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <i class="bi bi-share fs-2 mb-2"></i>
                                    <h3 class="mb-0" id="analyticsShares">0</h3>
                                    <small>{{ __('translation.content_generator.shares') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content Statistics -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-file-text me-2"></i>{{ __('translation.content_generator.content_statistics') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex justify-content-between py-2 border-bottom">
                                            <span class="text-muted">{{ __('translation.content_generator.word_count') }}</span>
                                            <span class="fw-bold" id="statsWordCount">{{ str_word_count(strip_tags($content->output_text ?? '')) }}</span>
                                        </li>
                                        <li class="d-flex justify-content-between py-2 border-bottom">
                                            <span class="text-muted">{{ __('translation.content_generator.character_count') }}</span>
                                            <span class="fw-bold" id="statsCharCount">{{ strlen($content->output_text ?? '') }}</span>
                                        </li>
                                        <li class="d-flex justify-content-between py-2">
                                            <span class="text-muted">{{ __('translation.content_generator.reading_time') }}</span>
                                            <span class="fw-bold" id="statsReadTime">{{ ceil(str_word_count(strip_tags($content->output_text ?? '')) / 200) }} {{ __('translation.content_generator.analytics_page.min_read') }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex justify-content-between py-2 border-bottom">
                                            <span class="text-muted">{{ __('translation.content_generator.created') }}</span>
                                            <span class="fw-bold">{{ $content->created_at->diffForHumans() }}</span>
                                        </li>
                                        <li class="d-flex justify-content-between py-2 border-bottom">
                                            <span class="text-muted">{{ __('translation.content_generator.last_updated') }}</span>
                                            <span class="fw-bold">{{ $content->updated_at->diffForHumans() }}</span>
                                        </li>
                                        <li class="d-flex justify-content-between py-2">
                                            <span class="text-muted">{{ __('translation.content_generator.version') }}</span>
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
                            <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>{{ __('translation.content_generator.seo_performance') }}</h6>
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
                                    <small class="text-muted">{{ __('translation.content_generator.seo_score') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Activity Timeline -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>{{ __('translation.content_generator.activity_timeline') }}</h6>
                        </div>
                        <div class="card-body">
                            <div id="activityTimeline">
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-success rounded-pill">{{ __('translation.content_generator.created') }}</span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0">{{ __('translation.content_generator.content_created') }}</p>
                                        <small class="text-muted">{{ $content->created_at->format('M d, Y H:i') }}</small>
                                    </div>
                                </div>
                                @if($content->updated_at != $content->created_at)
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-info rounded-pill">{{ __('translation.content_generator.last_updated') }}</span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0">{{ __('translation.content_generator.content_updated') }}</p>
                                        <small class="text-muted">{{ $content->updated_at->format('M d, Y H:i') }}</small>
                                    </div>
                                </div>
                                @endif
                                @if($content->last_seo_check)
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-primary rounded-pill">{{ __('translation.content_generator.seo_analysis') }}</span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0">{{ __('translation.content_generator.seo_analysis_performed') }}</p>
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
                    <i class="bi bi-x-circle me-2"></i>{{ __('translation.content_generator.close') }}
                </button>
                <a href="{{ route('analytics.content', ['locale' => app()->getLocale(), 'id' => $content->id]) }}" class="btn btn-primary">
                    <i class="bi bi-box-arrow-up-right me-2"></i>{{ __('translation.content_generator.full_analytics') }}
                </a>
            </div>
        </div>
    </div>
</div>

