{{-- Social Media Preview Modal Component --}}
@props(['content'])

<div class="modal fade" id="socialPreviewModal" tabindex="-1" aria-labelledby="socialPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="socialPreviewModalLabel">
                    <i class="bi bi-share me-2"></i>{{ __('translation.content_generator.social_preview') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Platform Tabs -->
                <ul class="nav nav-pills mb-4 justify-content-center flex-wrap gap-2" id="platformTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active text-facebook border-facebook border-2 bg-transparent" id="facebook-tab" data-bs-toggle="pill" data-bs-target="#facebook-preview" 
                                type="button" role="tab" onclick="loadSocialPreview('facebook')">
                            <i class="bi bi-facebook me-2"></i>Facebook
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-twitter border-twitter border-2 bg-transparent" id="twitter-tab" data-bs-toggle="pill" data-bs-target="#twitter-preview" 
                                type="button" role="tab" onclick="loadSocialPreview('twitter')">
                            <i class="bi bi-twitter-x me-2"></i>X
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-linkedin border-linkedin border-2 bg-transparent" id="linkedin-tab" data-bs-toggle="pill" data-bs-target="#linkedin-preview" 
                                type="button" role="tab" onclick="loadSocialPreview('linkedin')">
                            <i class="bi bi-linkedin me-2"></i>LinkedIn
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-instagram border-instagram border-2 bg-transparent" id="instagram-tab" data-bs-toggle="pill" data-bs-target="#instagram-preview" 
                                type="button" role="tab" onclick="loadSocialPreview('instagram')">
                            <i class="bi bi-instagram me-2"></i>Instagram
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-tiktok border-tiktok border-2 bg-transparent" id="tiktok-tab" data-bs-toggle="pill" data-bs-target="#tiktok-preview" 
                                type="button" role="tab" onclick="loadSocialPreview('tiktok')">
                            <svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/></svg>TikTok
                        </button>
                    </li>
                </ul>

                <!-- Preview Content -->
                <div class="tab-content" id="platformTabsContent">
                    <div id="preview-loading" class="text-center py-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">{{ __('translation.content_generator.loading') }}</span>
                        </div>
                        <p class="text-muted mt-2">{{ __('translation.content_generator.loading_preview') }}</p>
                    </div>
                    <div id="preview-content"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>{{ __('translation.common.close') }}
                </button>
            </div>
        </div>
    </div>
</div>
