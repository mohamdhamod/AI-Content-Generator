{{-- Assign Content Modal Component --}}
@props(['content'])

<div class="modal fade" id="assignContentModal" tabindex="-1" aria-labelledby="assignContentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-warning-orange text-white">
                <h5 class="modal-title" id="assignContentModalLabel">
                    <i class="bi bi-person-plus-fill me-2"></i>{{ __('translation.content_generator.assign_content') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Current Assignment Status -->
                <div id="currentAssignment" class="alert alert-info mb-3" style="display: none;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-check fs-4 me-2"></i>
                        <div>
                            <strong>{{ __('translation.content_generator.currently_assigned') }}</strong>
                            <div class="small" id="currentAssignmentInfo"></div>
                        </div>
                    </div>
                </div>

                <form id="assignContentForm">
                    <div class="mb-3">
                        <label for="assignTeamSelect" class="form-label">{{ __('translation.content_generator.select_team') }} <span class="text-danger">*</span></label>
                        <select class="form-select" id="assignTeamSelect" required>
                            <option value="">{{ __('translation.content_generator.choose_team') }}...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="assignMemberSelect" class="form-label">{{ __('translation.content_generator.assign_to') }} <span class="text-danger">*</span></label>
                        <select class="form-select" id="assignMemberSelect" required disabled>
                            <option value="">{{ __('translation.content_generator.first_select_team') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="assignPriority" class="form-label">{{ __('translation.content_generator.priority') }}</label>
                        <select class="form-select" id="assignPriority">
                            <option value="low">🟢 {{ __('translation.content_generator.priority_low') }}</option>
                            <option value="medium" selected>🟡 {{ __('translation.content_generator.priority_medium') }}</option>
                            <option value="high">🟠 {{ __('translation.content_generator.priority_high') }}</option>
                            <option value="urgent">🔴 {{ __('translation.content_generator.priority_urgent') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="assignDueDate" class="form-label">{{ __('translation.content_generator.due_date') }}</label>
                        <input type="datetime-local" class="form-control" id="assignDueDate" min="{{ now()->format('Y-m-d\TH:i') }}">
                    </div>
                    <div class="mb-3">
                        <label for="assignNotes" class="form-label">{{ __('translation.content_generator.notes') }}</label>
                        <textarea class="form-control" id="assignNotes" rows="3" placeholder="{{ __('translation.content_generator.add_instructions_placeholder') }}"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>{{ __('translation.content_generator.cancel') }}
                </button>
                <button type="button" class="btn btn-warning text-white" onclick="assignContent()" id="assignContentBtn">
                    <i class="bi bi-person-plus me-1"></i>{{ __('translation.content_generator.assign_content') }}
                </button>
            </div>
        </div>
    </div>
</div>
