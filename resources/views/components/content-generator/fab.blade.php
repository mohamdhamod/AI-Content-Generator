{{-- Content Generator Show Page - Floating Action Button Component --}}

<div class="fab-container" id="fabContainer">
    <div class="fab-menu">
        <a class="fab-item fab-item-ai" data-bs-toggle="modal" data-bs-target="#aiRefinementModal">
            <i class="bi bi-magic"></i>
            <span>{{ __('translation.content_generator.ai_refine') }}</span>
        </a>
        <a class="fab-item fab-item-seo" data-bs-toggle="modal" data-bs-target="#seoAnalysisModal">
            <i class="bi bi-graph-up"></i>
            <span>{{ __('translation.content_generator.seo_analysis') }}</span>
        </a>
        <a class="fab-item fab-item-translate" data-bs-toggle="modal" data-bs-target="#translateModal">
            <i class="bi bi-translate"></i>
            <span>{{ __('translation.content_generator.translate') }}</span>
        </a>
        <a class="fab-item fab-item-template" data-bs-toggle="modal" data-bs-target="#saveTemplateModal">
            <i class="bi bi-file-earmark-plus"></i>
            <span>{{ __('translation.content_generator.save_template') }}</span>
        </a>
        <a class="fab-item fab-item-analytics" data-bs-toggle="modal" data-bs-target="#analyticsModal">
            <i class="bi bi-bar-chart-line"></i>
            <span>{{ __('translation.content_generator.analytics') }}</span>
        </a>
    </div>
    <button class="fab-main" onclick="toggleFab()">
        <i class="bi bi-plus-lg"></i>
    </button>
</div>
