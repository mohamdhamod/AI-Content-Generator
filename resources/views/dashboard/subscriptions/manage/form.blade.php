<div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="addSubscriptionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubscriptionsModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>{{ __('translation.subscription.form.modal.title_add') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <form class="add-form" id="subscriptionsForm" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" id="editId" name="id">

                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-12 mb-3">
                            <label for="name" class="form-label">{{ __('translation.subscription.form.fields.name') }} <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   name="name"
                                   required
                                   placeholder="{{ __('translation.subscription.form.placeholders.name') }}">
                            <div class="invalid-feedback">
                                {{ __('translation.subscription.form.validation.name') }}
                            </div>
                        </div>

                        <!-- Price Field -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ __('translation.subscription.form.fields.price') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="price" id="price" class="form-control"
                                   placeholder="0.00" required>
                            <div class="invalid-feedback">
                                {{ __('translation.subscription.form.validation.price') }}
                            </div>
                        </div>

                        <!-- Currency Field -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ __('translation.subscription.form.fields.currency') }}</label>
                            <select name="currency" id="currency" class="form-select">
                                <option value="EUR">EUR (€)</option>
                                <option value="USD">USD ($)</option>
                                <option value="GBP">GBP (£)</option>
                            </select>
                        </div>

                        <!-- Duration Months Field -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ __('translation.subscription.form.fields.duration_months') }} <span class="text-danger">*</span></label>
                            <input type="number" step="1" min="1" name="duration_months" id="duration_months" class="form-control"
                                   placeholder="1" value="1" required>
                            <div class="invalid-feedback">
                                {{ __('translation.subscription.form.validation.duration_months') }}
                            </div>
                        </div>

                        <!-- Max Content Generations Field -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('translation.subscription.form.fields.max_content_generations') }} <span class="text-danger">*</span></label>
                            <input type="number" step="1" min="1" name="max_content_generations" id="max_content_generations" class="form-control"
                                   placeholder="10" value="10" required>
                            <small class="text-muted">{{ __('translation.subscription.form.hints.max_content_generations') }}</small>
                            <div class="invalid-feedback">
                                {{ __('translation.subscription.form.validation.max_content_generations') }}
                            </div>
                        </div>

                        <!-- Sort Order Field -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('translation.subscription.form.fields.sort_order') }}</label>
                            <input type="number" step="1" min="0" name="sort_order" id="sort_order" class="form-control"
                                   placeholder="0" value="0">
                        </div>

                        <!-- Digistore Product ID -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('translation.subscription.form.fields.digistore_product_id') }}</label>
                            <input type="text" name="digistore_product_id" id="digistore_product_id" class="form-control"
                                   placeholder="{{ __('translation.subscription.form.placeholders.digistore_product_id') }}">
                            <small class="text-muted">{{ __('translation.subscription.form.hints.digistore_product_id') }}</small>
                        </div>

                        <!-- Digistore Checkout URL -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('translation.subscription.form.fields.digistore_checkout_url') }}</label>
                            <input type="url" name="digistore_checkout_url" id="digistore_checkout_url" class="form-control"
                                   placeholder="https://www.digistore24.com/product/...">
                        </div>

                        <!-- Description -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">{{ __('translation.subscription.form.fields.description') }}</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>{{ __('translation.subscription.form.buttons.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary" id="afm_btnSaveIt" form="subscriptionsForm">
                    <i class="bi bi-check-circle me-1"></i>{{ __('translation.subscription.form.buttons.save') }}
                </button>
            </div>
        </div>
    </div>
</div>
