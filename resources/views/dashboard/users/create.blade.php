@extends('layout.main')
@include('layout.extra_meta')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row justify-content-start text-start mb-3">
        <div class="col-xxl-12 col-xl-12">
        <span class="badge bg-light text-dark shadow px-2 py-1 mb-2 fs-6">
            <i class="bi bi-people me-1"></i> {{ __('translation.users.page_title') }}
        </span>
            <p class="fs-6 text-muted mb-0">
                {{ __('translation.users.page_description') }}
            </p>
        </div>
    </div>

    <!-- Create User Card -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ __('translation.auth.profile') }}</h5>
                    <div class="card-action">
                        <button class="card-action-item border-0 btn" type="button">
                            <i class="bi bi-chevron-up"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="formCreatUser" class="row g-3" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label">
                                {{ __('translation.auth.name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="name" name="name"
                                   placeholder="{{ __('translation.auth.name') }}"
                                   class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6">
                            <label for="phone" class="form-label">{{ __('translation.auth.phone') }}</label>
                            <input type="tel" id="phone" name="phone" class="form-control"
                                   value="{{ old('phone') }}" required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 ">
                            <label for="email" class="form-label">
                                {{ __('translation.auth.email_address') }} <span class="text-danger">*</span>
                            </label>
                            <input type="email" id="email" name="email"
                                placeholder="{{ __('translation.auth.email_placeholder') }}"
                                   class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Roles -->
                        <div class="col-md-6 ">
                            <label for="roles" class="form-label">{{ __('translation.users.roles') }} <span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('roles') is-invalid @enderror"
                                    id="roles" name="roles[]" required multiple
                                    data-placeholder="{{ __('translation.messages.select_an_option') }}">
                                @foreach(\App\Enums\RoleEnum::ALL as $role)
                                    <option value="{{$role}}" {{ old('role') == $role ? 'selected' : '' }} data-role="{{$role}}">{{$role}}</option>
                                @endforeach
                            </select>
                            @error('roles')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="col-md-6 ">
                            <label for="password" class="form-label">
                                {{ __('translation.auth.password') }} <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" id="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="{{ __('translation.auth.password') }}" required>
                                <button type="button" class="btn btn-light btn-icon togglePassword" aria-label="Show/Hide Password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <p class="text-muted fs-xs mb-0">{{ __('translation.auth.password_hint') }}</p>
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6 ">
                            <label for="password_confirmation" class="form-label">
                                {{ __('translation.auth.password_confirmation') }} <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       placeholder="{{ __('translation.auth.password_confirmation') }}" required>
                                <button type="button" class="btn btn-light btn-icon togglePassword" aria-label="Show/Hide Password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">{{ __('translation.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('scripts')
<script>
// Attach handlers from general.js: password toggle and bind submit handler correctly
document.addEventListener('DOMContentLoaded', function() {
    try { if (window.bindPasswordToggle) bindPasswordToggle(); } catch(e) { console.error(e); }
    try { if (window.handleSubmit) handleSubmit('#formCreatUser'); } catch(e) { console.error(e); }
});
</script>
@endpush
