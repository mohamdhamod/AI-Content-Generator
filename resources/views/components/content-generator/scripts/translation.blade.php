{{-- Translation Script --}}
@props(['content'])

<script>
/**
 * Translate content to selected languages
 */
async function translateContent() {
    const selectedLanguages = Array.from(document.querySelectorAll('input[name="target_languages[]"]:checked')).map(el => el.value);
    const preserveMedicalTerms = document.getElementById('preserveMedicalTerms')?.checked || false;
    
    if (selectedLanguages.length === 0) {
        SwalHelper.warning('{{ __("translation.content_generator.no_language_selected") }}', '{{ __("translation.content_generator.select_at_least_one_language") }}');
        return;
    }
    
    try {
        const data = await ApiClient.post("{{ route('content.translate', ['id' => $content->id]) }}", {
            target_languages: selectedLanguages,
            preserve_medical_terms: preserveMedicalTerms
        }, {
            loadingText: `{{ __("translation.content_generator.translating_to") }} ${selectedLanguages.length} {{ __("translation.content_generator.languages") }}...`
        });
        
        if (data.success) {
            const result = await Swal.fire({
                icon: 'success',
                title: '{{ __("translation.content_generator.translation_complete") }}!',
                html: `{{ __("translation.content_generator.successfully_translated_to") }} ${data.data?.translations?.length || selectedLanguages.length} {{ __("translation.content_generator.languages") }}.`,
                confirmButtonText: '{{ __("translation.content_generator.view_translations") }}',
                showCancelButton: true,
                cancelButtonText: '{{ __("translation.content_generator.close") }}'
            });
            
            if (result.isConfirmed) {
                loadExistingTranslations();
                loadSidebarTranslations();
                document.getElementById('existingTranslations')?.scrollIntoView({ behavior: 'smooth' });
            } else {
                ModalManager.close('translateModal');
                loadSidebarTranslations();
            }
        } else {
            SwalHelper.error('{{ __("translation.content_generator.translation_failed") }}', data.message || '{{ __("translation.content_generator.failed_to_translate") }}');
        }
    } catch (error) {
        console.error('Error:', error);
        SwalHelper.error('{{ __("translation.content_generator.error") }}', '{{ __("translation.content_generator.network_error") }}');
    }
}

/**
 * Load existing translations
 */
async function loadExistingTranslations() {
    try {
        const data = await ApiClient.get("{{ route('content.translations.list', ['id' => $content->id]) }}", { showLoading: false });
        
        if (data.success && data.data?.length > 0) {
            const container = document.getElementById('translationsList');
            const wrapper = document.getElementById('existingTranslations');
            wrapper.style.display = 'block';
            
            container.innerHTML = data.data.map(t => `
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${Utils.escapeHtml(t.language_name)}</strong>
                        ${t.quality_score ? `<span class="badge bg-info ms-2">${t.quality_score}% {{ __("translation.content_generator.quality") }}</span>` : ''}
                        <br><small class="text-muted">${Utils.escapeHtml(t.preview || '{{ __("translation.content_generator.translation_available") }}')}</small>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="viewTranslation('${Utils.escapeHtml(t.language)}', '${Utils.escapeHtml(t.language_name)}')">
                            <i class="bi bi-eye"></i> {{ __("translation.content_generator.view") }}
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="copyTranslation('${Utils.escapeHtml(t.language)}')">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>
            `).join('');
        } else {
            document.getElementById('existingTranslations').style.display = 'none';
        }
    } catch (err) {
        console.error('Error loading translations:', err);
    }
}

/**
 * View a specific translation
 */
async function viewTranslation(langCode, langName) {
    Swal.fire({
        title: `<i class="bi bi-translate me-2"></i>{{ __("translation.content_generator.translation") }}: ${Utils.escapeHtml(langName)}`,
        html: '<div class="text-center"><div class="spinner-border text-primary"></div><p class="mt-2">{{ __("translation.content_generator.loading") }}...</p></div>',
        showConfirmButton: false,
        allowOutsideClick: false,
        width: '80%',
        customClass: { popup: 'text-start' }
    });
    
    try {
        const data = await ApiClient.get(`{{ url('/' . app()->getLocale() . '/content/' . $content->id . '/translation') }}/${langCode}`, { showLoading: false });
        
        if (data.success && data.data?.translation) {
            Swal.fire({
                title: `<i class="bi bi-translate me-2"></i>{{ __("translation.content_generator.translation") }}: ${Utils.escapeHtml(langName)}`,
                html: `
                    <div class="text-start" style="max-height: 60vh; overflow-y: auto;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            ${data.data.quality_score ? `<span class="badge bg-success">{{ __("translation.content_generator.quality") }}: ${data.data.quality_score}%</span>` : ''}
                            <button class="btn btn-sm btn-outline-primary" onclick="navigator.clipboard.writeText(document.getElementById('translationText').innerText).then(() => SwalHelper.toast('{{ __("translation.content_generator.copied") }}!'))">
                                <i class="bi bi-clipboard me-1"></i>{{ __("translation.content_generator.copy") }}
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
                confirmButtonText: '<i class="bi bi-x-circle me-1"></i>{{ __("translation.content_generator.close") }}',
                customClass: { popup: 'text-start' }
            });
        } else {
            SwalHelper.error('{{ __("translation.content_generator.error") }}', data.message || '{{ __("translation.content_generator.translation_not_found") }}');
        }
    } catch (error) {
        console.error('Error:', error);
        SwalHelper.error('{{ __("translation.content_generator.error") }}', '{{ __("translation.content_generator.failed_to_load_translation") }}');
    }
}

/**
 * Copy a translation to clipboard
 */
async function copyTranslation(langCode) {
    try {
        const data = await ApiClient.get(`{{ url('/' . app()->getLocale() . '/content/' . $content->id . '/translation') }}/${langCode}`, { showLoading: false });
        
        if (data.success && data.data?.translation) {
            await navigator.clipboard.writeText(data.data.translation);
            SwalHelper.toast('{{ __("translation.content_generator.translation_copied_to_clipboard") }}');
        }
    } catch (err) {
        console.error('Error copying translation:', err);
    }
}

/**
 * Load translations for sidebar panel
 */
async function loadSidebarTranslations() {
    const listContainer = document.getElementById('sidebarTranslationsList');
    const noTranslationsMsg = document.getElementById('noTranslationsMessage');
    
    try {
        const data = await ApiClient.get("{{ route('content.translations.list', ['id' => $content->id]) }}", { showLoading: false });
        
        if (data.success && data.data?.length > 0) {
            if (noTranslationsMsg) noTranslationsMsg.style.display = 'none';
            
            const flagMap = {
                'en': '🇬🇧', 'ar': '🇸🇦', 'de': '🇩🇪', 'fr': '🇫🇷', 'es': '🇪🇸',
                'tr': '🇹🇷', 'it': '🇮🇹', 'pt': '🇵🇹', 'ru': '🇷🇺', 'zh': '🇨🇳',
                'ja': '🇯🇵', 'ko': '🇰🇷', 'nl': '🇳🇱', 'pl': '🇵🇱', 'hi': '🇮🇳'
            };
            
            let html = data.data.map(t => `
                <div class="translation-item">
                    <div class="translation-lang">
                        <span class="translation-flag">${flagMap[t.language] || '🌐'}</span>
                        <span class="translation-name">${Utils.escapeHtml(t.language_name)}</span>
                        ${t.quality_score ? `<span class="translation-quality">${t.quality_score}%</span>` : ''}
                    </div>
                    <div class="translation-actions">
                        <button class="translation-btn translation-btn-view" onclick="viewTranslation('${Utils.escapeHtml(t.language)}', '${Utils.escapeHtml(t.language_name)}')" title="{{ __('translation.content_generator.view') }}">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="translation-btn translation-btn-copy" onclick="copyTranslation('${Utils.escapeHtml(t.language)}')" title="{{ __('translation.content_generator.copy') }}">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>
            `).join('');
            
            if (listContainer) {
                listContainer.innerHTML = html + `<div id="noTranslationsMessage" class="no-translations" style="display:none;"><i class="bi bi-translate d-block"></i><p class="mb-2">{{ __("translation.content_generator.no_translations") }}</p></div>`;
            }
        } else {
            if (listContainer) {
                listContainer.innerHTML = `
                    <div class="no-translations" id="noTranslationsMessage">
                        <i class="bi bi-translate d-block"></i>
                        <p class="mb-2">{{ __("translation.content_generator.no_translations") }}</p>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#translateModal">
                            <i class="bi bi-plus me-1"></i> {{ __("translation.content_generator.create_first_translation") }}
                        </button>
                    </div>
                `;
            }
        }
    } catch (error) {
        console.error('Error loading sidebar translations:', error);
    }
}

// Load existing translations when translate modal opens
document.getElementById('translateModal')?.addEventListener('shown.bs.modal', function() {
    loadExistingTranslations();
    loadSidebarTranslations();
});
</script>
