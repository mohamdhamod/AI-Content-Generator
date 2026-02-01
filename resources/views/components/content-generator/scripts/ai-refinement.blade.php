{{-- AI Refinement Script --}}
@props(['content'])

<script>
/**
 * Apply AI refinement action to content
 */
async function applyRefinement(action) {
    try {
        const data = await ApiClient.post("{{ route('content.refine', ['id' => $content->id]) }}", { action });
        
        if (data.success) {
            const result = await Swal.fire({
                icon: 'success',
                title: '{{ __("translation.content_generator.refinement_applied") }}',
                text: '{{ __("translation.content_generator.content_updated") }}',
                showCancelButton: true,
                confirmButtonText: '{{ __("translation.content_generator.view_changes") }}',
                cancelButtonText: '{{ __("translation.content_generator.close") }}'
            });
            
            if (result.isConfirmed) {
                location.reload();
            }
        } else {
            SwalHelper.error('{{ __("translation.content_generator.error") }}', data.message || '{{ __("translation.content_generator.refinement_failed") }}');
        }
    } catch (error) {
        console.error('Error:', error);
        SwalHelper.error('{{ __("translation.content_generator.error") }}', '{{ __("translation.content_generator.network_error") }}');
    }
}

/**
 * Adjust content tone
 */
async function adjustTone(tone) {
    try {
        const data = await ApiClient.post("{{ route('content.adjust-tone', ['id' => $content->id]) }}", { tone });
        
        if (data.success) {
            const result = await Swal.fire({
                icon: 'success',
                title: '{{ __("translation.content_generator.tone_adjusted") }}',
                text: '{{ __("translation.content_generator.content_updated") }}',
                showCancelButton: true,
                confirmButtonText: '{{ __("translation.content_generator.view_changes") }}',
                cancelButtonText: '{{ __("translation.content_generator.close") }}'
            });
            
            if (result.isConfirmed) {
                location.reload();
            }
        } else {
            SwalHelper.error('{{ __("translation.content_generator.error") }}', data.message || '{{ __("translation.content_generator.tone_adjustment_failed") }}');
        }
    } catch (error) {
        console.error('Error:', error);
        SwalHelper.error('{{ __("translation.content_generator.error") }}', '{{ __("translation.content_generator.network_error") }}');
    }
}
</script>
