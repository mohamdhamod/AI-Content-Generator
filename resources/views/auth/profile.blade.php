@extends('layout.home.main')
@include('layout.extra_meta')
@push('extra_styles')

@endpush
@section('content')
     <div class="mt-4 overflow-hidden align-items-center d-flex">
        <div class="container">
        <!-- Page Header -->
        <div class="row justify-content-start py-3">
            <div class="col-xxl-12 text-start">
            <span class="badge bg-light text-dark fw-normal shadow px-2 py-1 mb-2">
                <i class="bi bi-person-gear me-1"></i> {{ __('translation.profile.header.badge') }}
            </span>
                <p class="text-muted mb-0">
                    {{ __('translation.profile.header.description') }}
                </p>
            </div>
        </div>
        <!-- Profile & Password Forms -->
        <div class="row mb-4">
            <!-- Profile Info -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">{{ __('translation.auth.profile') }}</h5>
                        <div class="card-action">
                            <button type="button" class="card-action-item border-0 btn">
                                <i class="bi bi-chevron-up"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <form id="formProfile" class="row g-3" action="{{ route('user-profile-information.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="col-lg-12">
                                <label for="name" class="form-label">
                                    {{ __('translation.auth.name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="name" name="name" class="form-control"
                                       placeholder="{{ __('translation.auth.name') }}" required
                                       value="{{ old('name', auth()->user()->name) }}">
                            </div>

                            <div class="col-lg-12">
                                <label for="phone" class="form-label">{{ __('translation.auth.phone') }} <span class="text-muted small">({{ __('translation.common.optional') }})</span></label>
                                <input type="tel" id="phone" name="phone" class="form-control"
                                       value="{{ old('phone', auth()->user()->phone) }}">
                            </div>
                            <div class="col-lg-12">
                                <label for="email" class="form-label">
                                    {{ __('translation.auth.email_address') }} <span class="text-danger">*</span>
                                </label>
                                <input type="email" id="email" name="email" class="form-control"
                                        placeholder="{{ __('translation.auth.email_placeholder') }}" required value="{{ old('email', auth()->user()->email) }}">
                            </div>
                            
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">{{ __('translation.submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Change Password -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">{{ __('translation.auth.change_password') }}</h5>
                        <div class="card-action">
                            <button type="button" class="card-action-item border-0 btn">
                                <i class="bi bi-chevron-up"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <form class="row g-3" id="formUpdatePassword" method="POST" enctype="multipart/form-data"
                              action="{{ route('user-password.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="col-lg-12">
                                <label for="password" class="form-label">
                                    {{ __('translation.auth.new_password') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                     <input type="password" id="password" name="password" class="form-control"
                                           placeholder="{{ __('translation.auth.new_password') }}" required>
                                    <button type="button" class="btn btn-light btn-icon togglePassword" aria-label="Show/Hide Password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <label for="password_confirmation" class="form-label">
                                    {{ __('translation.auth.password_confirmation') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                     <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                                           placeholder="{{ __('translation.auth.password_confirmation') }}" required>
                                    <button type="button" class="btn btn-light btn-icon togglePassword" aria-label="Show/Hide Password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-lg-12 d-flex justify-content-between">
                                <button class="btn btn-primary" type="submit">{{ __('translation.submit') }}</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')

@endpush

@push('scripts')
    @include('modules.i18n')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            try { if (window.bindPasswordToggle) bindPasswordToggle(); } catch(e) { console.error(e); }
            
            try { if (window.handleSubmit) handleSubmit('#formProfile'); } catch(e) { console.error(e); }
            try { if (window.handleSubmit) handleSubmit('#formUpdatePassword'); } catch(e) { console.error(e); }
            try { if (window.handleSubmit) handleSubmit('#formCompany'); } catch(e) { console.error(e); }
        });
    </script>
@endpush
