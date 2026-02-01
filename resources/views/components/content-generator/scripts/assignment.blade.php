{{-- Assignment Script --}}
@props(['content'])

<script>
/**
 * Team select change - load members
 */
document.getElementById('assignTeamSelect')?.addEventListener('change', async function() {
    const teamId = this.value;
    const memberSelect = document.getElementById('assignMemberSelect');
    
    if (!teamId) {
        memberSelect.innerHTML = '<option value="">{{ __("translation.content_generator.first_select_team") }}...</option>';
        memberSelect.disabled = true;
        return;
    }

    memberSelect.innerHTML = '<option value="">{{ __("translation.content_generator.loading_members") }}...</option>';
    memberSelect.disabled = true;

    try {
        const data = await ApiClient.get(`{{ url(app()->getLocale()) }}/teams/${teamId}`, { showLoading: false });
        
        if (data.success && data.data?.members?.length > 0) {
            memberSelect.innerHTML = '<option value="">{{ __("translation.content_generator.select_member") }}...</option>';
            data.data.members.forEach(member => {
                memberSelect.innerHTML += `<option value="${member.user_id}">${Utils.escapeHtml(member.user?.name || '{{ __("translation.content_generator.unknown") }}')} (${member.role})</option>`;
            });
            memberSelect.disabled = false;
        } else {
            memberSelect.innerHTML = '<option value="">{{ __("translation.content_generator.no_members_found") }}</option>';
        }
    } catch (err) {
        memberSelect.innerHTML = '<option value="">{{ __("translation.content_generator.error_loading_members") }}</option>';
    }
});

/**
 * Assign content to team member
 */
async function assignContent() {
    const teamId = document.getElementById('assignTeamSelect')?.value;
    const memberId = document.getElementById('assignMemberSelect')?.value;
    
    if (!teamId || !memberId) {
        SwalHelper.warning('{{ __("translation.content_generator.error") }}', '{{ __("translation.content_generator.select_team_and_member") }}');
        return;
    }

    const btn = document.getElementById('assignContentBtn');
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __("translation.content_generator.assigning") }}...';

    try {
        const data = await ApiClient.post("{{ route('content.assign', ['id' => $content->id]) }}", {
            team_workspace_id: teamId,
            assigned_to: memberId,
            priority: document.getElementById('assignPriority')?.value || 'normal',
            due_date: document.getElementById('assignDueDate')?.value || null,
            notes: document.getElementById('assignNotes')?.value || ''
        }, { showLoading: false });

        btn.disabled = false;
        btn.innerHTML = originalHtml;

        if (data.success) {
            SwalHelper.success('{{ __("translation.content_generator.success") }}!', '{{ __("translation.content_generator.content_assigned") }}');
            ModalManager.close('assignContentModal');
            updateCurrentAssignmentDisplay(data.data);
        } else {
            SwalHelper.error('{{ __("translation.content_generator.error") }}', data.message || '{{ __("translation.content_generator.failed_to_assign") }}');
        }
    } catch (err) {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        SwalHelper.error('{{ __("translation.content_generator.error") }}', '{{ __("translation.content_generator.error_occurred") }}');
    }
}

/**
 * Update the current assignment display
 */
function updateCurrentAssignmentDisplay(assignment) {
    const currentAssignment = document.getElementById('currentAssignment');
    const currentAssignmentInfo = document.getElementById('currentAssignmentInfo');
    
    if (currentAssignment && currentAssignmentInfo && assignment) {
        currentAssignment.style.display = 'block';
        currentAssignmentInfo.innerHTML = `
            <strong>${Utils.escapeHtml(assignment.assigned_to_name || '{{ __("translation.content_generator.team_member") }}')}</strong> 
            ${assignment.team_name ? `(${Utils.escapeHtml(assignment.team_name)})` : ''}
            ${assignment.priority ? `<span class="badge bg-${assignment.priority === 'high' ? 'danger' : assignment.priority === 'urgent' ? 'warning' : 'secondary'} ms-1">${assignment.priority}</span>` : ''}
            ${assignment.due_date ? `<br><small class="text-muted">{{ __("translation.content_generator.due") }}: ${Utils.escapeHtml(assignment.due_date)}</small>` : ''}
        `;
    }
}

// Load teams for assign modal
document.getElementById('assignContentModal')?.addEventListener('shown.bs.modal', function() {
    loadTeams();
});
</script>
