@extends('layout.main')
@include('layout.extra_meta')

@section('content')

    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row justify-content-start py-3">
            <div class="col-xxl-8 col-xl-10 text-start">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('subscriptions.dashboard.manage.index') }}">
                                <i class="bi bi-credit-card me-1"></i> {{ __('translation.subscription.title') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="bi bi-list-check me-1"></i> {{ __('translation.subscription_features.title') }}
                        </li>
                    </ol>
                </nav>
                
                <span class="badge bg-primary fw-normal shadow px-2 py-1 mb-2">
                    <i class="bi bi-box-seam me-2"></i> 
                    {{ $subscription->name }}
                </span>
                <h3 class="fw-bold">{{ __('translation.subscription_features.header_title') }}</h3>
                <p class="text-muted mb-0">
                    {{ __('translation.subscription_features.header_description') }}
                </p>
            </div>
        </div>

        <!-- Features Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            {{ __('translation.subscription_features.title') }} 
                            <span class="badge bg-secondary">{{ $features->total() }}</span>
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('subscriptions.dashboard.manage.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i> {{ __('translation.subscription_features.buttons.back_to_subscriptions') }}
                            </a>
                            <button type="button" onclick="openCreate()" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-circle me-2"></i> {{ __('translation.subscription_features.buttons.new_title') }}
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($features->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">{{ __('translation.subscription_features.icon') }}</th>
                                        <th>{{ __('translation.subscription_features.feature_text') }}</th>
                                        <th>{{ __('translation.subscription_features.highlighted') }}</th>
                                        <th>{{ __('translation.subscription_features.sort_order') }}</th>
                                        <th>{{ __('translation.subscription_features.status') }}</th>
                                        <th>{{ __('translation.subscription_features.actions') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($features as $model)
                                        <tr>
                                            <td>
                                                <span class="d-flex align-items-center justify-content-center rounded-circle bg-primary-subtle" 
                                                      style="width: 40px; height: 40px;">
                                                    <i class="bi {{ $model->icon ?? 'bi-check' }} text-primary"></i>
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ $model->feature_text }}</strong>
                                            </td>
                                            <td>
                                                @if($model->is_highlighted)
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-star-fill me-1"></i>{{ __('translation.subscription_features.yes') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-dark">{{ __('translation.subscription_features.no') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $model->sort_order }}</span>
                                            </td>
                                            <td>
                                                @if($model->active)
                                                    <span class="badge bg-success">{{ __('translation.subscription_features.active') }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ __('translation.subscription_features.inactive') }}</span>
                                                @endif
                                            </td>
                                            <td class="actions">
                                                <!-- Edit button -->
                                                <button type="button"
                                                        class="btn btn-sm btn-primary edit-btn"
                                                        data-model='@json($model)'
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ __('translation.subscription_features.edit') }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <!-- Activate / Deactivate button -->
                                                @php($isActive = isset($model->active) && $model->active)
                                                <button type="button"
                                                        class="btn btn-sm process-btn {{ $isActive ? 'btn-warning' : 'btn-success' }}"
                                                        data-model='@json($model)'
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ $isActive ? __('translation.subscription_features.deactivate') : __('translation.subscription_features.activate') }}"
                                                        aria-pressed="{{ $isActive ? 'true' : 'false' }}">
                                                    <i class="bi {{ $isActive ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                                </button>

                                                <!-- Delete button -->
                                                <button type="button"
                                                        class="btn btn-sm btn-danger delete-btn"
                                                        data-model='@json($model)'
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ __('translation.subscription_features.delete') }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $features->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="bi bi-list-check display-4 text-muted"></i>
                                </div>
                                <h5 class="text-muted">{{ __('translation.subscription_features.no_features_found') }}</h5>
                                <p class="text-muted mb-0">{{ __('translation.subscription_features.no_features_found_message') }}</p>
                                <button type="button" onclick="openCreate()" class="btn btn-primary mt-3">
                                    <i class="bi bi-plus-circle me-2"></i> {{ __('translation.subscription_features.buttons.new_title') }}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('dashboard.subscriptions.features.form', ['subscription' => $subscription])
    @include('modules.confirm')
    @include('modules.confirm_activate')
    @include('modules.i18n', ['page' => 'subscription_features'])
    <script>
        const i18n = window.i18n;
        const subscriptionId = {{ $subscription->id }};
        const baseUrl = `{{ route('subscriptions.manage.features.index', ['manage_subscription' => $subscription->id]) }}`;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Edit button handler
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const json = this.getAttribute('data-model');
                    const data = JSON.parse(json);
                    window.openEdit(data);
                });
            });

            // Delete button handler
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const json = this.getAttribute('data-model');
                    const data = JSON.parse(json);
                    confirmDelete(data, `${baseUrl}/${data.id}`, i18n, data.feature_text || data.translations?.[0]?.feature_text || '');
                });
            });

            // Activate/Deactivate button handler
            document.querySelectorAll('.process-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const json = this.getAttribute('data-model');
                    const data = JSON.parse(json);
                    confirmActivate(data, `${baseUrl}/${data.id}/updateActiveStatus`, i18n);
                });
            });

            // Handle form submission
            const addForm = document.querySelector('.add-form');
            if (addForm && !addForm.__handleSubmitBound) {
                addForm.__handleSubmitBound = true;
                addForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    window.handleFormSubmit(e, this);
                });
            }
        });

        // Open edit modal
        window.openEdit = function (data) {
            const modal = document.getElementById('add-modal');
            const form = modal.querySelector('.add-form');
            const modalTitle = modal.querySelector('.modal-title');
            const saveButton = modal.querySelector('#afm_btnSaveIt');

            if (!modal || !form) {
                console.error('Modal or form not found');
                return;
            }

            // Reset form
            form.reset();

            // Add method override for PUT request
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';

            // Set form action
            form.action = `${baseUrl}/${data.id}`;

            // Update modal title and button
            if (modalTitle) {
                modalTitle.innerHTML = '<i class="bi bi-pencil-square me-2"></i>' + i18n.form.title_edit;
            }
            if (saveButton) {
                saveButton.innerHTML = '<i class="bi bi-check-circle me-1"></i>' + i18n.form.btn_update;
            }

            // Clear previous errors
            clearFormErrors(modal);

            // Fill form with data - handle translations
            const featureText = data.feature_text || data.translations?.[0]?.feature_text || '';
            const formData = {
                ...data,
                feature_text: featureText
            };
            fillForm(modal, formData);

            // Handle highlighted checkbox
            const highlightedCheckbox = form.querySelector('input[name="is_highlighted"]');
            if (highlightedCheckbox) {
                highlightedCheckbox.checked = data.is_highlighted;
            }

            // Show modal
            showModal(modal);
        };

        // Open create modal
        window.openCreate = function () {
            const modal = document.getElementById('add-modal');
            const form = modal.querySelector('.add-form');
            const modalTitle = modal.querySelector('.modal-title');
            const saveButton = modal.querySelector('#afm_btnSaveIt');

            if (!modal || !form) {
                console.error('Modal or form not found');
                return;
            }

            // Reset form
            form.reset();

            // Remove method override for POST request
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) {
                methodInput.remove();
            }

            // Set form action for create
            form.action = `${baseUrl}`;

            // Update modal title and button
            if (modalTitle) {
                modalTitle.innerHTML = '<i class="bi bi-plus-circle me-2"></i>' + i18n.form.title_add;
            }
            if (saveButton) {
                saveButton.innerHTML = '<i class="bi bi-check-circle me-1"></i>' + i18n.form.btn_save;
            }

            // Clear previous errors
            clearFormErrors(modal);

            // Show modal
            showModal(modal);
        };
    </script>
@endpush
