@extends('layout.main')
@include('layout.extra_meta')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row justify-content-start py-3">
            <div class="col-xxl-8 col-xl-10 text-start">
                <span class="badge bg-light text-dark fw-normal shadow px-2 py-1 mb-2">
                    <i class="bi bi-credit-card-2-back me-1"></i> {{ __('translation.subscription.admin_title') }}
                </span>
                <h3 class="fw-bold">{{ __('translation.subscription.admin_header_title') }}</h3>
                <p class="text-muted mb-0">
                    {{ __('translation.subscription.admin_header_description') }}
                </p>
            </div>
        </div>

        <!-- Quick Stats -->
        @php
            $activeCount = $subscriptions->where('status', 'active')->count();
            $pendingCount = $subscriptions->where('status', 'pending')->count();
            $totalRevenue = $subscriptions->where('status', 'active')->sum('amount');
            $totalCount = $subscriptions->total();
        @endphp
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body">
                        <div class="rounded-circle bg-success-subtle d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                            <i class="bi bi-check-circle text-success fs-4"></i>
                        </div>
                        <h4 class="card-title text-success mb-1">{{ $activeCount }}</h4>
                        <p class="card-text text-muted small mb-0">{{ __('translation.subscription.active_subscriptions') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body">
                        <div class="rounded-circle bg-warning-subtle d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                            <i class="bi bi-clock text-warning fs-4"></i>
                        </div>
                        <h4 class="card-title text-warning mb-1">{{ $pendingCount }}</h4>
                        <p class="card-text text-muted small mb-0">{{ __('translation.subscription.pending_subscriptions') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body">
                        <div class="rounded-circle bg-primary-subtle d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                            <i class="bi bi-currency-dollar text-primary fs-4"></i>
                        </div>
                        <h4 class="card-title text-primary mb-1">${{ number_format($totalRevenue, 2) }}</h4>
                        <p class="card-text text-muted small mb-0">{{ __('translation.subscription.total_revenue') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body">
                        <div class="rounded-circle bg-info-subtle d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                            <i class="bi bi-people text-info fs-4"></i>
                        </div>
                        <h4 class="card-title text-info mb-1">{{ $totalCount }}</h4>
                        <p class="card-text text-muted small mb-0">{{ __('translation.subscription.total_subscriptions') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscriptions Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-list-ul me-2"></i>{{ __('translation.subscription.user_subscriptions') ?? 'User Subscriptions' }}
                        </h5>
                        <div class="text-muted small">
                            <i class="bi bi-info-circle me-1"></i>
                            {{ __('translation.subscription.auto_payment_note') ?? 'Payments are processed automatically via Digistore24' }}
                        </div>
                    </div>

                    <div class="card-body">
                        @if($subscriptions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                    <tr>
                                        <th>{{ __('translation.subscription.id') }}</th>
                                        <th>{{ __('translation.subscription.user') ?? 'User' }}</th>
                                        <th>{{ __('translation.subscription.plan') ?? 'Plan' }}</th>
                                        <th>{{ __('translation.subscription.amount') }}</th>
                                        <th>{{ __('translation.subscription.start_date') }}</th>
                                        <th>{{ __('translation.subscription.expiry_date') }}</th>
                                        <th>{{ __('translation.subscription.status') }}</th>
                                        <th>{{ __('translation.subscription.actions') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($subscriptions as $userSubscription)
                                        <tr>
                                            <td><strong>#{{ $userSubscription->id }}</strong></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                                        <i class="bi bi-person text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $userSubscription->user->name ?? __('translation.subscription.na') }}</strong>
                                                        @if($userSubscription->user?->email)
                                                            <br><small class="text-muted">{{ $userSubscription->user->email }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ $userSubscription->subscription?->name ?? __('translation.subscription.na') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">
                                                    {{ $userSubscription->currency ?? 'USD' }} {{ number_format($userSubscription->amount, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($userSubscription->started_at)
                                                    {{ $userSubscription->started_at->format('Y-m-d') }}
                                                    <br><small class="text-muted">{{ $userSubscription->started_at->diffForHumans() }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($userSubscription->expires_at)
                                                    @php
                                                        $isExpired = $userSubscription->expires_at->isPast();
                                                        $isExpiringSoon = !$isExpired && $userSubscription->expires_at->diffInDays(now()) <= 7;
                                                    @endphp
                                                    <span class="{{ $isExpired ? 'text-danger' : ($isExpiringSoon ? 'text-warning' : '') }}">
                                                        {{ $userSubscription->expires_at->format('Y-m-d') }}
                                                    </span>
                                                    <br><small class="text-muted">{{ $userSubscription->expires_at->diffForHumans() }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @switch($userSubscription->status)
                                                    @case('active')
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle me-1"></i>{{ __('translation.subscription.status_active') }}
                                                        </span>
                                                        @break
                                                    @case('pending')
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="bi bi-clock me-1"></i>{{ __('translation.subscription.status_pending') }}
                                                        </span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-secondary">
                                                            <i class="bi bi-x-circle me-1"></i>{{ __('translation.subscription.status_cancelled') ?? 'Cancelled' }}
                                                        </span>
                                                        @break
                                                    @case('expired')
                                                        <span class="badge bg-danger">
                                                            <i class="bi bi-calendar-x me-1"></i>{{ __('translation.subscription.status_expired') }}
                                                        </span>
                                                        @break
                                                    @case('refunded')
                                                        <span class="badge bg-info">
                                                            <i class="bi bi-arrow-return-left me-1"></i>{{ __('translation.subscription.status_refunded') ?? 'Refunded' }}
                                                        </span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-light text-dark">{{ $userSubscription->status }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="{{ route('subscriptions.dashboard.show', $userSubscription->id) }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   data-bs-toggle="tooltip"
                                                   title="{{ __('translation.subscription.view') }}">
                                                    <i class="bi bi-eye"></i>
                                                </a>
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
