{{-- Content Comments Modal Component --}}
@props(['content'])

<div class="modal fade" id="contentCommentsModal" tabindex="-1" aria-labelledby="contentCommentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-teal-cyan text-white">
                <h5 class="modal-title" id="contentCommentsModalLabel">
                    <i class="bi bi-chat-dots-fill me-2"></i>{{ __('translation.content_generator.comments_discussion') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add Comment Form -->
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex gap-3">
                            <div class="avatar-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="flex-grow-1">
                                <textarea class="form-control mb-2" id="newCommentText" rows="3" placeholder="{{ __('translation.content_generator.add_comment_placeholder') }}"></textarea>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="annotateText">
                                        <label class="form-check-label small" for="annotateText">
                                            <i class="bi bi-highlighter me-1"></i>{{ __('translation.content_generator.add_text_annotation') }}
                                        </label>
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="addComment()">
                                        <i class="bi bi-send me-1"></i>{{ __('translation.content_generator.post_comment') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comments List -->
                <div id="commentsLoadingSpinner" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{ __('translation.content_generator.loading') }}</span>
                    </div>
                </div>
                <div id="commentsContainer" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted small"><span id="commentsCount">0</span> {{ __('translation.content_generator.comments') }}</span>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-secondary active" onclick="filterComments('all')">{{ __('translation.content_generator.all') }}</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="filterComments('unresolved')">{{ __('translation.content_generator.open') }}</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="filterComments('resolved')">{{ __('translation.content_generator.resolved') }}</button>
                        </div>
                    </div>
                    <div id="commentsList"></div>
                    <div id="noCommentsMessage" class="text-center py-4 text-muted" style="display: none;">
                        <i class="bi bi-chat-left-text fs-1 mb-2 d-block"></i>
                        <p>{{ __('translation.content_generator.no_comments_yet') }}</p>
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
