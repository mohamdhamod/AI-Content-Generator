<!-- Add/Edit Subscription Feature Modal -->
<div id="add-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>{{ __('translation.subscription_features.form.title_add') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="add-form" method="POST" action="{{ route('subscriptions.manage.features.store', ['manage_subscription' => $subscription->id]) }}">
                @csrf
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row">
                        <!-- Feature Text -->
                        <div class="col-md-8 mb-3">
                            <label class="form-label" for="feature_text">{{ __('translation.subscription_features.form.feature_text') }} <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="feature_text" 
                                   name="feature_text" 
                                   placeholder="{{ __('translation.subscription_features.form.feature_text_placeholder') }}"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Icon -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="icon">{{ __('translation.subscription_features.form.icon') }}</label>
                            <div class="input-group">
                                <span class="input-group-text" id="icon-preview">
                                    <i class="bi bi-check"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       id="icon" 
                                       name="icon" 
                                       value="bi-check"
                                       placeholder="bi-check"
                                       onchange="updateIconPreview(this.value)">
                            </div>
                            <small class="text-muted">{{ __('translation.subscription_features.form.icon_hint') }}</small>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Sort Order -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="sort_order">{{ __('translation.subscription_features.form.sort_order') }}</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="sort_order" 
                                   name="sort_order" 
                                   value="0"
                                   min="0">
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Is Highlighted -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label d-block">{{ __('translation.subscription_features.form.is_highlighted') }}</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_highlighted" 
                                       name="is_highlighted"
                                       value="1">
                                <label class="form-check-label" for="is_highlighted">
                                    <i class="bi bi-star-fill text-warning me-1"></i>{{ __('translation.subscription_features.form.highlighted_label') }}
                                </label>
                            </div>
                            <small class="text-muted">{{ __('translation.subscription_features.form.highlighted_hint') }}</small>
                        </div>
                    </div>

                    <!-- Popular Icons Reference -->
                    <div class="mt-3">
                        <label class="form-label">{{ __('translation.subscription_features.form.popular_icons') }}</label>
                        <div class="d-flex flex-wrap gap-2">
                            @php
                                $popularIcons = [
                                    'bi-check', 'bi-check-circle', 'bi-check-circle-fill', 'bi-check2-all',
                                    'bi-star', 'bi-star-fill', 'bi-lightning', 'bi-lightning-fill',
                                    'bi-shield-check', 'bi-award', 'bi-trophy', 'bi-gem',
                                    'bi-infinity', 'bi-rocket', 'bi-speedometer2', 'bi-graph-up'
                                ];
                            @endphp
                            @foreach($popularIcons as $icon)
                                <button type="button" 
                                        class="btn btn-outline-secondary btn-sm icon-picker"
                                        onclick="selectIcon('{{ $icon }}')"
                                        title="{{ $icon }}">
                                    <i class="bi {{ $icon }}"></i>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>{{ __('translation.subscription_features.form.btn_cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary" id="afm_btnSaveIt">
                        <i class="bi bi-check-circle me-1"></i>{{ __('translation.subscription_features.form.btn_save') }}
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
            preview.innerHTML = `<i class="bi ${iconClass}"></i>`;
        }
    }

    function selectIcon(iconClass) {
        const iconInput = document.getElementById('icon');
        if (iconInput) {
            iconInput.value = iconClass;
            updateIconPreview(iconClass);
        }
    }
</script>
