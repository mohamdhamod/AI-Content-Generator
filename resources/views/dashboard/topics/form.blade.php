<!-- Add/Edit Topic Modal -->
<div id="add-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>{{ __('translation.topics.form.title_add') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="add-form" method="POST" action="{{ route('specialties.topics.store', ['specialty' => $specialty->id]) }}">
                @csrf
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-8 mb-3">
                            <label class="form-label" for="name">{{ __('translation.topics.form.name') }} <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   placeholder="{{ __('translation.topics.form.name_placeholder') }}"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Icon -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="icon">{{ __('translation.topics.form.icon') }}</label>
                            <div class="input-group">
                                <span class="input-group-text" id="icon-preview">
                                    <i class="fas fa-circle"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       id="icon" 
                                       name="icon" 
                                       placeholder="fa-circle"
                                       onchange="updateIconPreview(this.value)">
                            </div>
                            <small class="text-muted">{{ __('translation.topics.form.icon_hint') }}</small>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Description -->
                        <div class="col-12 mb-3">
                            <label class="form-label" for="description">{{ __('translation.topics.form.description') }}</label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="{{ __('translation.topics.form.description_placeholder') }}"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- AI Prompt Hint -->
                        <div class="col-12 mb-3">
                            <label class="form-label" for="prompt_hint">
                                <i class="fas fa-robot text-primary me-1"></i>
                                {{ __('translation.topics.form.prompt_hint') }}
                            </label>
                            <textarea class="form-control" 
                                      id="prompt_hint" 
                                      name="prompt_hint" 
                                      rows="4"
                                      maxlength="1000"
                                      placeholder="{{ __('translation.topics.form.prompt_hint_placeholder') }}"></textarea>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                {{ __('translation.topics.form.prompt_hint_help') }}
                            </small>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Sort Order -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="sort_order">{{ __('translation.topics.form.sort_order') }}</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="sort_order" 
                                   name="sort_order" 
                                   value="0"
                                   min="0">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Popular Icons Reference -->
                    <div class="mt-3">
                        <label class="form-label">{{ __('translation.topics.form.popular_icons') }}</label>
                        <div class="d-flex flex-wrap gap-2">
                            @php
                                $popularIcons = [
                                    'fa-tooth', 'fa-heart', 'fa-brain', 'fa-lungs', 'fa-bone', 
                                    'fa-eye', 'fa-hand', 'fa-syringe', 'fa-pills', 'fa-stethoscope',
                                    'fa-dna', 'fa-virus', 'fa-shield', 'fa-apple-whole', 'fa-dumbbell'
                                ];
                            @endphp
                            @foreach($popularIcons as $icon)
                                <button type="button" 
                                        class="btn btn-outline-secondary btn-sm icon-picker"
                                        onclick="selectIcon('{{ $icon }}')"
                                        title="{{ $icon }}">
                                    <i class="fas {{ $icon }}"></i>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>{{ __('translation.topics.form.btn_cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary" id="afm_btnSaveIt">
                        <i class="bi bi-check-circle me-1"></i>{{ __('translation.topics.form.btn_save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateIconPreview(iconClass) {
        const preview = document.getElementById('icon-preview');
        if (preview) {
            preview.innerHTML = `<i class="fas ${iconClass || 'fa-circle'}"></i>`;
        }
    }

    function selectIcon(iconClass) {
        const iconInput = document.getElementById('icon');
        if (iconInput) {
            iconInput.value = iconClass;
            updateIconPreview(iconClass);
        }
    }

    // Override fillForm for topics
    document.addEventListener('DOMContentLoaded', function() {
        const originalFillForm = window.fillForm;
        window.fillForm = function(modal, data) {
            // Fill basic fields
            const nameInput = modal.querySelector('#name');
            const iconInput = modal.querySelector('#icon');
            const descriptionInput = modal.querySelector('#description');
            const sortOrderInput = modal.querySelector('#sort_order');

            if (nameInput) nameInput.value = data.name || '';
            if (iconInput) {
                iconInput.value = data.icon || '';
                updateIconPreview(data.icon);
            }
            if (descriptionInput) descriptionInput.value = data.description || '';
            
            // Prompt hint for AI context
            const promptHintInput = modal.querySelector('#prompt_hint');
            if (promptHintInput) promptHintInput.value = data.prompt_hint || '';
            
            if (sortOrderInput) sortOrderInput.value = data.sort_order || 0;
        };
    });
</script>
