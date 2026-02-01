{{-- Translate Modal Component --}}
@props(['content'])

@php
    $currentLang = $content->language ?? 'en';
    $languages = config('languages', []);
    $currentLangName = $languages[$currentLang]['display'] ?? $currentLang;
    
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

<div class="modal fade" id="translateModal" tabindex="-1" aria-labelledby="translateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-translate text-white">
                <h5 class="modal-title" id="translateModalLabel">
                    <i class="bi bi-translate me-2"></i>{{ __('translation.content_generator.translate_content') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">{{ __('translation.content_generator.translate_description') }}</p>
                
                <!-- Current Language -->
                <div class="alert alert-info d-flex align-items-center mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <div>
                        <strong>{{ __('translation.content_generator.current_language') }}:</strong> {{ $currentLangName }}
                    </div>
                </div>
                
                <!-- Target Language Selection -->
                <div class="mb-4">
                    <label class="form-label fw-bold">{{ __('translation.content_generator.select_target_language') }}</label>
                    <div class="row g-2" id="targetLanguages">
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
                            {{ __('translation.content_generator.preserve_medical_terminology') }}
                        </label>
                    </div>
                </div>
                
                <!-- Existing Translations -->
                <div id="existingTranslations" class="mb-3" style="display: none;">
                    <label class="form-label fw-bold">{{ __('translation.content_generator.existing_translations') }}</label>
                    <div id="translationsList" class="list-group"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>{{ __('translation.content_generator.cancel') }}
                </button>
                <button type="button" class="btn btn-success" onclick="translateContent()">
                    <i class="bi bi-translate me-2"></i>{{ __('translation.content_generator.translate') }}
                </button>
            </div>
        </div>
    </div>
</div>
