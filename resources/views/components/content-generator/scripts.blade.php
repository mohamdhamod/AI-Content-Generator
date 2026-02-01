{{-- Content Generator Show Page - Scripts Component --}}
@props(['content', 'pptxStyles' => [], 'pptxThemes' => [], 'pptxDetails' => [], 'currentLocale' => 'en'])

<script>
let currentPreviewData = {};

/**
 * Copy content to clipboard
 */
function copyContent() {
    if (typeof ContentManager !== 'undefined') {
        ContentManager.copyContent('content-output', '{{ __("translation.content_generator.copied_success") }}');
    } else {
        const content = document.getElementById('content-output').innerText;
        navigator.clipboard.writeText(content).then(() => {
            if (typeof SwalHelper !== 'undefined') {
                SwalHelper.toast('{{ __("translation.content_generator.copied_success") }}');
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'success', title: '{{ __("translation.content_generator.copied_success") }}', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
            }
        });
    }
}

/**
 * Download content as text file
 */
function downloadContent() {
    if (typeof ContentManager !== 'undefined') {
        ContentManager.downloadContent('content-output', 'generated-content-{{ $content->id }}.txt');
    } else {
        const content = document.getElementById('content-output').innerText;
        const blob = new Blob([content], { type: 'text/plain' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'generated-content-{{ $content->id }}.txt';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
    }
}

/**
 * Toggle favorite status
 */
function toggleFavorite(button) {
    const toggleUrl = "{{ route('content.toggle.favorite', ['id' => ':id']) }}".replace(':id', button.dataset.contentId);
    const messages = {
        favorite: '{{ __("translation.content_generator.add_favorite") }}',
        unfavorite: '{{ __("translation.content_generator.favorited") }}',
        error: '{{ __("translation.content_generator.chat.error_occurred") }}'
    };
    
    if (typeof ContentManager !== 'undefined') {
        ContentManager.toggleFavorite(button, toggleUrl, messages);
    } else {
        const contentId = button.dataset.contentId;
        button.disabled = true;
        button.style.opacity = '0.7';
        
        const icon = button.querySelector('i');
        const text = button.querySelector('.favorite-text');
        const originalIcon = icon.className;
        const originalText = text.textContent;
        
        icon.className = 'bi bi-hourglass-split me-1';
        
        fetch(toggleUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                button.dataset.isFavorited = data.is_favorited;
                icon.className = data.is_favorited ? 'bi bi-star-fill me-1 text-warning' : 'bi bi-star me-1';
                text.textContent = data.message;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'success', title: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
                }
            } else {
                icon.className = originalIcon;
                text.textContent = originalText;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: data.message || messages.error, toast: true, position: 'top-end', timer: 3000 });
                }
            }
        })
        .catch(error => {
            icon.className = originalIcon;
            text.textContent = originalText;
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: messages.error, toast: true, position: 'top-end', timer: 3000 });
            }
        })
        .finally(() => {
            button.disabled = false;
            button.style.opacity = '1';
        });
    }
}

// Social Media Translation strings for JavaScript
const socialMediaTranslations = {
    medicalProfessional: @json(__('translation.content_generator.social_media.medical_professional')),
    healthcareExpert: @json(__('translation.content_generator.social_media.healthcare_expert')),
    medicalContentCreator: @json(__('translation.content_generator.social_media.medical_content_creator')),
    justNow: @json(__('translation.content_generator.social_media.just_now')),
    seeMore: @json(__('translation.content_generator.social_media.see_more')),
    seeLess: @json(__('translation.content_generator.social_media.see_less')),
    more: @json(__('translation.content_generator.social_media.more')),
    follow: @json(__('translation.content_generator.social_media.follow')),
    characterCount: @json(__('translation.content_generator.social_media.character_count')),
    characters: @json(__('translation.content_generator.social_media.characters')),
    aboveRecommended: @json(__('translation.content_generator.social_media.above_recommended')),
    optimalLength: @json(__('translation.content_generator.social_media.optimal_length')),
    bestPractices: @json(__('translation.content_generator.social_media.best_practices')),
    fbLike: @json(__('translation.content_generator.social_media.facebook.like')),
    fbComment: @json(__('translation.content_generator.social_media.facebook.comment')),
    fbShare: @json(__('translation.content_generator.social_media.facebook.share')),
    fbComments: @json(__('translation.content_generator.social_media.facebook.comments')),
    fbShares: @json(__('translation.content_generator.social_media.facebook.shares')),
    xNow: @json(__('translation.content_generator.social_media.twitter.now')),
    xReply: @json(__('translation.content_generator.social_media.twitter.reply')),
    xRepost: @json(__('translation.content_generator.social_media.twitter.repost')),
    xReposts: @json(__('translation.content_generator.social_media.twitter.reposts')),
    xLike: @json(__('translation.content_generator.social_media.twitter.like')),
    xLikes: @json(__('translation.content_generator.social_media.twitter.likes')),
    xViews: @json(__('translation.content_generator.social_media.twitter.views')),
    xQuotes: @json(__('translation.content_generator.social_media.twitter.quotes')),
    xBookmarks: @json(__('translation.content_generator.social_media.twitter.bookmarks')),
    xThreadSuggestion: @json(__('translation.content_generator.social_media.twitter.thread_suggestion')),
    xContentTooLong: @json(__('translation.content_generator.social_media.twitter.content_too_long')),
    liFollow: @json(__('translation.content_generator.social_media.linkedin.follow')),
    liHealthcareExpert: @json(__('translation.content_generator.social_media.linkedin.healthcare_expert')),
    liTimeAgo: @json(__('translation.content_generator.social_media.linkedin.time_ago')),
    liSeeMore: @json(__('translation.content_generator.social_media.linkedin.see_more')),
    liSeeLess: @json(__('translation.content_generator.social_media.linkedin.see_less')),
    liLike: @json(__('translation.content_generator.social_media.linkedin.like')),
    liComment: @json(__('translation.content_generator.social_media.linkedin.comment')),
    liRepost: @json(__('translation.content_generator.social_media.linkedin.repost')),
    liSend: @json(__('translation.content_generator.social_media.linkedin.send')),
    liReactions: @json(__('translation.content_generator.social_media.linkedin.reactions')),
    liComments: @json(__('translation.content_generator.social_media.linkedin.comments')),
    liReposts: @json(__('translation.content_generator.social_media.linkedin.reposts')),
    igLikes: @json(__('translation.content_generator.social_media.instagram.likes')),
    igMore: @json(__('translation.content_generator.social_media.instagram.more')),
    igMoreTags: @json(__('translation.content_generator.social_media.instagram.more_tags')),
    igViewAllComments: @json(__('translation.content_generator.social_media.instagram.view_all_comments')),
    igViewComments: @json(__('translation.content_generator.social_media.instagram.view_comments')),
    igAddComment: @json(__('translation.content_generator.social_media.instagram.add_comment')),
    igPost: @json(__('translation.content_generator.social_media.instagram.post')),
    igTimeAgo: @json(__('translation.content_generator.social_media.instagram.time_ago')),
    igOriginalAudio: @json(__('translation.content_generator.social_media.instagram.original_audio')),
    igAddImage: @json(__('translation.content_generator.social_media.instagram.add_image')),
    igAddImageHere: @json(__('translation.content_generator.social_media.instagram.add_image_here')),
    ttShare: @json(__('translation.content_generator.social_media.tiktok.share')),
    ttYourVideo: @json(__('translation.content_generator.social_media.tiktok.your_video')),
    ttYourVideoHere: @json(__('translation.content_generator.social_media.tiktok.your_video_here')),
    ttOriginalSound: @json(__('translation.content_generator.social_media.tiktok.original_sound')),
    chars: @json(__('translation.content_generator.social_media.chars')),
    threadSuggestion: @json(__('translation.content_generator.social_media.thread_suggestion')),
    contentTooLong: @json(__('translation.content_generator.social_media.content_too_long')),
    copyContent: @json(__('translation.content_generator.social_media.copy_content')),
};

/**
 * Load social media preview
 */
function loadSocialPreview(platform) {
    const loading = document.getElementById('preview-loading');
    const content = document.getElementById('preview-content');
    
    loading.style.display = 'block';
    content.innerHTML = '';
    
    fetch(`{{ route('content.social.preview', $content->id) }}?platform=${platform}`)
        .then(response => response.json())
        .then(data => {
            loading.style.display = 'none';
            if (data.success) {
                currentPreviewData[platform] = data.preview;
                renderPreview(data.preview);
            } else {
                content.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            content.innerHTML = '<div class="alert alert-danger">{{ __("translation.content_generator.chat.error_occurred") }}</div>';
            console.error('Error:', error);
        });
}

// Load Facebook preview by default when modal opens
document.getElementById('socialPreviewModal')?.addEventListener('shown.bs.modal', function () {
    if (Object.keys(currentPreviewData).length === 0) {
        loadSocialPreview('facebook');
    }
});

/**
 * FLOATING ACTION BUTTON Toggle
 */
function toggleFab() {
    document.getElementById('fabContainer')?.classList.toggle('active');
}

// Close FAB when clicking outside
document.addEventListener('click', function(e) {
    const fab = document.getElementById('fabContainer');
    if (fab && !fab.contains(e.target)) {
        fab.classList.remove('active');
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Load sidebar translations
    if (typeof loadSidebarTranslations === 'function') {
        loadSidebarTranslations();
    }
    // Load comments count
    if (typeof loadCommentsCount === 'function') {
        loadCommentsCount();
    }
});
</script>

{{-- Include additional script files --}}
@include('components.content-generator.scripts.social-preview-render')
@include('components.content-generator.scripts.ai-refinement')
@include('components.content-generator.scripts.seo-analysis')
@include('components.content-generator.scripts.translation')
@include('components.content-generator.scripts.template')
@include('components.content-generator.scripts.analytics')
@include('components.content-generator.scripts.team-collaboration')
@include('components.content-generator.scripts.comments')
@include('components.content-generator.scripts.assignment')
@include('components.content-generator.scripts.pptx-export')
@include('components.content-generator.scripts.schedule')
