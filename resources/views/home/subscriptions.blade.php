@extends('layout.home.main')
@include('layout.extra_meta')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="fw-bold mb-2">{{ __('translation.subscription.choose_plan') ?? 'Choose Your Plan' }}</h1>
            <p class="text-muted">{{ __('translation.subscription.choose_plan_subtitle') ?? 'Select the perfect plan for your medical content needs' }}</p>
        </div>
    </div>

    <!-- Current Subscription Status -->
    @if($activeSubscription)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                <div>
                    <h5 class="alert-heading mb-1">{{ __('translation.subscription.active_subscription') ?? 'Active Subscription' }}</h5>
                    <p class="mb-0">
                        {{ __('translation.subscription.current_plan') ?? 'Current Plan' }}: 
                        <strong>{{ $activeSubscription->subscription->name ?? 'N/A' }}</strong>
                        <span class="mx-2">|</span>
                        {{ __('translation.subscription.expires_at') ?? 'Expires' }}: 
                        <strong>{{ $activeSubscription->expires_at?->format('M d, Y') ?? 'N/A' }}</strong>
                        @if($activeSubscription->subscription && $activeSubscription->subscription->max_content_generations != -1)
                            <span class="mx-2">|</span>
                            {{ __('translation.subscription.generations_left') ?? 'Generations' }}: 
                            <strong>{{ $activeSubscription->subscription->max_content_generations ?? '∞' }}</strong>
                        @else
                            <span class="mx-2">|</span>
                            <span class="badge bg-success">{{ __('translation.subscription.unlimited') ?? 'Unlimited' }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Subscription Plans -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">
        @forelse($subscriptionPlans as $plan)
        <div class="col">
            <div class="card h-100 {{ $activeSubscription && $activeSubscription->subscription_id == $plan->id ? 'border-success border-2' : 'border-0 shadow-sm' }}">
                @if($activeSubscription && $activeSubscription->subscription_id == $plan->id)
                    <div class="card-header bg-success text-white text-center py-2">
                        <i class="bi bi-check-circle me-1"></i> {{ __('translation.subscription.current_plan') ?? 'Current Plan' }}
                    </div>
                @endif
                
                <div class="card-body text-center">
                    <!-- Plan Name -->
                    <h4 class="card-title fw-bold mb-3">{{ $plan->name }}</h4>
                    
                    <!-- Price -->
                    <div class="mb-3">
                        @if($plan->price == 0)
                            <span class="display-5 fw-bold text-success">{{ __('translation.subscription.free') ?? 'Free' }}</span>
                        @else
                            <span class="display-5 fw-bold">${{ number_format($plan->price, 0) }}</span>
                            <span class="text-muted">/{{ $plan->duration_months == 1 ? __('translation.subscription.month') ?? 'month' : $plan->duration_months . ' ' . (__('translation.subscription.months') ?? 'months') }}</span>
                        @endif
                    </div>
                    
                    <!-- Content Generations -->
                    <div class="mb-3">
                        <span class="badge {{ $plan->max_content_generations == -1 ? 'bg-success' : 'bg-primary' }} fs-6 px-3 py-2">
                            @if($plan->max_content_generations == -1)
                                <i class="bi bi-infinity me-1"></i> {{ __('translation.subscription.unlimited_generations') ?? 'Unlimited Generations' }}
                            @else
                                <i class="bi bi-lightning-fill me-1"></i> {{ $plan->max_content_generations }} {{ __('translation.subscription.generations_per_month') ?? 'generations/month' }}
                            @endif
                        </span>
                    </div>
                    
                    <!-- Description -->
                    <p class="text-muted small mb-3">{{ $plan->description }}</p>
                    
                    <!-- Features -->
                    @if($plan->activeFeatures && $plan->activeFeatures->count() > 0)
                    <div class="text-start mb-3">
                        <ul class="list-unstyled small">
                            @foreach($plan->activeFeatures as $feature)
                                <li class="mb-2 {{ $feature->is_highlighted ? 'text-primary fw-semibold' : '' }}">
                                    <i class="bi {{ $feature->icon ?? 'bi-check' }} {{ $feature->is_highlighted ? 'text-warning' : 'text-success' }} me-2"></i>
                                    {{ $feature->feature_text }}
                                    @if($feature->is_highlighted)
                                        <i class="bi bi-star-fill text-warning ms-1" style="font-size: 0.7em;"></i>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                
                <div class="card-footer bg-transparent border-0 text-center pb-4">
                    @if($activeSubscription && $activeSubscription->subscription_id == $plan->id)
                        <button class="btn btn-outline-success w-100" disabled>
                            <i class="bi bi-check-circle me-1"></i> {{ __('translation.subscription.current_plan') ?? 'Current Plan' }}
                        </button>
                    @elseif($plan->price == 0)
                        <a href="{{ route('content.index') }}" class="btn btn-primary w-100">
                            <i class="bi bi-rocket-takeoff me-1"></i> {{ __('translation.subscription.start_free') ?? 'Start Free' }}
                        </a>
                    @else
                        @if($plan->checkout_url && $plan->digistore_product_id)
                            <a href="{{ $plan->checkout_url }}" class="btn btn-primary w-100" target="_blank">
                                <i class="bi bi-credit-card me-1"></i> {{ __('translation.subscription.subscribe_now') ?? 'Subscribe Now' }}
                            </a>
                        @else
                            <button class="btn btn-secondary w-100" disabled>
                                <i class="bi bi-clock me-1"></i> {{ __('translation.subscription.coming_soon') ?? 'Coming Soon' }}
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle me-2"></i>
                {{ __('translation.subscription.no_plans_available') ?? 'No subscription plans available at the moment.' }}
            </div>
        </div>
        @endforelse
    </div>

    <!-- FAQ Section -->
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <h3 class="text-center mb-4">{{ __('translation.subscription.faq_title') ?? 'Frequently Asked Questions' }}</h3>
            
            <div class="accordion" id="subscriptionFaq">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            {{ __('translation.subscription.faq_1_q') ?? 'What happens when I reach my generation limit?' }}
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#subscriptionFaq">
                        <div class="accordion-body">
                            {{ __('translation.subscription.faq_1_a') ?? 'When you reach your monthly limit, you can upgrade to a higher plan or wait until your next billing cycle when your limits reset.' }}
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            {{ __('translation.subscription.faq_2_q') ?? 'Can I cancel my subscription anytime?' }}
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#subscriptionFaq">
                        <div class="accordion-body">
                            {{ __('translation.subscription.faq_2_a') ?? 'Yes! You can cancel your subscription at any time. Your access will continue until the end of your current billing period.' }}
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            {{ __('translation.subscription.faq_3_q') ?? 'What payment methods do you accept?' }}
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#subscriptionFaq">
                        <div class="accordion-body">
                            {{ __('translation.subscription.faq_3_a') ?? 'We accept all major credit cards, PayPal, and bank transfers through our secure payment partner Digistore24.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
