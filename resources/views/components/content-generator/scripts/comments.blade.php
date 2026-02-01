{{-- Comments Script --}}
@props(['content'])

<script>
/**
 * Load comments for this content
 */
async function loadComments() {
    const spinner = document.getElementById('commentsLoadingSpinner');
    const container = document.getElementById('commentsContainer');
    const commentsList = document.getElementById('commentsList');
    const noCommentsMsg = document.getElementById('noCommentsMessage');
    const countSpan = document.getElementById('commentsCount');

    try {
        const data = await ApiClient.get("{{ url(app()->getLocale()) }}/content/{{ $content->id }}/comments", { showLoading: false });
        
        if (spinner) spinner.style.display = 'none';
        if (container) container.style.display = 'block';

        if (data.success && data.data?.length > 0) {
            if (noCommentsMsg) noCommentsMsg.style.display = 'none';
            if (countSpan) countSpan.textContent = data.data.length;
            
            if (commentsList) {
                commentsList.innerHTML = data.data.map(comment => renderComment(comment)).join('');
            }
        } else {
            if (noCommentsMsg) noCommentsMsg.style.display = 'block';
            if (commentsList) commentsList.innerHTML = '';
            if (countSpan) countSpan.textContent = '0';
        }
    } catch (err) {
        if (spinner) spinner.style.display = 'none';
        if (container) container.style.display = 'block';
        console.error('Error loading comments:', err);
    }
}

/**
 * Load comments count for badge
 */
async function loadCommentsCount() {
    try {
        const data = await ApiClient.get("{{ url(app()->getLocale()) }}/content/{{ $content->id }}/comments", { showLoading: false });
        
        if (data.success && data.data?.length > 0) {
            const badge = document.querySelector('.comments-count-badge');
            if (badge) {
                badge.textContent = data.data.length;
                badge.style.display = 'inline';
            }
        }
    } catch (err) {
        console.log('Could not load comments count');
    }
}

/**
 * Render a comment HTML
 */
function renderComment(comment) {
    const isResolved = comment.is_resolved;
    const userName = Utils.escapeHtml(comment.user?.name || '{{ __("translation.content_generator.unknown") }}');
    const commentText = Utils.escapeHtml(comment.comment);
    const annotatedText = comment.annotated_text ? Utils.escapeHtml(comment.annotated_text) : '';
    
    return `
        <div class="card mb-2 ${isResolved ? 'border-success bg-light' : ''}">
            <div class="card-body py-2">
                <div class="d-flex gap-2">
                    <div class="avatar-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 50%; font-size: 12px;">
                        ${userName.charAt(0).toUpperCase()}
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong class="small">${userName}</strong>
                                <span class="text-muted small ms-2">${comment.created_at_human || ''}</span>
                                ${isResolved ? '<span class="badge bg-success ms-2 small">{{ __("translation.content_generator.resolved") }}</span>' : ''}
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-link text-muted p-0" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item small" href="#" onclick="replyToComment(${comment.id})"><i class="bi bi-reply me-2"></i>{{ __("translation.content_generator.reply") }}</a></li>
                                    ${!isResolved ? `<li><a class="dropdown-item small" href="#" onclick="resolveComment(${comment.id})"><i class="bi bi-check-circle me-2"></i>{{ __("translation.content_generator.resolve") }}</a></li>` : ''}
                                </ul>
                            </div>
                        </div>
                        <p class="mb-1 small">${commentText}</p>
                        ${annotatedText ? `<div class="bg-warning-subtle px-2 py-1 rounded small"><i class="bi bi-highlighter me-1"></i>"${annotatedText}"</div>` : ''}
                        ${comment.replies?.length > 0 ? `
                            <div class="ms-3 mt-2 border-start ps-2">
                                ${comment.replies.map(reply => renderComment(reply)).join('')}
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Add new comment
 */
async function addComment() {
    const commentText = document.getElementById('newCommentText')?.value.trim();
    if (!commentText) {
        SwalHelper.warning('{{ __("translation.content_generator.error") }}', '{{ __("translation.content_generator.enter_comment") }}');
        return;
    }

    const payload = { comment: commentText };
    
    // Check if annotation mode is enabled
    if (document.getElementById('annotateText')?.checked) {
        const selection = window.getSelection();
        if (selection.toString().trim()) {
            payload.annotated_text = selection.toString();
        }
    }

    try {
        const data = await ApiClient.post("{{ route('content.comments.add', ['id' => $content->id]) }}", payload, { showLoading: false });
        
        if (data.success) {
            document.getElementById('newCommentText').value = '';
            const annotateCheckbox = document.getElementById('annotateText');
            if (annotateCheckbox) annotateCheckbox.checked = false;
            loadComments();
            loadCommentsCount();
            SwalHelper.toast('{{ __("translation.content_generator.comment_added") }}!');
        } else {
            SwalHelper.error('{{ __("translation.content_generator.error") }}', data.message || '{{ __("translation.content_generator.failed_to_add_comment") }}');
        }
    } catch (err) {
        SwalHelper.error('{{ __("translation.content_generator.error") }}', '{{ __("translation.content_generator.error_occurred") }}');
    }
}

/**
 * Reply to a comment
 */
async function replyToComment(commentId) {
    const result = await Swal.fire({
        title: '{{ __("translation.content_generator.reply_to_comment") }}',
        input: 'textarea',
        inputPlaceholder: '{{ __("translation.content_generator.type_your_reply") }}...',
        showCancelButton: true,
        confirmButtonText: '{{ __("translation.content_generator.reply") }}',
        confirmButtonColor: Utils.colors.teal
    });
    
    if (result.isConfirmed && result.value) {
        try {
            const data = await ApiClient.post(`{{ url(app()->getLocale()) }}/comments/${commentId}/reply`, { comment: result.value }, { showLoading: false });
            
            if (data.success) {
                loadComments();
                SwalHelper.toast('{{ __("translation.content_generator.reply_added") }}!');
            }
        } catch (err) {
            console.error('Error replying:', err);
        }
    }
}

/**
 * Resolve a comment
 */
async function resolveComment(commentId) {
    try {
        const data = await ApiClient.put(`{{ url(app()->getLocale()) }}/comments/${commentId}/resolve`, {}, { showLoading: false });
        
        if (data.success) {
            loadComments();
            SwalHelper.toast('{{ __("translation.content_generator.comment_resolved") }}!');
        }
    } catch (err) {
        console.error('Error resolving comment:', err);
    }
}

/**
 * Filter comments
 */
function filterComments(filter) {
    const buttons = document.querySelectorAll('#commentsContainer .btn-group .btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    const cards = document.querySelectorAll('#commentsList .card');
    cards.forEach(card => {
        const isResolved = card.classList.contains('border-success');
        if (filter === 'all') {
            card.style.display = 'block';
        } else if (filter === 'resolved') {
            card.style.display = isResolved ? 'block' : 'none';
        } else if (filter === 'unresolved') {
            card.style.display = !isResolved ? 'block' : 'none';
        }
    });
}

// Load comments when modal opens
document.getElementById('contentCommentsModal')?.addEventListener('shown.bs.modal', function() {
    loadComments();
});
</script>
