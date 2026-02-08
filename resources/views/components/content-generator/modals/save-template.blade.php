{{-- Save Template Modal Component --}}

<div class="modal fade" id="saveTemplateModal" tabindex="-1" aria-labelledby="saveTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-template text-white">
                <h5 class="modal-title" id="saveTemplateModalLabel">
                    <i class="bi bi-file-earmark-plus me-2"></i>{{ __('translation.content_generator.save_as_template') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">{{ __('translation.content_generator.save_template_description') }}</p>
                
                <!-- Template Name -->
                <div class="mb-3">
                    <label for="templateName" class="form-label fw-bold">{{ __('translation.content_generator.template_name') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="templateName" 
                           placeholder="{{ __('translation.content_generator.template_name_placeholder') }}" required>
                </div>
                
                <!-- Template Description -->
                <div class="mb-3">
                    <label for="templateDescription" class="form-label fw-bold">{{ __('translation.content_generator.description') }}</label>
                    <textarea class="form-control" id="templateDescription" rows="3" 
                              placeholder="{{ __('translation.content_generator.describe_template_placeholder') }}"></textarea>
                </div>
                
                <!-- Template Category -->
                <div class="mb-3">
                    <label for="templateCategory" class="form-label fw-bold">{{ __('translation.content_generator.category') }}</label>
                    <select class="form-select" id="templateCategory">
                        <option value="general">{{ __('translation.content_generator.general') }}</option>
                        <option value="patient_education">{{ __('translation.content_generator.patient_education') }}</option>
                        <option value="clinical_documentation">{{ __('translation.content_generator.clinical_documentation') }}</option>
                        <option value="research">{{ __('translation.content_generator.research_studies') }}</option>
                        <option value="marketing">{{ __('translation.content_generator.healthcare_marketing') }}</option>
                        <option value="social_media">{{ __('translation.content_generator.template_category_social_media') }}</option>
                    </select>
                </div>
                
                <!-- Template Options -->
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('translation.content_generator.include_in_template') }}</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="includeStructure" checked>
                        <label class="form-check-label" for="includeStructure">
                            {{ __('translation.content_generator.content_structure') }}
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="includeTone" checked>
                        <label class="form-check-label" for="includeTone">
                            {{ __('translation.content_generator.tone_style_settings') }}
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="includePromptHints">
                        <label class="form-check-label" for="includePromptHints">
                            {{ __('translation.content_generator.topic_prompt_hints') }}
                        </label>
                    </div>
                </div>
                
                <!-- Share with Team -->
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="shareWithTeam">
                        <label class="form-check-label" for="shareWithTeam">
                            <i class="bi bi-people me-1"></i>{{ __('translation.content_generator.share_with_team') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>{{ __('translation.content_generator.cancel') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="saveAsTemplate()">
                    <i class="bi bi-save me-2"></i>{{ __('translation.content_generator.save_template') }}
                </button>
            </div>
        </div>
    </div>
</div>
