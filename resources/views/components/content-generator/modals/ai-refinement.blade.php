{{-- AI Refinement Modal Component --}}
@props(['content'])

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
