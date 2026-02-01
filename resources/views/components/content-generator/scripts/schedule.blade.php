{{-- Schedule Script --}}
@props(['content'])

<script>
/**
 * Schedule content for future publishing
 */
async function scheduleContent() {
    const scheduledAt = document.getElementById('scheduledAt')?.value;
    const platforms = Array.from(document.querySelectorAll('#scheduleModal input[type="checkbox"]:checked')).map(el => el.value);
    const notes = document.getElementById('scheduleNotes')?.value || '';
    const url = "{{ route('content.calendar.schedule', ['locale' => app()->getLocale(), 'id' => $content->id]) }}";
    
    if (!scheduledAt) {
        SwalHelper.warning('{{ __("translation.content_generator.missing_date") }}', '{{ __("translation.content_generator.select_date_time") }}');
        return;
    }
    
    if (typeof CalendarManager !== 'undefined') {
        CalendarManager.scheduleContent(url, { scheduled_at: scheduledAt, platforms, notes });
        return;
    }

    try {
        const data = await ApiClient.post(url, { scheduled_at: scheduledAt, platforms, notes });
        
        if (data.success) {
            await SwalHelper.success('{{ __("translation.content_generator.content_scheduled") }}!', '{{ __("translation.content_generator.content_scheduled_successfully") }}', { timer: 2000 });
            ModalManager.close('scheduleModal');
            location.reload();
        } else {
            SwalHelper.error('{{ __("translation.content_generator.scheduling_failed") }}', data.message || '{{ __("translation.content_generator.failed_to_schedule") }}');
        }
    } catch (err) {
        SwalHelper.error('{{ __("translation.content_generator.error") }}', '{{ __("translation.content_generator.network_error") }}');
    }
}

/**
 * Publish content immediately
 */
async function publishNow() {
    const url = "{{ route('content.calendar.publish', ['locale' => app()->getLocale(), 'id' => $content->id]) }}";
    
    if (typeof CalendarManager !== 'undefined') {
        CalendarManager.publishNow(url);
        return;
    }

    const result = await SwalHelper.confirm(
        '{{ __("translation.content_generator.publish_now") }}?',
        '{{ __("translation.content_generator.publish_now_confirm") }}',
        '{{ __("translation.content_generator.yes_publish") }}'
    );
    
    if (result.isConfirmed) {
        performPublish();
    }
}

/**
 * Perform the actual publish action
 */
async function performPublish() {
    try {
        const data = await ApiClient.post("{{ route('content.calendar.publish', ['locale' => app()->getLocale(), 'id' => $content->id]) }}");
        
        if (data.success) {
            await SwalHelper.success('{{ __("translation.content_generator.published") }}!', '{{ __("translation.content_generator.content_published_successfully") }}', { timer: 2000 });
            location.reload();
        } else {
            SwalHelper.error('{{ __("translation.content_generator.publishing_failed") }}', data.message || '{{ __("translation.content_generator.failed_to_publish") }}');
        }
    } catch (err) {
        SwalHelper.error('{{ __("translation.content_generator.error") }}', '{{ __("translation.content_generator.network_error") }}');
    }
}
</script>
