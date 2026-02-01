@extends('layout.main')
@include('layout.extra_meta')

@section('content')

    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row justify-content-start py-3">
            <div class="col-xxl-8 col-xl-10 text-start">
                <span class="badge bg-light text-dark fw-normal shadow px-2 py-1 mb-2">
                    <i class="bi bi-link-45deg me-2"></i> {{ __('translation.subscription.title') }}
                </span>
                <h3 class="fw-bold">{{ __('translation.subscription.header_title') }}</h3>
                <p class="text-muted mb-0">
                    {{ __('translation.subscription.header_description') }}
                </p>
            </div>
        </div>

        <!-- subscriptions Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ __('translation.subscription.title') }}</h5>
                        <div class="d-flex gap-2">
                            <button type="button" onclick="openCreate()" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-circle me-2"></i> {{ __('translation.subscription.buttons.new_title') }}
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
    @if($subscriptions->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{ __('translation.subscription.name') }}</th>
                    <th>{{ __('translation.subscription.price') }}</th>
                    <th>{{ __('translation.subscription.duration') }}</th>
                    <th>{{ __('translation.subscription.generations') }}</th>
                    <th>{{ __('translation.subscription.status') }}</th>
                    <th>{{ __('translation.subscription.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($subscriptions as $model)
                    <tr>
                        <td>
                            <div>
                                <strong>{{ $model->name }}</strong>
                                @if($model->description)
                                    <br><small class="text-muted">{{ Str::limit($model->description, 50) }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <strong>{{ number_format($model->price, 2) }} {{ $model->currency ?? 'EUR' }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $model->duration_months ?? 1 }} {{ __('translation.subscription.months') }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $model->max_content_generations ?? '-' }}</span>
                        </td>
                        <td>
                            @if($model->active)
                                <span class="badge bg-success">{{ __('translation.subscription.active') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('translation.subscription.inactive') }}</span>
                            @endif
                        </td>
                        <td class="actions">
                            <!-- Features button -->
                            <a href="{{ route('subscriptions.manage.features.index', ['manage_subscription' => $model->id]) }}"
                               class="btn btn-sm btn-outline-primary"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="{{ __('translation.subscription_features.title') }}">
                                <i class="bi bi-list-check"></i>
                            </a>

                            <!-- Edit button -->
                            <button type="button"
                                    class="btn btn-sm btn-primary edit-btn"
                                    data-model='@json($model)'
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="{{ __('translation.subscription.edit') }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <!-- View button -->
                            <button type="button"
                                    class="btn btn-sm btn-info view-btn"
                                    data-model='@json($model)'
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="{{ __('translation.subscription.view') }}">
                                <i class="bi bi-eye"></i>
                            </button>

                            <!-- Activate / Deactivate button -->
                            @php($isActive = isset($model->active) && $model->active)
                            <button type="button"
                                    class="btn btn-sm process-btn {{ $isActive ? 'btn-warning' : 'btn-success' }}"
                                    data-model='@json($model)'
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="{{ $isActive ? __('translation.subscription.deactivate') : __('translation.configurations.activate') }}"
                                    aria-pressed="{{ $isActive ? 'true' : 'false' }}">
                                <i class="bi {{ $isActive ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                            </button>

                            <!-- Delete button -->
                            <button type="button"
                                    class="btn btn-sm btn-danger delete-btn"
                                    data-model='@json($model)'
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="{{ __('translation.subscription.delete') }}">
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
            {{ $subscriptions->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-inbox display-4 text-muted"></i>
            </div>
            <h5 class="text-muted">{{ __('translation.subscription.no_subscriptions_found') }}</h5>
            <p class="text-muted mb-0">{{ __('translation.subscription.no_subscriptions_found_message') }}</p>
        </div>
    @endif
</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('dashboard.subscriptions.manage.form')
    @include('modules.confirm')
    @include('modules.confirm_activate')
    @include('modules.view')
    @include('modules.i18n', ['page' => 'subscriptions'])
    <script>
        const i18n = window.i18n;
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const json = this.getAttribute('data-model');
                    const data = JSON.parse(json);
                    window.openEdit(data);
                });
            });
                    document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const json = this.getAttribute('data-model');
                    const data = JSON.parse(json);
                    confirmDelete(data , `{{ route('subscriptions.dashboard.manage.index') }}/${data.id}` , i18n , data.name);
                });
            });

            document.querySelectorAll('.process-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const json = this.getAttribute('data-model');
                    const data = JSON.parse(json);

                    confirmActivate(data , `{{ route('subscriptions.dashboard.manage.index') }}/${data.id}/updateActiveStatus` , i18n);

                });
            });

            document.querySelectorAll('.view-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const json = this.getAttribute('data-model');
                    const data = JSON.parse(json);
                    window.openView(data);
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

            // Set form action - use update route
            form.action = `{{ route('subscriptions.dashboard.manage.index') }}/${data.id}`;

            // Update modal title and button
            if (modalTitle) {
                modalTitle.innerHTML = '<i class="bi bi-plus-circle me-2"></i>' + i18n.form.title_edit;
            }
            if (saveButton) {
                saveButton.innerHTML = '<i class="bi bi-check-circle me-1"></i>' + i18n.form.btn_update;
            }

            // Show edit-only fields
            modal.querySelectorAll('.edit-only').forEach(field => {
                field.style.display = 'block';
            });
            // Clear previous errors
            clearFormErrors(modal);

            // Fill form with data
            fillForm(modal, data);
            // Show modal
            showModal(modal);
        };

        // Open view modal
        window.openView = function (data) {
            const modal = document.getElementById('view-modal');
            const dataContainer = modal.querySelector('#data');

            if (!modal || !dataContainer) {
                console.error('View modal or data container not found');
                return;
            }

            // Define fields to display
            const fields = [
                {label: i18n.labels.id, value: data.id || ''},
                {label: i18n.labels.name, value: data.name || ''},
                {label: i18n.labels.price, value: (data.price || '0') + ' ' + (data.currency || 'EUR')},
                {label: i18n.labels.duration_months, value: (data.duration_months || 1) + ' ' + i18n.labels.months},
                {label: i18n.labels.max_content_generations, value: data.max_content_generations || ''},
                {label: i18n.labels.digistore_product_id, value: data.digistore_product_id || '-'},
                {label: i18n.labels.description, value: data.description || ''},
                {label: i18n.labels.status, value: data.active ? i18n.labels.active : i18n.labels.inactive},
                {label: i18n.labels.created_at, value: data.created_at || ''}
            ];

            // Generate HTML for fields
            const fieldHtml = fields.map(field => `
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="fw-bold">${field.label}:</label>
                    </div>
                    <div class="col-md-9">
                        <span class="text-muted">${field.value}</span>
                    </div>
                </div>
            `).join('');

            dataContainer.innerHTML = fieldHtml;

            // Clear previous errors
            clearFormErrors(modal);

            // Show modal
            showModal(modal);
        }

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
            form.action = `{{ route('subscriptions.dashboard.manage.store') }}`;

            // Update modal title and button
            if (modalTitle) {
                modalTitle.innerHTML = '<i class="bi bi-plus-circle me-2"></i>' + i18n.form.title_add;
            }
            if (saveButton) {
                saveButton.innerHTML = '<i class="bi bi-check-circle me-1"></i>' + i18n.form.btn_save;
            }

            // Hide edit-only fields
            modal.querySelectorAll('.edit-only').forEach(field => {
                field.style.display = 'none';
            });

            // Clear previous errors
            clearFormErrors(modal);


            // Show modal
            showModal(modal);
        }
    </script>
@endpush
