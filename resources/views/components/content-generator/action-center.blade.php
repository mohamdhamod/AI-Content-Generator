{{-- Content Generator Show Page - Action Center Component --}}
@props(['content', 'pptxStyles' => [], 'pptxThemes' => [], 'pptxDetails' => [], 'currentLocale' => 'en'])

<div class="action-center">
    <!-- Export & History -->
    <div class="action-center-title">
        <i class="bi bi-box-arrow-up me-1"></i> {{ __('translation.content_generator.export_history') }}
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
        <button type="button" class="action-btn action-btn-pptx bg-gradient-pptx text-white border-0" data-bs-toggle="modal" data-bs-target="#pptxThemeModal" title="{{ __('translation.content_generator.export_pptx_desc') }}">
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
            {{ __('translation.content_generator.version') }} {{ $content->version }}
        </button>
        @endif
    </div>
</div>
