@extends('layout.home.main')
@include('layout.extra_meta')

@section('content')

<!-- ============================================================== -->
<!-- PAGE HERO -->
<!-- ============================================================== -->
<div class="page-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="hero-title">
                    <i class="bi bi-list-task me-2"></i>{{ __('translation.content_generator.my_tasks_title') }}
                </h1>
                <p class="hero-subtitle">{{ __('translation.content_generator.my_tasks_subtitle') }}</p>
                
                @php
                    $taskCollection = collect($tasks);
                    $total = $taskCollection->count();
                    $pending = $taskCollection->where('status', 'pending')->count();
                    $inProgress = $taskCollection->where('status', 'in_progress')->count();
                    $completed = $taskCollection->where('status', 'completed')->count();
                    $overdue = $taskCollection->where('is_overdue', true)->count();
                @endphp
                
                <div class="d-flex gap-2 flex-wrap mb-3">
                    <span class="hero-badge">
                        <i class="bi bi-clipboard-check"></i>
                        {{ $total }} {{ __('translation.content_generator.total_tasks') }}
                    </span>
                    @if($overdue > 0)
                    <span class="hero-badge" style="background: rgba(239,68,68,0.3);">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ $overdue }} {{ __('translation.content_generator.overdue') }}
                    </span>
                    @endif
                </div>
                
                <!-- Filter Tabs -->
                <div class="filter-tabs">
                    <a href="{{ route('my-tasks') }}" class="filter-tab {{ !$statusFilter ? 'active' : '' }}">
                        <i class="bi bi-grid-3x3-gap"></i>{{ __('translation.content_generator.all_filter') }}
                    </a>
                    <a href="{{ route('my-tasks', ['status' => 'pending']) }}" class="filter-tab {{ $statusFilter === 'pending' ? 'active' : '' }}">
                        <i class="bi bi-hourglass-split"></i>{{ __('translation.content_generator.pending') }} ({{ $pending }})
                    </a>
                    <a href="{{ route('my-tasks', ['status' => 'in_progress']) }}" class="filter-tab {{ $statusFilter === 'in_progress' ? 'active' : '' }}">
                        <i class="bi bi-play-circle"></i>{{ __('translation.content_generator.in_progress') }} ({{ $inProgress }})
                    </a>
                    <a href="{{ route('my-tasks', ['status' => 'completed']) }}" class="filter-tab {{ $statusFilter === 'completed' ? 'active' : '' }}">
                        <i class="bi bi-check-circle"></i>{{ __('translation.content_generator.completed') }} ({{ $completed }})
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('content.index') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>{{ __('translation.content_generator.generate_content') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- ============================================================== -->
    <!-- QUICK STATS -->
    <!-- ============================================================== -->
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-icon total"><i class="bi bi-clipboard-data"></i></div>
            <div class="stat-value">{{ $total }}</div>
            <div class="stat-label">{{ __('translation.content_generator.total_tasks') }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-icon pending"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-value">{{ $pending }}</div>
            <div class="stat-label">{{ __('translation.content_generator.pending') }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-icon progress"><i class="bi bi-lightning-charge"></i></div>
            <div class="stat-value">{{ $inProgress }}</div>
            <div class="stat-label">{{ __('translation.content_generator.in_progress') }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-icon done"><i class="bi bi-check2-circle"></i></div>
            <div class="stat-value">{{ $completed }}</div>
            <div class="stat-label">{{ __('translation.content_generator.completed') }}</div>
        </div>
        @if($overdue > 0)
        <div class="stat-box">
            <div class="stat-icon overdue"><i class="bi bi-exclamation-circle"></i></div>
            <div class="stat-value">{{ $overdue }}</div>
            <div class="stat-label">{{ __('translation.content_generator.overdue') }}</div>
        </div>
        @endif
    </div>

    <!-- ============================================================== -->
    <!-- TASKS LIST -->
    <!-- ============================================================== -->
    @if($total > 0)
        @foreach($tasks as $task)
        <div class="task-card priority-{{ $task['priority'] }} {{ $task['is_overdue'] ? 'is-overdue' : '' }} {{ $task['status'] === 'completed' ? 'is-completed' : '' }}">
            <div class="task-header">
                <div class="task-badges">
                    <span class="badge-priority {{ $task['priority'] }}">
                        @switch($task['priority'])
                            @case('low') <i class="bi bi-circle-fill me-1 text-priority-low" style="font-size:0.5rem"></i>{{ __('translation.content_generator.priority_low') }} @break
                            @case('medium') <i class="bi bi-circle-fill me-1 text-priority-medium" style="font-size:0.5rem"></i>{{ __('translation.content_generator.priority_medium') }} @break
                            @case('high') <i class="bi bi-circle-fill me-1 text-priority-high" style="font-size:0.5rem"></i>{{ __('translation.content_generator.priority_high') }} @break
                            @case('urgent') <i class="bi bi-circle-fill me-1 text-priority-urgent" style="font-size:0.5rem"></i>{{ __('translation.content_generator.priority_urgent') }} @break
                        @endswitch
                    </span>
                    <span class="badge-status {{ $task['status'] }}">
                        @switch($task['status'])
                            @case('pending') <i class="bi bi-clock me-1"></i>{{ __('translation.content_generator.pending') }} @break
                            @case('in_progress') <i class="bi bi-play-circle me-1"></i>{{ __('translation.content_generator.in_progress') }} @break
                            @case('completed') <i class="bi bi-check-circle me-1"></i>{{ __('translation.content_generator.completed') }} @break
                            @case('cancelled') <i class="bi bi-x-circle me-1"></i>{{ __('translation.content_generator.cancelled') }} @break
                        @endswitch
                    </span>
                    @if($task['is_overdue'])
                    <span class="badge-status badge-overdue">
                        <i class="bi bi-exclamation-triangle me-1"></i>{{ __('translation.content_generator.overdue_badge') }}
                    </span>
                    @endif
                </div>
                <div class="task-actions d-flex gap-2">
                    <a href="{{ route('content.show', $task['content']['id']) }}" class="btn-action btn-view">
                        <i class="bi bi-eye me-1"></i>{{ __('translation.content_generator.view_task') }}
                    </a>
                    @if($task['status'] === 'pending')
                    <button class="btn-action btn-start" onclick="updateTaskStatus({{ $task['id'] }}, 'in_progress')">
                        <i class="bi bi-play me-1"></i>{{ __('translation.content_generator.start_task') }}
                    </button>
                    @elseif($task['status'] === 'in_progress')
                    <button class="btn-action btn-complete" onclick="updateTaskStatus({{ $task['id'] }}, 'completed')">
                        <i class="bi bi-check2 me-1"></i>{{ __('translation.content_generator.complete_task') }}
                    </button>
                    @endif
                </div>
            </div>
            <div class="task-body">
                <a href="{{ route('content.show', $task['content']['id']) }}" class="task-title">
                    {{ $task['content']['title'] }}
                </a>
                @if($task['notes'])
                <p class="task-notes">
                    <i class="bi bi-sticky me-1"></i>{{ $task['notes'] }}
                </p>
                @endif
                
                <div class="task-meta">
                    <div class="meta-item">
                        <i class="bi bi-people"></i>
                        <span>{{ $task['workspace'] }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-person"></i>
                        <span>{{ __('translation.content_generator.assigned_by') }} <strong>{{ $task['assigned_by'] }}</strong></span>
                    </div>
                    @if($task['due_date'])
                    <div class="meta-item {{ $task['is_overdue'] ? 'overdue' : '' }}">
                        <i class="bi bi-calendar-event"></i>
                        <span>{{ __('translation.content_generator.due') }}: <strong>{{ $task['due_date'] }}</strong></span>
                    </div>
                    @endif
                    <div class="meta-item">
                        <i class="bi bi-clock-history"></i>
                        <span>{{ __('translation.content_generator.created') }}: {{ $task['created_at'] }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-inbox"></i>
            </div>
            <h4 class="empty-title">{{ __('translation.content_generator.no_tasks_found') }}</h4>
            <p class="empty-text">
                @if($statusFilter)
                    {{ __('translation.content_generator.no_status_tasks', ['status' => str_replace('_', ' ', $statusFilter)]) }}
                @else
                    {{ __('translation.content_generator.no_assigned_tasks') }}
                @endif
            </p>
            <a href="{{ route('content.index') }}" class="btn-primary-gradient">
                <i class="bi bi-plus-circle me-2"></i>{{ __('translation.content_generator.generate_new_content') }}
            </a>
        </div>
    @endif
</div>

<script>
function updateTaskStatus(taskId, newStatus) {
    // Show loading
    Swal.fire({
        title: '{{ __("translation.content_generator.updating") }}',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    fetch(`{{ url(app()->getLocale()) }}/assignments/${taskId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '{{ __("translation.content_generator.success") }}',
                text: newStatus === 'in_progress' ? '{{ __("translation.content_generator.task_started") }}' : '{{ __("translation.content_generator.task_completed") }}',
                showConfirmButton: false,
                timer: 1500
            }).then(() => location.reload());
        } else {
            Swal.fire('{{ __("translation.content_generator.error") }}', data.message || '{{ __("translation.content_generator.failed_to_update_task") }}', 'error');
        }
    })
    .catch(err => {
        Swal.fire('{{ __("translation.content_generator.error") }}', '{{ __("translation.content_generator.an_error_occurred") }}', 'error');
    });
}
</script>
@endsection
