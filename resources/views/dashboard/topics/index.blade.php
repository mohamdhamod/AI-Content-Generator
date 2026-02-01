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
                            <a href="{{ route('specialties.index') }}">
                                <i class="bi bi-hospital me-1"></i> {{ __('translation.specialties.title') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="bi bi-tags me-1"></i> {{ __('translation.topics.title') }}
                        </li>
                    </ol>
                </nav>
                
                <span class="badge bg-light text-dark fw-normal shadow px-2 py-1 mb-2">
                    <i class="fas {{ $specialty->icon ?? 'fa-stethoscope' }} me-2" style="color: {{ $specialty->color ?? '#6c757d' }};"></i> 
                    {{ $specialty->name }}
                </span>
                <h3 class="fw-bold">{{ __('translation.topics.header_title') }}</h3>
                <p class="text-muted mb-0">
                    {{ __('translation.topics.header_description', ['specialty' => $specialty->name]) }}
                </p>
            </div>
        </div>

        <!-- Topics Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            {{ __('translation.topics.title') }} 
                            <span class="badge bg-secondary">{{ $topics->total() }}</span>
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('specialties.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i> {{ __('translation.topics.buttons.back_to_specialties') }}
                            </a>
                            <button type="button" onclick="openCreate()" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-circle me-2"></i> {{ __('translation.topics.buttons.new_title') }}
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($topics->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th style="width: 60px;">{{ __('translation.topics.icon') }}</th>
                                        <th>{{ __('translation.topics.name') }}</th>
                                        <th>{{ __('translation.topics.description') }}</th>
                                        <th style="width: 80px;" class="text-center">
                                            <i class="fas fa-robot text-primary" title="{{ __('translation.topics.form.prompt_hint') }}"></i>
                                        </th>
                                        <th>{{ __('translation.topics.sort_order') }}</th>
                                        <th>{{ __('translation.topics.status') }}</th>
                                        <th>{{ __('translation.topics.actions') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($topics as $model)
                                        <tr>
                                            <td>
                                                <span class="d-flex align-items-center justify-content-center rounded-circle" 
                                                      style="width: 40px; height: 40px; background-color: {{ $specialty->color ?? '#6c757d' }}20;">
                                                    <i class="fas {{ $model->icon ?? 'fa-circle' }}" style="color: {{ $specialty->color ?? '#6c757d' }};"></i>
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ $model->name }}</strong>
                                            </td>
                                            <td>
                                                @if($model->description)
                                                    <small class="text-muted">{{ Str::limit($model->description, 80) }}</small>
                                                @else
                                                    <small class="text-muted fst-italic">-</small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($model->prompt_hint)
                                                    <span class="badge bg-primary" 
                                                          data-bs-toggle="tooltip" 
                                                          data-bs-placement="top"
                                                          title="{{ Str::limit($model->prompt_hint, 100) }}">
                                                        <i class="fas fa-robot"></i>
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $model->sort_order }}</span>
                                            </td>
                                            <td>
                                                @if($model->active)
                                                    <span class="badge bg-success">{{ __('translation.topics.active') }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ __('translation.topics.inactive') }}</span>
                                                @endif
                                            </td>
                                            <td class="actions">
                                                <!-- Edit button -->
                                                <button type="button"
                                                        class="btn btn-sm btn-primary edit-btn"
                                                        data-model='@json($model)'
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ __('translation.topics.edit') }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <!-- Activate / Deactivate button -->
                                                @php($isActive = isset($model->active) && $model->active)
                                                <button type="button"
                                                        class="btn btn-sm process-btn {{ $isActive ? 'btn-warning' : 'btn-success' }}"
                                                        data-model='@json($model)'
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ $isActive ? __('translation.topics.deactivate') : __('translation.topics.activate') }}"
                                                        aria-pressed="{{ $isActive ? 'true' : 'false' }}">
                                                    <i class="bi {{ $isActive ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                                </button>

                                                <!-- Delete button -->
                                                <button type="button"
                                                        class="btn btn-sm btn-danger delete-btn"
                                                        data-model='@json($model)'
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ __('translation.topics.delete') }}">
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
                                {{ $topics->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="bi bi-inbox display-4 text-muted"></i>
                                </div>
                                <h5 class="text-muted">{{ __('translation.topics.no_topics_found') }}</h5>
                                <p class="text-muted mb-0">{{ __('translation.topics.no_topics_found_message') }}</p>
                                <button type="button" onclick="openCreate()" class="btn btn-primary mt-3">
                                    <i class="bi bi-plus-circle me-2"></i> {{ __('translation.topics.buttons.new_title') }}
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
    @include('dashboard.topics.form', ['specialty' => $specialty])
    @include('modules.confirm')
    @include('modules.confirm_activate')
    @include('modules.i18n', ['page' => 'topics'])
    <script>
        const i18n = window.i18n;
        const specialtyId = {{ $specialty->id }};
        const baseUrl = `{{ route('specialties.topics.index', ['specialty' => $specialty->id]) }}`;
        
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
                    confirmDelete(data, `${baseUrl}/${data.id}`, i18n, data.name);
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

            // Fill form with data
            fillForm(modal, data);

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
