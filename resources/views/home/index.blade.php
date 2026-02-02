@extends('layout.home.main')
@include('layout.extra_meta')

@section('content')
<!-- Hero Section -->
<div class="bg-light py-3">
    <div class="container">
        <div class="row align-items-center g-3 py-2">
            <div class="col-lg-6">
                @auth
                    <span class="badge bg-success-subtle text-success px-3 py-2 mb-3 border border-success border-opacity-25">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        @if(($userData['credits_available'] ?? 0) == -1)
                            {{ __('translation.content_generator.unlimited') }}
                        @else
                            {{ $userData['credits_available'] ?? 0 }}
                        @endif
                        {{ __('translation.home.credits_available') }}
                    </span>
                @else
                    <span class="badge bg-primary text-white px-3 py-2 mb-3 shadow-sm">
                        <i class="bi bi-gift-fill me-2"></i>
                        <strong>{{ __('translation.home.start_free_credits') }}</strong>
                        <i class="bi bi-arrow-right ms-2"></i>
                    </span>
                @endauth
                
                <h1 class="h2 fw-bold mb-3">
                    {{ __('translation.home.welcome_title') }} 
                    <span class="text-primary">{{ __('translation.home.welcome_title_highlight') }}</span>
                </h1>
                
                <p class="text-muted mb-3">
                    {{ __('translation.home.welcome_subtitle') }}
                </p>
                
                <!-- Trust Indicators -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-success-subtle text-success px-2 py-1 border border-success border-opacity-25">
                        <i class="bi bi-shield-check-fill me-1"></i> {{ __('translation.home.medical_accuracy') }}
                    </span>
                    <span class="badge bg-warning-subtle text-warning px-2 py-1 border border-warning border-opacity-25">
                        <i class="bi bi-lightning-charge-fill me-1"></i> {{ __('translation.home.ai_powered') }}
                    </span>
                    <span class="badge bg-info-subtle text-info px-2 py-1 border border-info border-opacity-25">
                        <i class="bi bi-translate me-1"></i> {{ __('translation.home.multi_language') }}
                    </span>
                </div>
                
                @if(($stats['generated_count'] ?? 0) > 0)
                <!-- Social Proof Counter -->
                <div class="alert alert-light border mb-3 py-2">
                    <div class="d-flex align-items-center">
                        <div class="me-2">
                            <div class="d-flex">
                                <span class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;margin-right:-6px;z-index:3;border:2px solid white;font-size:0.65rem;">
                                    <i class="bi bi-person-fill"></i>
                                </span>
                                <span class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;margin-right:-6px;z-index:2;border:2px solid white;font-size:0.65rem;">
                                    <i class="bi bi-person-fill"></i>
                                </span>
                                <span class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;z-index:1;border:2px solid white;font-size:0.65rem;">
                                    <i class="bi bi-person-fill"></i>
                                </span>
                            </div>
                        </div>
                        <small class="text-muted">
                            <strong class="text-dark">{{ number_format($stats['generated_count']) }}+</strong> {{ __('translation.home.pieces_generated') }}
                        </small>
                    </div>
                </div>
                @endif
                
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @auth
                        <a href="{{ route('content.index') }}" class="btn btn-primary px-4">
                            <i class="bi bi-plus-circle me-1"></i>{{ __('translation.home.start_generating') }}
                        </a>
                        <a href="{{ route('content.history') }}" class="btn btn-outline-primary px-4">
                            <i class="bi bi-clock-history me-1"></i>{{ __('translation.home.my_history') }}
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary px-4">
                            <i class="bi bi-person-plus me-1"></i>{{ __('translation.home.sign_up_free') }}
                        </a>
                        <a href="#features" class="btn btn-outline-primary px-4">
                            <i class="bi bi-play-circle me-1"></i>{{ __('translation.home.learn_more') }}
                        </a>
                    @endauth
                </div>
                
                <!-- Stats -->
                <div class="row g-2">
                    <div class="col-4">
                        <div class="text-center p-2 bg-white rounded shadow-sm border border-primary border-opacity-25">
                            <i class="bi bi-heart-pulse text-primary fs-5"></i>
                            <div class="h5 text-primary fw-bold mb-0">{{ $stats['specialties_count'] ?? 11 }}</div>
                            <small class="text-muted">{{ __('translation.home.specialties') }}</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center p-2 bg-white rounded shadow-sm border border-success border-opacity-25">
                            <i class="bi bi-list-ul text-success fs-5"></i>
                            <div class="h5 text-success fw-bold mb-0">{{ $stats['topics_count'] ?? 121 }}</div>
                            <small class="text-muted">{{ __('translation.home.topics') }}</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center p-2 bg-white rounded shadow-sm border border-info border-opacity-25">
                            <i class="bi bi-file-earmark-text text-info fs-5"></i>
                            <div class="h5 text-info fw-bold mb-0">{{ $stats['content_types_count'] ?? 7 }}</div>
                            <small class="text-muted">{{ __('translation.home.types') }}</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <!-- AI Preview Card -->
                <div class="position-relative">
                    <div class="card border-0 shadow">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="spinner-grow spinner-grow-sm text-primary me-2"></div>
                                    <span class="badge bg-primary-subtle text-primary px-2 py-1 border border-primary border-opacity-25">
                                        <i class="bi bi-stars me-1"></i>{{ __('translation.home.ai_generating') }}
                                    </span>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>3.2s
                                </small>
                            </div>
                            
                            <h6 class="fw-bold mb-2">{{ __('translation.home.preview_title') }}</h6>
                            
                            <div class="text-muted mb-2">
                                <p class="mb-1">
                                    <strong class="text-dark">{{ __('translation.home.preview_subtitle') }}</strong>
                                </p>
                                <p class="small mb-0">
                                    {{ __('translation.home.preview_content') }}
                                </p>
                            </div>
                            
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-2 border-top">
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-heart-pulse me-1"></i>{{ __('translation.home.preview_specialty') }}
                                    </span>
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-translate me-1"></i>{{ __('translation.home.preview_language') }}
                                    </span>
                                </div>
                                <span class="badge bg-success-subtle text-success border border-success border-opacity-25">
                                    <i class="bi bi-check-circle me-1"></i>{{ __('translation.home.complete') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="position-absolute top-0 end-0 translate-middle">
                        <span class="badge bg-white text-primary shadow-sm px-2 py-1 border">
                            <i class="bi bi-cpu me-1"></i>AI
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Why Choose Us Section -->
<div class="container py-3">
    <div class="row mb-2">
        <div class="col-lg-8 mx-auto text-center">
            <span class="badge bg-info-subtle text-info mb-2 px-2 py-1 border border-info border-opacity-25">
                <i class="bi bi-award me-1"></i>{{ __('translation.home.why_trust_us') }}
            </span>
            <h3 class="fw-bold mb-2">{{ __('translation.home.why_trust_title') }}</h3>
            <p class="text-muted small">{{ __('translation.home.why_trust_subtitle') }}</p>
        </div>
    </div>
    
    <div class="row g-2">
        <div class="col-md-3 col-sm-6">
            <div class="text-center p-2">
                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-1" style="width:40px;height:40px;">
                    <i class="bi bi-shield-fill-check text-primary fs-5"></i>
                </div>
                <h6 class="fw-bold mb-1">{{ __('translation.home.feature_accuracy_title') }}</h6>
                <p class="text-muted small mb-0">{{ __('translation.home.feature_accuracy_desc') }}</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="text-center p-2">
                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-1" style="width:40px;height:40px;">
                    <i class="bi bi-clock-history text-success fs-5"></i>
                </div>
                <h6 class="fw-bold mb-1">{{ __('translation.home.feature_time_title') }}</h6>
                <p class="text-muted small mb-0">{{ __('translation.home.feature_time_desc') }}</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="text-center p-2">
                <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-1" style="width:40px;height:40px;">
                    <i class="bi bi-people text-warning fs-5"></i>
                </div>
                <h6 class="fw-bold mb-1">{{ __('translation.home.feature_friendly_title') }}</h6>
                <p class="text-muted small mb-0">{{ __('translation.home.feature_friendly_desc') }}</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="text-center p-2">
                <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-1" style="width:40px;height:40px;">
                    <i class="bi bi-graph-up-arrow text-info fs-5"></i>
                </div>
                <h6 class="fw-bold mb-1">{{ __('translation.home.feature_seo_title') }}</h6>
                <p class="text-muted small mb-0">{{ __('translation.home.feature_seo_desc') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Features - 3 Steps -->
<div class="container py-3" id="features">
    <div class="row mb-2">
        <div class="col-lg-8 mx-auto text-center">
            <span class="badge bg-primary-subtle text-primary mb-2 px-2 py-1 border border-primary border-opacity-25">
                <i class="bi bi-lightbulb me-1"></i>{{ __('translation.home.how_it_works_badge') }}
            </span>
            <h3 class="fw-bold mb-2">{{ __('translation.home.how_it_works_title') }}</h3>
            <p class="text-muted small">{{ __('translation.home.how_it_works_subtitle') }}</p>
        </div>
    </div>
    
    <div class="row g-2">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-2">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                        <i class="bi bi-1-circle-fill text-primary fs-3"></i>
                    </div>
                    <h6 class="fw-bold mb-2">{{ __('translation.home.step_1_title') }}</h6>
                    <p class="text-muted small mb-0">{{ __('translation.home.step_1_description') }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-2">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                        <i class="bi bi-2-circle-fill text-success fs-3"></i>
                    </div>
                    <h6 class="fw-bold mb-2">{{ __('translation.home.step_2_title') }}</h6>
                    <p class="text-muted small mb-0">{{ __('translation.home.step_2_description') }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-2">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                        <i class="bi bi-3-circle-fill text-info fs-3"></i>
                    </div>
                    <h6 class="fw-bold mb-2">{{ __('translation.home.step_3_title') }}</h6>
                    <p class="text-muted small mb-0">{{ __('translation.home.step_3_description') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Specialties -->
@if(isset($featuredSpecialties) && $featuredSpecialties->count() > 0)
<div class="bg-light py-3">
    <div class="container">
        <div class="row mb-2">
            <div class="col-lg-8 mx-auto text-center">
                <span class="badge bg-success-subtle text-success mb-2 px-2 py-1 border border-success border-opacity-25">
                    <i class="bi bi-hospital me-1"></i>{{ __('translation.home.specialties_badge') }}
                </span>
                <h3 class="fw-bold mb-2">{{ __('translation.home.specialties_title') }}</h3>
                <p class="text-muted small">{{ __('translation.home.specialties_subtitle') }}</p>
            </div>
        </div>
        
        <div class="row g-2">
            @foreach($featuredSpecialties as $specialty)
            <div class="col-sm-6 col-lg-3">
                <div class="card border-0 bg-white shadow-sm h-100">
                    <div class="card-body text-center p-2">
                        <div class="mb-1">
                            @php
                                // Map Font Awesome icons to Bootstrap Icons
                                $iconMap = [
                                    'fa-tooth' => 'bi-heart-pulse',
                                    'fa-hand-sparkles' => 'bi-droplet',
                                    'fa-stethoscope' => 'bi-hospital',
                                    'fa-person-walking' => 'bi-person-walking',
                                    'fa-heart-pulse' => 'bi-heart-pulse',
                                    'fa-baby' => 'bi-people',
                                    'fa-eye' => 'bi-eye',
                                    'fa-bone' => 'bi-shield-plus',
                                    'fa-brain' => 'bi-brain',
                                    'fa-lungs' => 'bi-lungs',
                                    'fa-syringe' => 'bi-prescription2',
                                ];
                                $icon = $iconMap[$specialty->icon] ?? 'bi-heart-pulse';
                            @endphp
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                <i class="{{ $icon }} text-primary fs-5"></i>
                            </div>
                        </div>
                        <h6 class="fw-bold mb-1">{{ $specialty->name }}</h6>
                        <p class="text-muted small mb-0">
                            <i class="bi bi-list-check me-1"></i>{{ $specialty->topics_count }} {{ __('translation.common.topics') }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        @auth
        <div class="text-center mt-3">
            <a href="{{ route('content.index') }}" class="btn btn-primary px-4">
                <i class="bi bi-arrow-right-circle me-1"></i>{{ __('translation.home.explore_specialties') }}
            </a>
        </div>
        @else
        <div class="text-center mt-3">
            <a href="{{ route('register') }}" class="btn btn-primary px-4">
                <i class="bi bi-arrow-right-circle me-1"></i>{{ __('translation.home.get_started_free') }}
            </a>
        </div>
        @endauth
    </div>
</div>
@endif

<!-- CTA Section -->
<div class="container py-3">
    @guest
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card border-0 shadow-lg overflow-hidden bg-primary">
                <div class="card-body text-center p-3">
                    <div class="mb-2">
                        <div class="bg-white bg-opacity-25 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                            <i class="bi bi-gift text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h5 class="text-white fw-bold mb-2">
                        {{ __('translation.home.credits_cta_title') }}
                    </h5>
                    <p class="text-white mb-3">
                        {{ __('translation.home.credits_cta_description') }}
                    </p>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <div class="bg-white bg-opacity-10 rounded p-2 border border-white border-opacity-25">
                                <div class="mb-1">
                                    <i class="bi bi-gift-fill text-warning" style="font-size: 1.2rem;"></i>
                                </div>
                                <h6 class="text-white mb-1">5</h6>
                                <small class="text-white">{{ __('translation.home.free_credits') }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-white bg-opacity-10 rounded p-2 border border-white border-opacity-25">
                                <div class="mb-1">
                                    <i class="bi bi-hospital text-warning" style="font-size: 1.2rem;"></i>
                                </div>
                                <h6 class="text-white mb-1">11+</h6>
                                <small class="text-white">{{ __('translation.home.specialties') }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-white bg-opacity-10 rounded p-2 border border-white border-opacity-25">
                                <div class="mb-1">
                                    <i class="bi bi-credit-card text-warning" style="font-size: 1.2rem;"></i>
                                </div>
                                <h6 class="text-white mb-1">{{ __('translation.home.no') }}</h6>
                                <small class="text-white">{{ __('translation.home.credit_card') }}</small>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('register') }}" class="btn btn-light px-4 shadow">
                        <i class="bi bi-rocket-takeoff me-2"></i>
                        <strong>{{ __('translation.home.sign_up_free') }}</strong>
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <div class="mt-3">
                        <small class="text-white opacity-75">
                            <i class="bi bi-shield-check me-1"></i>
                            {{ __('translation.home.no_credit_card_required') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card border-0 shadow overflow-hidden bg-gradient-success-green">
                <div class="card-body text-center p-3">
                    <div class="mb-3">
                        <i class="bi bi-magic text-white fs-1"></i>
                    </div>
                    <h4 class="text-white fw-bold mb-2">{{ __('translation.home.auth_cta_title') }}</h4>
                    <p class="text-white opacity-90 mb-3">
                        {{ __('translation.home.you_have') }} 
                        <strong>
                            @if(($userData['credits_available'] ?? 0) == -1)
                                {{ __('translation.content_generator.unlimited') }}
                            @else
                                {{ $userData['credits_available'] ?? 0 }}
                            @endif
                            {{ __('translation.home.credits_text') }}
                        </strong> 
                        {{ __('translation.home.available') }}.
                    </p>
                    
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <a href="{{ route('content.index') }}" class="btn btn-light px-4">
                            <i class="bi bi-plus-circle me-1"></i>{{ __('translation.home.start_generating') }}
                        </a>
                        <a href="{{ route('content.history') }}" class="btn btn-outline-light px-4">
                            <i class="bi bi-clock-history me-1"></i>{{ __('translation.home.view_history') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endguest
</div>

@endsection
