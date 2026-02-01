{{-- Team Collaboration Modal Component --}}

<div class="modal fade" id="teamCollaborationModal" tabindex="-1" aria-labelledby="teamCollaborationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-indigo text-white">
                <h5 class="modal-title" id="teamCollaborationModalLabel">
                    <i class="bi bi-people-fill me-2"></i>{{ __('translation.content_generator.team_collaboration') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Tabs -->
                <ul class="nav nav-tabs mb-3" id="teamTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="myTeamsTab" data-bs-toggle="tab" data-bs-target="#myTeamsPane" type="button" role="tab">
                            <i class="bi bi-people me-1"></i>{{ __('translation.content_generator.my_teams') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="createTeamTab" data-bs-toggle="tab" data-bs-target="#createTeamPane" type="button" role="tab">
                            <i class="bi bi-plus-circle me-1"></i>{{ __('translation.content_generator.create_team') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="invitationsTab" data-bs-toggle="tab" data-bs-target="#invitationsPane" type="button" role="tab">
                            <i class="bi bi-envelope me-1"></i>{{ __('translation.content_generator.invitations') }}
                            <span class="badge bg-danger ms-1 invitations-count" style="display: none;">0</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="teamTabsContent">
                    <!-- My Teams Pane -->
                    <div class="tab-pane fade show active" id="myTeamsPane" role="tabpanel">
                        <div id="teamsLoadingSpinner" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">{{ __('translation.content_generator.loading') }}</span>
                            </div>
                        </div>
                        <div id="teamsContainer" style="display: none;">
                            <div id="teamsList"></div>
                            <div id="noTeamsMessage" class="text-center py-4 text-muted" style="display: none;">
                                <i class="bi bi-people fs-1 mb-2 d-block"></i>
                                <p>{{ __('translation.content_generator.no_teams_yet') }}</p>
                                <button class="btn btn-primary" onclick="document.getElementById('createTeamTab').click()">
                                    <i class="bi bi-plus-circle me-1"></i>{{ __('translation.content_generator.create_first_team') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Create Team Pane -->
                    <div class="tab-pane fade" id="createTeamPane" role="tabpanel">
                        <form id="createTeamForm">
                            <div class="mb-3">
                                <label for="teamName" class="form-label">{{ __('translation.content_generator.team_name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="teamName" placeholder="{{ __('translation.content_generator.enter_team_name') }}" required maxlength="255">
                            </div>
                            <div class="mb-3">
                                <label for="teamDescription" class="form-label">{{ __('translation.content_generator.description') }}</label>
                                <textarea class="form-control" id="teamDescription" rows="3" placeholder="{{ __('translation.content_generator.describe_team_purpose') }}" maxlength="1000"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="teamPlan" class="form-label">{{ __('translation.content_generator.plan') }}</label>
                                <select class="form-select" id="teamPlan">
                                    <option value="free">{{ __('translation.content_generator.free') }}</option>
                                    <option value="team">{{ __('translation.content_generator.team') }}</option>
                                    <option value="enterprise">{{ __('translation.content_generator.enterprise') }}</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" id="createTeamBtn">
                                <i class="bi bi-plus-circle me-2"></i>{{ __('translation.content_generator.create_team') }}
                            </button>
                        </form>
                    </div>

                    <!-- Invitations Pane -->
                    <div class="tab-pane fade" id="invitationsPane" role="tabpanel">
                        <div id="invitationsLoadingSpinner" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">{{ __('translation.content_generator.loading') }}</span>
                            </div>
                        </div>
                        <div id="invitationsContainer" style="display: none;">
                            <div id="invitationsList"></div>
                            <div id="noInvitationsMessage" class="text-center py-4 text-muted" style="display: none;">
                                <i class="bi bi-envelope-open fs-1 mb-2 d-block"></i>
                                <p>{{ __('translation.content_generator.no_pending_invitations') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>{{ __('translation.content_generator.close') }}
                </button>
            </div>
        </div>
    </div>
</div>
