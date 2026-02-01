{{-- SEO Analysis Modal Component --}}
@props(['content'])

<div class="modal fade" id="seoAnalysisModal" tabindex="-1" aria-labelledby="seoAnalysisModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-seo text-white">
                <h5 class="modal-title" id="seoAnalysisModalLabel">
                    <i class="bi bi-graph-up me-2"></i>{{ __('translation.content_generator.seo_analysis') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Analysis Form -->
                <div id="seoAnalysisForm">
                    <div class="mb-3">
                        <label for="seoFocusKeyword" class="form-label">{{ __('translation.content_generator.focus_keyword') }}</label>
                        <input type="text" class="form-control" id="seoFocusKeyword" 
                               value="{{ $content->seo_focus_keyword ?? '' }}"
                               placeholder="{{ __('translation.content_generator.enter_target_keyword') }}">
                        <small class="text-muted">{{ __('translation.content_generator.main_keyword_hint') }}</small>
                    </div>
                    <div class="mb-3">
                        <label for="seoMetaDescription" class="form-label">{{ __('translation.content_generator.meta_description') }}</label>
                        <textarea class="form-control" id="seoMetaDescription" rows="3"
                                  placeholder="{{ __('translation.content_generator.enter_meta_description') }}">{{ $content->seo_meta_description ?? '' }}</textarea>
                        <small class="text-muted">{{ __('translation.content_generator.optimal_length_hint') }}</small>
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
                        <h6 class="fw-bold mb-3">{{ __('translation.content_generator.detailed_scores') }}</h6>
                        <div id="seoDetailedScores"></div>
                    </div>
                    
                    <!-- Recommendations -->
                    <div class="mb-3">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-lightbulb text-warning me-2"></i>{{ __('translation.content_generator.recommendations') }}
                        </h6>
                        <div id="seoRecommendations"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>{{ __('translation.content_generator.close') }}
                </button>
                <!-- Form Actions -->
                <button type="button" class="btn btn-primary" id="btnAnalyzeSeo" onclick="analyzeSeo()">
                    <i class="bi bi-play-circle me-2"></i>{{ __('translation.content_generator.analyze_seo') }}
                </button>
                <!-- Results Actions -->
                <button type="button" class="btn btn-outline-primary" id="btnAnalyzeAgain" style="display: none;" onclick="resetSeoAnalysis()">
                    <i class="bi bi-arrow-repeat me-2"></i>{{ __('translation.content_generator.analyze_again') }}
                </button>
            </div>
        </div>
    </div>
</div>
