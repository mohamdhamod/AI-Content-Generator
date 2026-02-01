{{-- Template Script --}}
@props(['content'])

<script>
/**
 * Save content as a reusable template
 */
async function saveAsTemplate() {
    const name = document.getElementById('templateName')?.value.trim();
    const description = document.getElementById('templateDescription')?.value.trim() || '';
    const category = document.getElementById('templateCategory')?.value || 'general';
    const includeStructure = document.getElementById('includeStructure')?.checked || false;
    const includeTone = document.getElementById('includeTone')?.checked || false;
    const includePromptHints = document.getElementById('includePromptHints')?.checked || false;
    const shareWithTeam = document.getElementById('shareWithTeam')?.checked || false;
    
    if (!name) {
        SwalHelper.warning('{{ __("translation.content_generator.name_required") }}', '{{ __("translation.content_generator.enter_template_name") }}');
        return;
    }
    
    try {
        const data = await ApiClient.post("{{ route('templates.store') }}", {
            name,
            description,
            category,
            content_id: {{ $content->id }},
            include_structure: includeStructure,
            include_tone: includeTone,
            include_prompt_hints: includePromptHints,
            share_with_team: shareWithTeam
        });
        
        if (data.success) {
            const result = await Swal.fire({
                icon: 'success',
                title: '{{ __("translation.content_generator.template_saved") }}!',
                html: `
                    <p>{{ __("translation.content_generator.your_template") }} "<strong>${Utils.escapeHtml(name)}</strong>" {{ __("translation.content_generator.saved_successfully") }}.</p>
                    <p class="text-muted small">{{ __("translation.content_generator.use_template_when_creating") }}</p>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-collection me-1"></i> {{ __("translation.content_generator.view_my_templates") }}',
                cancelButtonText: '{{ __("translation.content_generator.close") }}',
                confirmButtonColor: Utils.colors.primary
            });
            
            ModalManager.close('saveTemplateModal');
            document.getElementById('templateName').value = '';
            document.getElementById('templateDescription').value = '';
            
            if (result.isConfirmed) {
                window.location.href = "{{ route('templates.index') }}";
            }
        } else {
            SwalHelper.error('{{ __("translation.content_generator.save_failed") }}', data.message || '{{ __("translation.content_generator.failed_to_save_template") }}');
        }
    } catch (error) {
        console.error('Error:', error);
        SwalHelper.error('{{ __("translation.content_generator.error") }}', '{{ __("translation.content_generator.network_error") }}');
    }
}
</script>
