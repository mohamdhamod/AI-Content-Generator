@if($contents->count() > 0)
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 40%">{{ __('translation.content_generator.topic') }}</th>
                    <th>{{ __('translation.content_generator.type') }}</th>
                    <th>{{ __('translation.content_generator.specialty') }}</th>
                    <th>{{ __('translation.content_generator.status.title') }}</th>
                    <th>{{ __('translation.content_generator.date') }}</th>
                    <th class="text-end">{{ __('translation.content_generator.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contents as $content)
                <tr>
                    <td>
                        <div class="fw-medium">{{ Str::limit($content->input_data['topic'] ?? 'N/A', 50) }}</div>
                        <small class="text-muted">
                            @php
                                $langCode = $content->input_data['language'] ?? $content->language ?? 'en';
                                $languages = config('languages', []);
                                $langName = $languages[$langCode]['display'] ?? $langCode;
                            @endphp
                            <i class="bi bi-translate me-1"></i>{{ $langName }}
                        </small>
                    </td>
                    <td>
                        @if($content->contentType)
                        <span class="badge" style="background-color: {{ $content->contentType->color ?? '#4A90D9' }};">
                            {{ $content->contentType->name }}
                        </span>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($content->specialty)
                        <span class="badge bg-light text-dark">
                            <i class="fas {{ $content->specialty->icon ?? 'fa-stethoscope' }} me-1" style="color: {{ $content->specialty->color ?? '#6c757d' }};"></i>
                            {{ $content->specialty->name }}
                        </span>
                        @else
                        <span class="text-muted">{{ __('translation.content_generator.form.general') }}</span>
                        @endif
                    </td>
                    <td>
                        @if($content->status === 'completed')
                        <span class="badge bg-success-subtle text-success">
                            <i class="bi bi-check-circle me-1"></i>{{ __('translation.content_generator.status.completed') }}
                        </span>
                        @elseif($content->status === 'failed')
                        <span class="badge bg-danger-subtle text-danger">
                            <i class="bi bi-x-circle me-1"></i>{{ __('translation.content_generator.status.failed') }}
                        </span>
                        @else
                        <span class="badge bg-warning-subtle text-warning">
                            <i class="bi bi-hourglass-split me-1"></i>{{ __('translation.content_generator.status.processing') }}
                        </span>
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">{{ $content->created_at->format('M d, Y') }}</small>
                        <br>
                        <small class="text-muted">{{ $content->created_at->format('H:i') }}</small>
                    </td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('content.show', $content->id) }}" class="btn btn-outline-primary" title="{{ __('translation.content_generator.view') }}">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    title="{{ __('translation.content_generator.delete') }}"
                                    onclick="deleteContent({{ $content->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    {{ $contents->withQueryString()->links() }}
</div>
@else
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bi bi-inbox display-4 text-muted"></i>
        <h5 class="mt-3 text-muted">{{ __('translation.content_generator.no_history') }}</h5>
        <p class="text-muted mb-4">{{ __('translation.content_generator.no_history_message') }}</p>
        <a href="{{ route('content.index') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>{{ __('translation.content_generator.generate_first') }}
        </a>
    </div>
</div>
@endif

<script>
function deleteContent(id) {
    const url = "{{ route('content.destroy', ['id' => ':id']) }}".replace(':id', id);
    
    if (typeof FormHelper !== 'undefined') {
        FormHelper.confirmDelete(url, {
            confirmTitle: '{{ __("translation.content_generator.confirm_delete_title") }}',
            confirmText: '{{ __("translation.content_generator.confirm_delete") }}',
            confirmButton: '{{ __("translation.common.delete") }}',
            onSuccess: () => location.reload()
        });
    } else if (confirm('{{ __("translation.content_generator.confirm_delete") }}')) {
        fetch(url, { 
            method: 'DELETE', 
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        }).then(() => location.reload());
    }
}
</script>
