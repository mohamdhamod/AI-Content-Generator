{{-- Team Collaboration Script --}}
@props(['content'])

<script>
/**
 * Load user's teams using ApiClient
 */
async function loadTeams() {
    const spinner = document.getElementById('teamsLoadingSpinner');
    const container = document.getElementById('teamsContainer');
    const teamsList = document.getElementById('teamsList');
    const noTeamsMsg = document.getElementById('noTeamsMessage');

    try {
        const data = await ApiClient.get("{{ route('teams.index') }}");
        
        if (spinner) spinner.style.display = 'none';
        if (container) container.style.display = 'block';

        if (data.success && data.data?.length > 0) {
            if (noTeamsMsg) noTeamsMsg.style.display = 'none';
            if (teamsList) {
                teamsList.innerHTML = data.data.map(team => {
                    const canInvite = ['owner', 'admin'].includes(team.role);
                    return `
                    <div class="card mb-2 border-0 shadow-sm">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 fw-bold">${Utils.escapeHtml(team.name)}</h6>
                                    <small class="text-muted">
                                        <i class="bi bi-people me-1"></i>${team.member_count} {{ __("translation.content_generator.members") }}
                                        <span class="mx-2">•</span>
                                        <i class="bi bi-file-text me-1"></i>${team.content_count} {{ __("translation.content_generator.contents") }}
                                    </small>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge ${team.role === 'owner' ? 'bg-primary' : team.role === 'admin' ? 'bg-success' : 'bg-secondary'}">${team.role}</span>
                                    ${canInvite ? `
                                    <button class="btn btn-sm btn-success" onclick="inviteMemberToTeam(${team.id}, '${Utils.escapeHtml(team.name).replace(/'/g, "\\'")}')" title="{{ __('translation.content_generator.invite_member') }}">
                                        <i class="bi bi-person-plus"></i>
                                    </button>
                                    ` : ''}
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewTeam(${team.id})" title="{{ __('translation.content_generator.view_team') }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `}).join('');
            }
            populateAssignTeamSelect(data.data);
        } else {
            if (noTeamsMsg) noTeamsMsg.style.display = 'block';
            if (teamsList) teamsList.innerHTML = '';
        }
    } catch (err) {
        if (spinner) spinner.style.display = 'none';
        if (container) container.style.display = 'block';
        if (noTeamsMsg) noTeamsMsg.style.display = 'block';
        console.error('Error loading teams:', err);
    }
}

/**
 * Populate team select in assign modal
 */
function populateAssignTeamSelect(teams) {
    const select = document.getElementById('assignTeamSelect');
    if (!select) return;
    
    select.innerHTML = '<option value="">{{ __("translation.content_generator.choose_team") }}...</option>';
    teams.forEach(team => {
        select.innerHTML += `<option value="${team.id}">${Utils.escapeHtml(team.name)}</option>`;
    });
}

/**
 * View team details using ApiClient
 */
async function viewTeam(teamId) {
    try {
        const data = await ApiClient.get(`{{ url(app()->getLocale()) }}/teams/${teamId}`);
        
        if (data.success) {
            const team = data.data;
            const canInvite = ['owner', 'admin'].includes(team.user_role);
            
            // Build members list
            let membersHtml = '<div class="list-group list-group-flush">';
            if (team.members?.length > 0) {
                team.members.forEach(member => {
                    const roleClass = member.role === 'owner' ? 'bg-primary' : 
                                     member.role === 'admin' ? 'bg-success' : 
                                     member.role === 'editor' ? 'bg-info' : 'bg-secondary';
                    const statusBadge = member.status === 'pending' ? 
                        '<span class="badge bg-warning ms-1">{{ __("translation.content_generator.pending") }}</span>' : '';
                    membersHtml += `
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <strong>${Utils.escapeHtml(member.user?.name || member.pending_email || '{{ __("translation.content_generator.unknown") }}')}</strong>
                                <br><small class="text-muted">${Utils.escapeHtml(member.user?.email || '')}</small>
                            </div>
                            <div>
                                <span class="badge ${roleClass}">${member.role}</span>
                                ${statusBadge}
                            </div>
                        </div>
                    `;
                });
            } else {
                membersHtml += '<div class="text-muted text-center py-2">{{ __("translation.content_generator.no_members") }}</div>';
            }
            membersHtml += '</div>';

            const result = await Swal.fire({
                title: `<i class="bi bi-people-fill text-primary"></i> ${Utils.escapeHtml(team.name)}`,
                html: `
                    <div class="text-start">
                        ${team.description ? `<p class="text-muted">${Utils.escapeHtml(team.description)}</p>` : ''}
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body py-2 text-center">
                                        <div class="fs-4 fw-bold text-primary">${team.members?.length || 0}</div>
                                        <small class="text-muted">{{ __("translation.content_generator.members") }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body py-2 text-center">
                                        <div class="fs-4 fw-bold text-success">${team.statistics?.content?.total || 0}</div>
                                        <small class="text-muted">{{ __("translation.content_generator.contents") }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-2"><i class="bi bi-people me-2"></i>{{ __("translation.content_generator.team_members") }}</h6>
                        ${membersHtml}
                    </div>
                `,
                width: 500,
                showConfirmButton: canInvite,
                confirmButtonText: '<i class="bi bi-person-plus me-2"></i>{{ __("translation.content_generator.invite_member") }}',
                confirmButtonColor: Utils.colors.indigo,
                showCancelButton: true,
                cancelButtonText: '{{ __("translation.content_generator.close") }}',
            });

            if (result.isConfirmed) {
                inviteMemberToTeam(teamId, team.name);
            }
        }
    } catch (err) {
        console.error('Error viewing team:', err);
        SwalHelper.error('{{ __("translation.content_generator.error") }}', '{{ __("translation.content_generator.error_occurred") }}');
    }
}

/**
 * Invite a member to a team
 */
function inviteMemberToTeam(teamId, teamName) {
    // Close modal and clean up
    ModalManager.close('teamCollaborationModal');

    setTimeout(async () => {
        // Step 1: Get email
        const emailResult = await Swal.fire({
            title: '{{ __("translation.content_generator.invite_to") }} ' + Utils.escapeHtml(teamName),
            text: '{{ __("translation.content_generator.enter_email_to_invite") }}:',
            input: 'email',
            inputPlaceholder: 'email@example.com',
            showCancelButton: true,
            confirmButtonText: '{{ __("translation.content_generator.next") }}',
            confirmButtonColor: Utils.colors.indigo,
            cancelButtonText: '{{ __("translation.content_generator.cancel") }}',
            inputValidator: (value) => {
                if (!value) return '{{ __("translation.content_generator.enter_email") }}';
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) return '{{ __("translation.content_generator.enter_valid_email") }}';
            }
        });

        if (!emailResult.isConfirmed || !emailResult.value) return;
        const email = emailResult.value;

        // Step 2: Get role
        const roleResult = await Swal.fire({
            title: '{{ __("translation.content_generator.select_role") }}',
            text: '{{ __("translation.content_generator.choose_role_for") }} ' + email,
            input: 'select',
            inputOptions: {
                'editor': '{{ __("translation.content_generator.role_editor") }}',
                'reviewer': '{{ __("translation.content_generator.role_reviewer") }}',
                'viewer': '{{ __("translation.content_generator.role_viewer") }}',
                'admin': '{{ __("translation.content_generator.role_admin") }}'
            },
            inputPlaceholder: '{{ __("translation.content_generator.select_role") }}',
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-send me-2"></i>{{ __("translation.content_generator.send_invitation") }}',
            confirmButtonColor: Utils.colors.indigo,
            cancelButtonText: '{{ __("translation.content_generator.back") }}',
            inputValidator: (value) => {
                if (!value) return '{{ __("translation.content_generator.select_role") }}';
            },
            showLoaderOnConfirm: true,
            preConfirm: async (role) => {
                try {
                    const data = await ApiClient.post(`{{ url(app()->getLocale()) }}/teams/${teamId}/invite`, { email, role });
                    if (!data.success) throw new Error(data.message || '{{ __("translation.content_generator.failed_to_send_invitation") }}');
                    return data;
                } catch (error) {
                    Swal.showValidationMessage(error.message);
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        });

        if (roleResult.isConfirmed && roleResult.value?.success) {
            SwalHelper.success('{{ __("translation.content_generator.invitation_sent") }}!', '{{ __("translation.content_generator.invitation_email_sent_to") }} ' + email);
        } else if (roleResult.dismiss === Swal.DismissReason.cancel) {
            inviteMemberToTeam(teamId, teamName);
        }
    }, 350);
}

/**
 * Load pending invitations using ApiClient
 */
async function loadInvitations() {
    const spinner = document.getElementById('invitationsLoadingSpinner');
    const container = document.getElementById('invitationsContainer');
    const list = document.getElementById('invitationsList');
    const noMsg = document.getElementById('noInvitationsMessage');
    const countBadge = document.querySelector('.invitations-count');

    try {
        const data = await ApiClient.get("{{ route('teams.invitations.pending') }}");
        
        if (spinner) spinner.style.display = 'none';
        if (container) container.style.display = 'block';

        if (data.success && data.data?.length > 0) {
            if (noMsg) noMsg.style.display = 'none';
            if (countBadge) {
                countBadge.textContent = data.data.length;
                countBadge.style.display = 'inline';
            }

            if (list) {
                list.innerHTML = data.data.map(inv => `
                    <div class="card mb-2 border-0 shadow-sm ${inv.is_expired ? 'opacity-50' : ''}">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 fw-bold">${Utils.escapeHtml(inv.workspace?.name || '{{ __("translation.content_generator.unknown_team") }}')}</h6>
                                    <small class="text-muted">
                                        <i class="bi bi-person me-1"></i>{{ __("translation.content_generator.invited_by") }} ${Utils.escapeHtml(inv.inviter?.name || '{{ __("translation.content_generator.unknown") }}')}
                                        <span class="mx-2">•</span>
                                        ${inv.invited_at}
                                    </small>
                                    <div class="mt-1">
                                        <span class="badge bg-info">${inv.role}</span>
                                        ${inv.is_expired ? '<span class="badge bg-danger ms-1">{{ __("translation.content_generator.expired") }}</span>' : ''}
                                    </div>
                                </div>
                                ${!inv.is_expired ? `
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-success" onclick="acceptInvitation(${inv.id})">
                                            <i class="bi bi-check-lg"></i> {{ __("translation.content_generator.accept") }}
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="declineInvitation(${inv.id})">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        } else {
            if (noMsg) noMsg.style.display = 'block';
            if (list) list.innerHTML = '';
            if (countBadge) countBadge.style.display = 'none';
        }
    } catch (err) {
        if (spinner) spinner.style.display = 'none';
        if (container) container.style.display = 'block';
        if (noMsg) noMsg.style.display = 'block';
        console.error('Error loading invitations:', err);
    }
}

/**
 * Accept an invitation using SwalHelper
 */
async function acceptInvitation(invitationId) {
    const result = await SwalHelper.confirm(
        '{{ __("translation.content_generator.accept_invitation") }}?',
        '{{ __("translation.content_generator.will_join_team") }}',
        '{{ __("translation.content_generator.yes_accept") }}',
        { confirmColor: Utils.colors.success }
    );

    if (result.isConfirmed) {
        try {
            const data = await ApiClient.post(`{{ url(app()->getLocale()) }}/invitations/${invitationId}/accept`);
            
            if (data.success) {
                SwalHelper.success('{{ __("translation.content_generator.joined") }}!', '{{ __("translation.content_generator.now_team_member") }}');
                loadInvitations();
                loadTeams();
            } else {
                SwalHelper.error('{{ __("translation.content_generator.error") }}', data.message || '{{ __("translation.content_generator.failed_to_accept") }}');
            }
        } catch (err) {
            SwalHelper.error('{{ __("translation.content_generator.error") }}', '{{ __("translation.content_generator.error_occurred") }}');
        }
    }
}

/**
 * Decline an invitation using SwalHelper
 */
async function declineInvitation(invitationId) {
    const result = await SwalHelper.confirm(
        '{{ __("translation.content_generator.decline_invitation") }}?',
        '{{ __("translation.content_generator.cannot_join_unless_invited") }}',
        '{{ __("translation.content_generator.yes_decline") }}',
        { confirmColor: Utils.colors.danger, icon: 'warning' }
    );

    if (result.isConfirmed) {
        try {
            const data = await ApiClient.post(`{{ url(app()->getLocale()) }}/invitations/${invitationId}/decline`);
            
            if (data.success) {
                Swal.fire('{{ __("translation.content_generator.declined") }}', '{{ __("translation.content_generator.invitation_declined") }}', 'info');
                loadInvitations();
            } else {
                SwalHelper.error('{{ __("translation.content_generator.error") }}', data.message || '{{ __("translation.content_generator.failed_to_decline") }}');
            }
        } catch (err) {
            SwalHelper.error('{{ __("translation.content_generator.error") }}', '{{ __("translation.content_generator.error_occurred") }}');
        }
    }
}

// Bind create team form using FormHelper
document.addEventListener('DOMContentLoaded', function() {
    const createTeamForm = document.getElementById('createTeamForm');
    if (createTeamForm) {
        FormHelper.bind(createTeamForm, {
            loadingText: '{{ __("translation.content_generator.creating") }}...',
            resetOnSuccess: true,
            closeModalOnSuccess: false,
            onSuccess: (data) => {
                SwalHelper.success('{{ __("translation.content_generator.success") }}!', '{{ __("translation.content_generator.team_created") }}');
                document.getElementById('myTeamsTab')?.click();
                loadTeams();
            },
            onError: (data) => {
                SwalHelper.error('{{ __("translation.content_generator.error") }}', data.message || '{{ __("translation.content_generator.failed_to_create_team") }}');
            }
        });
    }

    // Load teams when modal opens
    document.getElementById('teamCollaborationModal')?.addEventListener('shown.bs.modal', function() {
        loadTeams();
        loadInvitations();
    });

    // Load invitations when tab is clicked
    document.getElementById('invitationsTab')?.addEventListener('click', function() {
        loadInvitations();
    });
});
</script>
