@extends('layout.main')
@include('layout.extra_meta')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row justify-content-start py-3">
            <div class="col-xxl-12 col-xl-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('subscriptions.dashboard.index') }}" class="text-muted text-decoration-none mb-2 d-inline-block">
                            <i class="bi bi-arrow-left me-1"></i> {{ __('translation.subscription.back_to_list') }}
                        </a>
                        <h3 class="fw-bold mb-1">{{ __('translation.subscription.subscription_details') }}</h3>
                        <p class="text-muted mb-0">{{ __('translation.subscription.subscription_details') }} #{{ $subscription->id }}</p>
                    </div>
                    <div>
                        @switch($subscription->status)
                            @case('active')
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i>{{ __('translation.subscription.status_active') }}
                                </span>
                                @break
                            @case('pending')
                                <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                    <i class="bi bi-clock me-1"></i>{{ __('translation.subscription.status_pending') }}
                                </span>
                                @break
                            @case('cancelled')
                                <span class="badge bg-secondary fs-6 px-3 py-2">
                                    <i class="bi bi-x-circle me-1"></i>{{ __('translation.subscription.status_cancelled') ?? 'Cancelled' }}
                                </span>
                                @break
                            @case('expired')
                                <span class="badge bg-danger fs-6 px-3 py-2">
                                    <i class="bi bi-calendar-x me-1"></i>{{ __('translation.subscription.status_expired') }}
                                </span>
                                @break
                            @case('refunded')
                                <span class="badge bg-info fs-6 px-3 py-2">
                                    <i class="bi bi-arrow-return-left me-1"></i>{{ __('translation.subscription.status_refunded') ?? 'Refunded' }}
                                </span>
                                @break
                            @default
                                <span class="badge bg-light text-dark fs-6 px-3 py-2">{{ $subscription->status }}</span>
                        @endswitch
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscription Details -->
        <div class="row">
            <!-- Subscription Info -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-info-circle me-2"></i>{{ __('translation.subscription.subscription_information') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label text-muted small">{{ __('translation.subscription.id') }}</label>
                                    <div class="fw-semibold">#{{ $subscription->id }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label text-muted small">{{ __('translation.subscription.plan') ?? 'Plan' }}</label>
                                    <div class="fw-semibold">
                                        <span class="badge bg-primary">{{ $subscription->subscription?->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label text-muted small">{{ __('translation.subscription.amount') }}</label>
                                    <div class="h4 text-success mb-0">
                                        {{ $subscription->currency ?? 'USD' }} {{ number_format($subscription->amount, 2) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label text-muted small">{{ __('translation.subscription.payment_method') ?? 'Payment Method' }}</label>
                                    <div class="fw-semibold">{{ $subscription->payment_method ?? 'Digistore24' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label text-muted small">{{ __('translation.subscription.start_date') }}</label>
                                    <div class="fw-semibold">
                                        @if($subscription->started_at)
                                            {{ $subscription->started_at->format('Y-m-d H:i') }}
                                            <br><small class="text-muted">{{ $subscription->started_at->diffForHumans() }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label text-muted small">{{ __('translation.subscription.expiry_date') }}</label>
                                    <div class="fw-semibold">
                                        @if($subscription->expires_at)
                                            @php
                                                $isExpired = $subscription->expires_at->isPast();
                                            @endphp
                                            <span class="{{ $isExpired ? 'text-danger' : '' }}">
                                                {{ $subscription->expires_at->format('Y-m-d H:i') }}
                                            </span>
                                            <br><small class="text-muted">{{ $subscription->expires_at->diffForHumans() }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($subscription->digistore_order_id)
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label text-muted small">Digistore Order ID</label>
                                    <div class="fw-semibold font-monospace">{{ $subscription->digistore_order_id }}</div>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label text-muted small">{{ __('translation.subscription.created_at') }}</label>
                                    <div class="fw-semibold">
                                        {{ $subscription->created_at->format('Y-m-d H:i') }}
                                        <br><small class="text-muted">{{ $subscription->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Info -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person me-2"></i>{{ __('translation.subscription.user') ?? 'User' }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($subscription->user)
                            <div class="text-center mb-3">
                                <div class="rounded-circle bg-primary-subtle d-inline-flex align-items-center justify-content-center mb-2" style="width: 64px; height: 64px;">
                                    <i class="bi bi-person text-primary fs-3"></i>
                                </div>
                                <h5 class="mb-1">{{ $subscription->user->name }}</h5>
                                <p class="text-muted mb-0">{{ $subscription->user->email }}</p>
                            </div>
                            
                            <hr>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted small">{{ __('translation.subscription.user_id') ?? 'User ID' }}</label>
                                <div class="fw-semibold">#{{ $subscription->user->id }}</div>
                            </div>

                            @if($subscription->user->created_at)
                            <div class="mb-3">
                                <label class="form-label text-muted small">{{ __('translation.subscription.member_since') ?? 'Member Since' }}</label>
                                <div class="fw-semibold">{{ $subscription->user->created_at->format('Y-m-d') }}</div>
                            </div>
                            @endif
                        @else
                            <div class="text-center py-3">
                                <i class="bi bi-person-x display-6 text-muted"></i>
                                <p class="text-muted mb-0">{{ __('translation.subscription.no_user_info') ?? 'No user information available' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
