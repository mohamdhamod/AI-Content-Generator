@extends('layout.main')
@include('layout.extra_meta')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row justify-content-start py-3">
            <div class="col-xxl-12 col-xl-12 text-start">
                <span class="badge bg-warning text-dark fw-normal shadow px-2 py-1 mb-2">
                    <i class="bi bi-clock-history me-1"></i> {{ __('translation.subscription.pending_subscriptions') }}
                </span>
                <h3 class="fw-bold">{{ __('translation.subscription.pending_subscriptions') }}</h3>
                <p class="text-muted mb-0">
                    {{ __('translation.subscription.pending_description') ?? 'Subscriptions awaiting payment confirmation from Digistore24' }}
                </p>
            </div>
        </div>

        <!-- Info Alert -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <div>
                        {{ __('translation.subscription.pending_auto_note') ?? 'Pending subscriptions will be automatically activated when payment is confirmed via Digistore24 webhook.' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Subscriptions Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-hourglass-split me-2"></i>{{ __('translation.subscription.pending_subscriptions') }}
                        </h5>
                        <span class="badge bg-warning text-dark">
                            {{ method_exists($pendingSubscriptions, 'total') ? $pendingSubscriptions->total() : $pendingSubscriptions->count() }} 
                            {{ __('translation.subscription.pending_count') ?? 'pending' }}
                        </span>
                    </div>

                    <div class="card-body">
                        @if($pendingSubscriptions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('translation.subscription.id') }}</th>
                                            <th>{{ __('translation.subscription.user') ?? 'User' }}</th>
                                            <th>{{ __('translation.subscription.plan') ?? 'Plan' }}</th>
                                            <th>{{ __('translation.subscription.amount') }}</th>
                                            <th>{{ __('translation.subscription.request_date') ?? 'Date' }}</th>
                                            <th>{{ __('translation.subscription.status') }}</th>
                                            <th class="text-center">{{ __('translation.subscription.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingSubscriptions as $userSubscription)
                                            <tr>
                                                <td>
                                                    <strong>#{{ $userSubscription->id }}</strong>
                                                </td>
                                                <td>
                                                    @if($userSubscription->user)
                                                        <div class="d-flex align-items-center">
                                                            <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                                <i class="bi bi-person text-primary"></i>
                                                            </div>
                                                            <div>
                                                                <strong>{{ $userSubscription->user->name }}</strong>
                                                                <br><small class="text-muted">{{ $userSubscription->user->email }}</small>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">{{ __('translation.common.not_specified') ?? 'N/A' }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">
                                                        {{ $userSubscription->subscription?->name ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold text-success">
                                                        {{ $userSubscription->currency ?? 'USD' }} {{ number_format($userSubscription->amount, 2) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span>{{ $userSubscription->created_at->format('Y-m-d') }}</span>
                                                    <br><small class="text-muted">{{ $userSubscription->created_at->diffForHumans() }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-clock me-1"></i>{{ __('translation.subscription.status_pending') }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('subscriptions.dashboard.show', $userSubscription->id) }}"
                                                       class="btn btn-sm btn-outline-primary"
                                                       data-bs-toggle="tooltip"
                                                       data-bs-placement="top"
                                                       title="{{ __('translation.subscription.view') }}">
                                                        <i class="bi bi-eye"></i> {{ __('translation.common.view') ?? 'View' }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            @if(method_exists($pendingSubscriptions, 'links'))
                                <div class="mt-3 d-flex justify-content-center">
                                    {{ $pendingSubscriptions->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="bi bi-check-circle display-1 text-success"></i>
                                </div>
                                <h5 class="text-muted">{{ __('translation.subscription.no_pending_subscriptions') }}</h5>
                                <p class="text-muted mb-0">{{ __('translation.subscription.all_subscriptions_processed') ?? 'All subscriptions have been processed' }}</p>
                                <a href="{{ route('subscriptions.dashboard.index') }}" class="btn btn-outline-primary mt-3">
                                    <i class="bi bi-arrow-left me-1"></i>{{ __('translation.subscription.back_to_list') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
