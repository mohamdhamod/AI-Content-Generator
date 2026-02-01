{{-- Schedule Modal Component --}}
@props(['content'])

<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-calendar text-white">
                <h5 class="modal-title" id="scheduleModalLabel">
                    <i class="bi bi-calendar-check me-2"></i>{{ __('translation.content_generator.schedule_content') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="scheduledAt" class="form-label">{{ __('translation.content_generator.schedule_date_time') }}</label>
                    <input type="datetime-local" class="form-control" id="scheduledAt" 
                           min="{{ now()->format('Y-m-d\TH:i') }}"
                           value="{{ $content->scheduled_at ? $content->scheduled_at->format('Y-m-d\TH:i') : '' }}">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">{{ __('translation.content_generator.publishing_platforms') }}</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="blog" id="platformBlog">
                        <label class="form-check-label" for="platformBlog">
                            <i class="bi bi-newspaper text-primary me-1"></i>{{ __('translation.content_generator.blog') }}
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="website" id="platformWebsite">
                        <label class="form-check-label" for="platformWebsite">
                            <i class="bi bi-globe text-info me-1"></i>{{ __('translation.content_generator.website') }}
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="facebook" id="platformFacebook">
                        <label class="form-check-label" for="platformFacebook">
                            <i class="bi bi-facebook text-primary me-1"></i>Facebook
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="twitter" id="platformTwitter">
                        <label class="form-check-label" for="platformTwitter">
                            <i class="bi bi-twitter text-info me-1"></i>Twitter
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="linkedin" id="platformLinkedin">
                        <label class="form-check-label" for="platformLinkedin">
                            <i class="bi bi-linkedin text-primary me-1"></i>LinkedIn
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="instagram" id="platformInstagram">
                        <label class="form-check-label" for="platformInstagram">
                            <i class="bi bi-instagram text-danger me-1"></i>Instagram
                        </label>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="scheduleNotes" class="form-label">{{ __('translation.content_generator.publishing_notes') }}</label>
                    <textarea class="form-control" id="scheduleNotes" rows="3" 
                              placeholder="{{ __('translation.content_generator.add_notes_placeholder') }}">{{ $content->publishing_notes ?? '' }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.content_generator.cancel') }}</button>
                <button type="button" class="btn btn-success" onclick="publishNow()">
                    <i class="bi bi-send me-2"></i>{{ __('translation.content_generator.publish_now') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="scheduleContent()">
                    <i class="bi bi-calendar-check me-2"></i>{{ __('translation.content_generator.schedule') }}
                </button>
            </div>
        </div>
    </div>
</div>
