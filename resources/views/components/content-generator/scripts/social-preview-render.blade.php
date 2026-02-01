{{-- Social Preview Render Script --}}
@props(['content'])

<script>
// Social Media Colors from CSS variables
const socialColors = {
    // Facebook
    fb: {
        get primary() { return Utils.colors.social.facebook; },
        get green() { return Utils.colors.social.facebookGreen; },
        get text() { return Utils.colors.social.facebookText; },
        get secondary() { return Utils.colors.social.facebookSecondary; },
        get reaction() { return Utils.colors.social.facebookReaction; }
    },
    // Twitter/X
    x: {
        get primary() { return Utils.colors.social.twitter; },
        get blue() { return Utils.colors.social.twitterBlue; },
        get bg() { return Utils.colors.social.twitterBg; },
        get border() { return Utils.colors.social.twitterBorder; },
        get text() { return Utils.colors.social.twitterText; },
        get secondary() { return Utils.colors.social.twitterSecondary; }
    },
    // LinkedIn
    li: {
        get primary() { return Utils.colors.social.linkedin; },
        get dark() { return Utils.colors.social.linkedinDark; },
        get bg() { return Utils.colors.social.linkedinBg; },
        get reaction() { return Utils.colors.social.linkedinReaction; },
        get celebrate() { return Utils.colors.social.linkedinCelebrate; }
    },
    // Instagram
    ig: {
        get primary() { return Utils.colors.social.instagram; },
        get blue() { return Utils.colors.social.instagramBlue; },
        get secondary() { return Utils.colors.social.instagramSecondary; },
        get border() { return Utils.colors.social.instagramBorder; },
        get link() { return Utils.colors.social.instagramLink; }
    },
    // TikTok
    tt: {
        get cyan() { return Utils.colors.social.tiktokCyan; },
        get pink() { return Utils.colors.social.tiktokPink; },
        get blue() { return Utils.colors.social.tiktokBlue; }
    },
    // Common
    white: '#fff',
    black: '#000'
};

function renderPreview(preview) {
    const content = document.getElementById('preview-content');
    const t = socialMediaTranslations;
    
    const direction = preview.direction || 'ltr';
    const textAlign = preview.text_align || 'left';
    const isRtl = direction === 'rtl';
    
    let html = '';
    
    if (preview.platform === 'facebook') {
        const fbMaxVisible = 500;
        const fbText = preview.text || '';
        const fbShowMore = fbText.length > fbMaxVisible;
        const fbVisibleText = fbShowMore ? fbText.substring(0, fbMaxVisible) : fbText;
        
        html = `
            <div class="facebook-mockup" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; direction: ${direction};">
                <div class="card border-0 shadow" style="max-width: 680px; margin: 0 auto; border-radius: 8px; background: ${socialColors.white};">
                    <div class="card-body p-0">
                        <div class="d-flex align-items-center p-3">
                            <div class="position-relative">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, ${socialColors.fb.primary}, ${socialColors.fb.green}); color: white;">
                                    <i class="bi bi-hospital-fill" style="font-size: 18px;"></i>
                                </div>
                            </div>
                            <div class="${isRtl ? 'me-3' : 'ms-3'} flex-grow-1">
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold" style="font-size: 15px; color: ${socialColors.fb.text};">${t.medicalProfessional}</span>
                                    <i class="bi bi-patch-check-fill ${isRtl ? 'me-1' : 'ms-1'}" style="color: ${socialColors.fb.primary}; font-size: 14px;"></i>
                                </div>
                                <div class="d-flex align-items-center text-muted" style="font-size: 13px;">
                                    <span>${t.justNow}</span>
                                    <span class="mx-1">·</span>
                                    <i class="bi bi-globe2" style="font-size: 11px;"></i>
                                </div>
                            </div>
                            <button class="btn btn-link text-muted p-2" style="font-size: 20px;"><i class="bi bi-three-dots"></i></button>
                        </div>
                        <div class="px-3 pb-3" style="font-size: 15px; line-height: 1.5; color: ${socialColors.fb.text}; direction: ${direction}; text-align: ${textAlign};">
                            ${preview.headline ? `<div class="fw-bold mb-2" style="font-size: 16px;">${preview.headline}</div>` : ''}
                            <div id="fb-post-text">
                                <span id="fb-visible-text">${fbVisibleText.replace(/\n/g, '<br>')}</span>${fbShowMore ? '...' : ''}
                                ${fbShowMore ? `<span id="fb-hidden-text" style="display: none;">${fbText.substring(fbMaxVisible).replace(/\n/g, '<br>')}</span><span class="text-primary fw-semibold" style="cursor: pointer;" onclick="toggleFbText(this)">${t.seeMore}</span>` : ''}
                            </div>
                        </div>
                        ${preview.hashtags && preview.hashtags.length > 0 ? `<div class="px-3 pb-3">${preview.hashtags.map(tag => `<a href="#" class="text-decoration-none me-1" style="color: ${socialColors.fb.primary}; font-size: 15px;">${tag}</a>`).join('')}</div>` : ''}
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top text-muted" style="font-size: 15px;">
                            <div class="d-flex align-items-center">
                                <div class="d-flex" style="margin-${isRtl ? 'left' : 'right'}: 8px;">
                                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width: 18px; height: 18px; background: ${socialColors.fb.primary}; margin-${isRtl ? 'left' : 'right'}: -4px; border: 2px solid white; z-index: 3;"><i class="bi bi-hand-thumbs-up-fill text-white" style="font-size: 10px;"></i></span>
                                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width: 18px; height: 18px; background: ${socialColors.fb.reaction}; margin-${isRtl ? 'left' : 'right'}: -4px; border: 2px solid white; z-index: 2;"><i class="bi bi-heart-fill text-white" style="font-size: 10px;"></i></span>
                                </div>
                                <span style="color: ${socialColors.fb.secondary};">128</span>
                            </div>
                            <span style="color: ${socialColors.fb.secondary};">24 ${t.fbComments} · 12 ${t.fbShares}</span>
                        </div>
                        <div class="d-flex justify-content-around px-2 py-1 border-top">
                            <button class="btn btn-light flex-fill mx-1 py-2 d-flex align-items-center justify-content-center rounded-2" style="border: none; font-size: 15px; font-weight: 600; color: ${socialColors.fb.secondary}; background: transparent;"><i class="bi bi-hand-thumbs-up ${isRtl ? 'ms-2' : 'me-2'}" style="font-size: 18px;"></i>${t.fbLike}</button>
                            <button class="btn btn-light flex-fill mx-1 py-2 d-flex align-items-center justify-content-center rounded-2" style="border: none; font-size: 15px; font-weight: 600; color: ${socialColors.fb.secondary}; background: transparent;"><i class="bi bi-chat-left ${isRtl ? 'ms-2' : 'me-2'}" style="font-size: 18px;"></i>${t.fbComment}</button>
                            <button class="btn btn-light flex-fill mx-1 py-2 d-flex align-items-center justify-content-center rounded-2" style="border: none; font-size: 15px; font-weight: 600; color: ${socialColors.fb.secondary}; background: transparent;"><i class="bi bi-share ${isRtl ? 'ms-2' : 'me-2'}" style="font-size: 18px;"></i>${t.fbShare}</button>
                        </div>
                    </div>
                </div>
            </div>`;
    } else if (preview.platform === 'twitter') {
        const xText = preview.text || '';
        html = `
            <div class="twitter-mockup" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; direction: ${direction}; background: ${socialColors.x.bg};">
                <div class="card border-0" style="max-width: 598px; margin: 0 auto; border-radius: 0; background: ${socialColors.x.bg}; border-bottom: 1px solid ${socialColors.x.border};">
                    <div class="card-body p-3">
                        <div class="d-flex">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, ${socialColors.x.primary}, ${socialColors.black}); flex-shrink: 0;"><i class="bi bi-hospital-fill text-white" style="font-size: 18px;"></i></div>
                            <div class="${isRtl ? 'me-3' : 'ms-3'} flex-grow-1">
                                <div class="d-flex align-items-center flex-wrap mb-1">
                                    <span class="fw-bold text-white" style="font-size: 15px;">${t.medicalProfessional}</span>
                                    <i class="bi bi-patch-check-fill mx-1" style="color: ${socialColors.x.blue}; font-size: 16px;"></i>
                                    <span style="color: ${socialColors.x.secondary}; font-size: 15px;">@MedicalPro</span>
                                    <span style="color: ${socialColors.x.secondary}; font-size: 15px;" class="mx-1">·</span>
                                    <span style="color: ${socialColors.x.secondary}; font-size: 15px;">${t.xNow}</span>
                                    <button class="btn btn-link p-0 ${isRtl ? 'me-auto' : 'ms-auto'}" style="color: ${socialColors.x.secondary};"><i class="bi bi-three-dots"></i></button>
                                </div>
                                <div class="mb-3" style="font-size: 15px; line-height: 1.5; color: ${socialColors.x.text}; direction: ${direction}; text-align: ${textAlign}; white-space: pre-wrap; word-wrap: break-word;">${xText.replace(/\n/g, '<br>')}</div>
                                ${preview.hashtags && preview.hashtags.length > 0 ? `<div class="mb-3">${preview.hashtags.map(tag => `<a href="#" class="text-decoration-none me-2" style="color: ${socialColors.x.blue}; font-size: 15px;">${tag}</a>`).join('')}</div>` : ''}
                                <div class="mb-3 pb-3 border-bottom" style="border-color: ${socialColors.x.border} !important;">
                                    <span style="color: ${socialColors.x.secondary}; font-size: 15px;">12:30 PM · ${new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                                    <span style="color: ${socialColors.x.secondary}; font-size: 15px;"> · </span>
                                    <span style="color: ${socialColors.x.secondary}; font-size: 15px;"><span class="fw-bold text-white">1.2M</span> ${t.xViews}</span>
                                </div>
                                <div class="d-flex gap-4 mb-3 pb-3 border-bottom" style="border-color: ${socialColors.x.border} !important;">
                                    <span style="color: ${socialColors.x.secondary}; font-size: 14px;"><span class="fw-bold text-white">245</span> ${t.xReposts}</span>
                                    <span style="color: ${socialColors.x.secondary}; font-size: 14px;"><span class="fw-bold text-white">89</span> ${t.xQuotes}</span>
                                    <span style="color: ${socialColors.x.secondary}; font-size: 14px;"><span class="fw-bold text-white">1.5K</span> ${t.xLikes}</span>
                                    <span style="color: ${socialColors.x.secondary}; font-size: 14px;"><span class="fw-bold text-white">12</span> ${t.xBookmarks}</span>
                                </div>
                                <div class="d-flex justify-content-between" style="max-width: 425px;">
                                    <button class="btn btn-link p-2 rounded-circle" style="color: ${socialColors.x.secondary};"><i class="bi bi-chat" style="font-size: 18px;"></i></button>
                                    <button class="btn btn-link p-2 rounded-circle" style="color: ${socialColors.x.secondary};"><i class="bi bi-repeat" style="font-size: 18px;"></i></button>
                                    <button class="btn btn-link p-2 rounded-circle" style="color: ${socialColors.x.secondary};"><i class="bi bi-heart" style="font-size: 18px;"></i></button>
                                    <button class="btn btn-link p-2 rounded-circle" style="color: ${socialColors.x.secondary};"><i class="bi bi-bar-chart" style="font-size: 18px;"></i></button>
                                    <button class="btn btn-link p-2 rounded-circle" style="color: ${socialColors.x.secondary};"><i class="bi bi-bookmark" style="font-size: 18px;"></i></button>
                                    <button class="btn btn-link p-2 rounded-circle" style="color: ${socialColors.x.secondary};"><i class="bi bi-upload" style="font-size: 18px;"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center py-2" style="background: ${socialColors.black};">
                    <span class="badge ${preview.current_length > preview.max_length ? 'bg-danger' : preview.current_length > preview.recommended_length ? 'bg-warning' : 'bg-success'}" style="font-size: 13px; padding: 8px 16px;">
                        <i class="bi bi-fonts ${isRtl ? 'ms-2' : 'me-2'}"></i>${preview.current_length} / ${preview.max_length} ${t.characters}
                    </span>
                </div>
            </div>`;
    } else if (preview.platform === 'linkedin') {
        const liMaxVisible = 500;
        const liText = preview.text || '';
        const liShowMore = liText.length > liMaxVisible;
        const liVisibleText = liShowMore ? liText.substring(0, liMaxVisible) : liText;
        
        html = `
            <div class="linkedin-mockup" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; direction: ${direction}; background: ${socialColors.li.bg};">
                <div class="card border-0 shadow-sm" style="max-width: 552px; margin: 0 auto; border-radius: 8px; background: ${socialColors.white};">
                    <div class="card-body p-0">
                        <div class="d-flex align-items-start p-3 pb-0">
                            <div class="position-relative">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, ${socialColors.li.primary}, ${socialColors.li.dark});"><i class="bi bi-hospital-fill text-white" style="font-size: 20px;"></i></div>
                                <span class="position-absolute bg-success rounded-circle border border-2 border-white" style="width: 14px; height: 14px; bottom: 0; ${isRtl ? 'left' : 'right'}: 0;"></span>
                            </div>
                            <div class="${isRtl ? 'me-2' : 'ms-2'} flex-grow-1">
                                <div class="d-flex align-items-center">
                                    <span class="fw-semibold" style="font-size: 14px; color: rgba(0,0,0,0.9);">${t.medicalProfessional}</span>
                                    <span class="mx-1" style="color: rgba(0,0,0,0.6);">•</span>
                                    <span style="color: ${socialColors.li.primary}; font-size: 14px; font-weight: 600;">${t.liFollow}</span>
                                </div>
                                <div style="font-size: 12px; color: rgba(0,0,0,0.6); line-height: 1.3;">${t.liHealthcareExpert}</div>
                                <div class="d-flex align-items-center" style="font-size: 12px; color: rgba(0,0,0,0.6);"><span>${t.liTimeAgo}</span><span class="mx-1">•</span><i class="bi bi-globe2" style="font-size: 11px;"></i></div>
                            </div>
                            <div class="d-flex"><button class="btn btn-link p-1" style="color: rgba(0,0,0,0.6);"><i class="bi bi-three-dots"></i></button><button class="btn btn-link p-1" style="color: rgba(0,0,0,0.6);"><i class="bi bi-x-lg"></i></button></div>
                        </div>
                        <div class="px-3 pt-2 pb-3" style="font-size: 14px; line-height: 1.5; color: rgba(0,0,0,0.9); direction: ${direction}; text-align: ${textAlign};">
                            ${preview.headline ? `<div class="fw-semibold mb-2" style="font-size: 16px;">${preview.headline}</div>` : ''}
                            <div id="li-post-text">
                                <span id="li-visible-text">${liVisibleText.replace(/\n/g, '<br>')}</span>${liShowMore ? '...' : ''}
                                ${liShowMore ? `<span id="li-hidden-text" style="display: none;">${liText.substring(liMaxVisible).replace(/\n/g, '<br>')}</span><span class="fw-semibold" style="color: rgba(0,0,0,0.6); cursor: pointer;" onclick="toggleLiText(this)">${t.liSeeMore}</span>` : ''}
                            </div>
                        </div>
                        ${preview.hashtags && preview.hashtags.length > 0 ? `<div class="px-3 pb-3">${preview.hashtags.map(tag => `<a href="#" class="text-decoration-none me-1" style="color: ${socialColors.li.primary}; font-size: 14px; font-weight: 600;">${tag}</a>`).join('')}</div>` : ''}
                        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-top" style="font-size: 12px; color: rgba(0,0,0,0.6);">
                            <div class="d-flex align-items-center">
                                <div class="d-flex">
                                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width: 16px; height: 16px; background: ${socialColors.li.primary}; margin-${isRtl ? 'left' : 'right'}: -3px; border: 1px solid white; z-index: 3;"><i class="bi bi-hand-thumbs-up-fill text-white" style="font-size: 9px;"></i></span>
                                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width: 16px; height: 16px; background: ${socialColors.li.reaction}; margin-${isRtl ? 'left' : 'right'}: -3px; border: 1px solid white; z-index: 2;"><i class="bi bi-heart-fill text-white" style="font-size: 9px;"></i></span>
                                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width: 16px; height: 16px; background: ${socialColors.li.celebrate}; border: 1px solid white; z-index: 1;"><i class="bi bi-emoji-smile-fill text-white" style="font-size: 9px;"></i></span>
                                </div>
                                <span class="${isRtl ? 'me-2' : 'ms-2'}">1,247</span>
                            </div>
                            <span>89 ${t.liComments} • 34 ${t.liReposts}</span>
                        </div>
                        <div class="d-flex justify-content-around px-2 py-1 border-top">
                            <button class="btn btn-light flex-fill mx-1 py-2 d-flex align-items-center justify-content-center rounded-1" style="border: none; font-size: 14px; font-weight: 600; color: rgba(0,0,0,0.6); background: transparent;"><i class="bi bi-hand-thumbs-up ${isRtl ? 'ms-2' : 'me-2'}" style="font-size: 18px;"></i>${t.liLike}</button>
                            <button class="btn btn-light flex-fill mx-1 py-2 d-flex align-items-center justify-content-center rounded-1" style="border: none; font-size: 14px; font-weight: 600; color: rgba(0,0,0,0.6); background: transparent;"><i class="bi bi-chat-left-text ${isRtl ? 'ms-2' : 'me-2'}" style="font-size: 18px;"></i>${t.liComment}</button>
                            <button class="btn btn-light flex-fill mx-1 py-2 d-flex align-items-center justify-content-center rounded-1" style="border: none; font-size: 14px; font-weight: 600; color: rgba(0,0,0,0.6); background: transparent;"><i class="bi bi-repeat ${isRtl ? 'ms-2' : 'me-2'}" style="font-size: 18px;"></i>${t.liRepost}</button>
                            <button class="btn btn-light flex-fill mx-1 py-2 d-flex align-items-center justify-content-center rounded-1" style="border: none; font-size: 14px; font-weight: 600; color: rgba(0,0,0,0.6); background: transparent;"><i class="bi bi-send ${isRtl ? 'ms-2' : 'me-2'}" style="font-size: 18px;"></i>${t.liSend}</button>
                        </div>
                    </div>
                </div>
            </div>`;
    } else if (preview.platform === 'instagram') {
        const igMaxVisible = 125;
        const igText = preview.text || '';
        const igShowMore = igText.length > igMaxVisible;
        const igVisibleText = igShowMore ? igText.substring(0, igMaxVisible) : igText;
        
        html = `
            <div class="instagram-mockup" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; direction: ${direction}; background: ${socialColors.black};">
                <div class="card border-0" style="max-width: 470px; margin: 0 auto; background: ${socialColors.black};">
                    <div class="d-flex align-items-center p-3">
                        <div class="rounded-circle p-0.5" style="background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); padding: 2px;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: ${socialColors.black}; border: 2px solid ${socialColors.black};"><i class="bi bi-hospital-fill" style="font-size: 14px; color: ${socialColors.white};"></i></div>
                        </div>
                        <div class="${isRtl ? 'me-3' : 'ms-3'} flex-grow-1">
                            <div class="d-flex align-items-center"><span class="fw-semibold text-white" style="font-size: 14px;">medicalpro</span><i class="bi bi-patch-check-fill mx-1" style="color: ${socialColors.ig.blue}; font-size: 14px;"></i></div>
                            <span style="font-size: 12px; color: ${socialColors.ig.secondary};">${t.igOriginalAudio}</span>
                        </div>
                        <button class="btn btn-link text-white p-0"><i class="bi bi-three-dots"></i></button>
                    </div>
                    <div class="position-relative" style="background: linear-gradient(135deg, #405DE6, #5851DB, #833AB4, #C13584, #E1306C, #FD1D1D); aspect-ratio: 1/1;">
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <div class="text-center text-white p-4"><i class="bi bi-image" style="font-size: 64px; opacity: 0.5;"></i><p class="mt-2 mb-0" style="font-size: 14px; opacity: 0.7;">${t.igAddImage}</p></div>
                        </div>
                    </div>
                    <div class="p-3 pb-2">
                        <div class="d-flex justify-content-between mb-2">
                            <div class="d-flex">
                                <button class="btn btn-link text-white p-0 ${isRtl ? 'ms-3' : 'me-3'}"><i class="bi bi-heart" style="font-size: 24px;"></i></button>
                                <button class="btn btn-link text-white p-0 ${isRtl ? 'ms-3' : 'me-3'}"><i class="bi bi-chat" style="font-size: 24px;"></i></button>
                                <button class="btn btn-link text-white p-0"><i class="bi bi-send" style="font-size: 24px;"></i></button>
                            </div>
                            <button class="btn btn-link text-white p-0"><i class="bi bi-bookmark" style="font-size: 24px;"></i></button>
                        </div>
                        <div class="fw-semibold text-white mb-2" style="font-size: 14px;">2,847 ${t.igLikes}</div>
                        <div id="ig-caption" style="font-size: 14px; direction: ${direction}; text-align: ${textAlign};">
                            <span class="fw-semibold text-white">medicalpro</span>
                            <span class="text-white"> </span>
                            <span id="ig-visible-text" class="text-white">${igVisibleText.replace(/\n/g, '<br>')}</span>
                            ${igShowMore ? `<span id="ig-hidden-text" style="display: none;" class="text-white">${igText.substring(igMaxVisible).replace(/\n/g, '<br>')}</span><span id="ig-more-btn" style="color: ${socialColors.ig.secondary}; cursor: pointer;" onclick="toggleIgText(this)">... ${t.igMore}</span>` : ''}
                        </div>
                        ${preview.hashtags && preview.hashtags.length > 0 ? `<div class="mt-2" id="ig-hashtags" style="font-size: 14px; line-height: 1.6;">${preview.hashtags.slice(0, 5).map(tag => `<a href="#" class="text-decoration-none me-1" style="color: ${socialColors.ig.link};">${tag}</a>`).join('')}${preview.hashtags.length > 5 ? `<span id="ig-more-tags" style="display: none;">${preview.hashtags.slice(5).map(tag => `<a href="#" class="text-decoration-none me-1" style="color: ${socialColors.ig.link};">${tag}</a>`).join('')}</span><span style="color: ${socialColors.ig.secondary}; cursor: pointer;" onclick="toggleIgTags(this)">...+${preview.hashtags.length - 5} ${t.igMoreTags}</span>` : ''}</div>` : ''}
                        <div class="mt-2" style="color: ${socialColors.ig.secondary}; font-size: 14px; cursor: pointer;">${t.igViewAllComments}</div>
                        <div class="mt-1" style="color: ${socialColors.ig.secondary}; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px;">${t.igTimeAgo}</div>
                    </div>
                    <div class="d-flex align-items-center px-3 py-2 border-top" style="border-color: ${socialColors.ig.border} !important;">
                        <button class="btn btn-link p-0 ${isRtl ? 'ms-3' : 'me-3'}"><span style="font-size: 24px;">😊</span></button>
                        <input type="text" class="form-control bg-transparent border-0 text-white" placeholder="${t.igAddComment}" style="font-size: 14px;">
                        <button class="btn btn-link p-0 text-primary fw-semibold" style="font-size: 14px; color: ${socialColors.ig.blue} !important;">${t.igPost}</button>
                    </div>
                </div>
            </div>`;
    } else if (preview.platform === 'tiktok') {
        const ttText = preview.text || '';
        html = `
            <div class="tiktok-mockup" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; direction: ${direction}; background: ${socialColors.black};">
                <div class="position-relative" style="max-width: 400px; margin: 0 auto; aspect-ratio: 9/16; background: linear-gradient(135deg, ${socialColors.tt.cyan}, ${socialColors.tt.pink}); border-radius: 12px; overflow: hidden;">
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <div class="text-center text-white"><i class="bi bi-play-circle" style="font-size: 64px; opacity: 0.7;"></i><p class="mt-2 mb-0" style="font-size: 14px; opacity: 0.7;">${t.ttYourVideo}</p></div>
                    </div>
                    <div class="position-absolute d-flex flex-column align-items-center gap-3" style="${isRtl ? 'left' : 'right'}: 12px; bottom: 100px;">
                        <div class="text-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mb-1" style="width: 48px; height: 48px; background: rgba(255,255,255,0.1);"><i class="bi bi-hospital-fill text-white" style="font-size: 20px;"></i></div>
                            <span class="badge bg-danger rounded-pill" style="font-size: 10px;">+</span>
                        </div>
                        <div class="text-center"><button class="btn btn-link text-white p-0"><i class="bi bi-heart-fill" style="font-size: 32px;"></i></button><div class="text-white" style="font-size: 12px;">12.5K</div></div>
                        <div class="text-center"><button class="btn btn-link text-white p-0"><i class="bi bi-chat-dots-fill" style="font-size: 32px;"></i></button><div class="text-white" style="font-size: 12px;">847</div></div>
                        <div class="text-center"><button class="btn btn-link text-white p-0"><i class="bi bi-bookmark-fill" style="font-size: 32px;"></i></button><div class="text-white" style="font-size: 12px;">2.1K</div></div>
                        <div class="text-center"><button class="btn btn-link text-white p-0"><i class="bi bi-share-fill" style="font-size: 32px;"></i></button><div class="text-white" style="font-size: 12px;">${t.ttShare}</div></div>
                    </div>
                    <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                        <div class="d-flex align-items-center mb-2"><span class="fw-bold text-white" style="font-size: 16px;">@medicalpro</span><i class="bi bi-patch-check-fill mx-1" style="color: ${socialColors.tt.blue}; font-size: 14px;"></i></div>
                        <div class="mb-2" style="font-size: 14px; color: white; direction: ${direction}; text-align: ${textAlign}; max-height: 80px; overflow-y: auto;">${ttText.replace(/\n/g, '<br>')}</div>
                        ${preview.hashtags && preview.hashtags.length > 0 ? `<div class="mb-2" style="font-size: 14px;">${preview.hashtags.slice(0, 5).map(tag => `<a href="#" class="text-decoration-none me-1 fw-semibold" style="color: white;">${tag}</a>`).join('')}</div>` : ''}
                        <div class="d-flex align-items-center"><i class="bi bi-music-note-beamed text-white ${isRtl ? 'ms-2' : 'me-2'}"></i><div class="text-white" style="font-size: 13px; white-space: nowrap; overflow: hidden;"><marquee>${t.ttOriginalSound}</marquee></div></div>
                    </div>
                </div>
            </div>`;
    }
    
    // Add statistics and actions
    html += `
        <div class="mt-4">
            <div class="alert alert-info d-flex align-items-center justify-content-between">
                <div>
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>${t.characterCount}:</strong> ${preview.current_length} / ${preview.max_length}
                    ${preview.current_length > preview.recommended_length ? `<span class="badge bg-warning ms-2">${t.aboveRecommended}</span>` : `<span class="badge bg-success ms-2">${t.optimalLength}</span>`}
                </div>
            </div>
            ${preview.thread_suggestion && preview.thread_suggestion.length > 0 ? `
                <div class="alert alert-primary">
                    <strong><i class="bi bi-chat-square-text me-2"></i>${t.threadSuggestion}:</strong>
                    <small class="text-muted">(${t.contentTooLong})</small>
                    <div class="list-group list-group-flush mt-2">
                        ${preview.thread_suggestion.map((tweet, i) => `<div class="list-group-item bg-transparent border-start border-primary border-3 ps-3"><div class="d-flex align-items-center mb-1"><span class="badge bg-primary me-2">${i + 1}</span><small class="text-muted">${tweet.length} ${t.chars}</small></div><div>${tweet}</div></div>`).join('')}
                    </div>
                </div>
            ` : ''}
            ${preview.best_practices ? `
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="card-title"><i class="bi bi-lightbulb text-warning me-2"></i>${t.bestPractices}</h6>
                        <ul class="mb-0">${preview.best_practices.map(tip => `<li class="mb-1">${tip}</li>`).join('')}</ul>
                    </div>
                </div>
            ` : ''}
            <div class="text-center mt-4">
                <button class="btn btn-lg btn-primary shadow" onclick="copySocialPreview('${preview.platform}')">
                    <i class="bi bi-clipboard-check me-2"></i>${t.copyContent} ${preview.platform.charAt(0).toUpperCase() + preview.platform.slice(1)}
                </button>
            </div>
        </div>
    `;
    
    content.innerHTML = html;
}

function copySocialPreview(platform) {
    const preview = currentPreviewData[platform];
    if (!preview) return;
    
    let textToCopy = preview.text;
    if (preview.hashtags && preview.hashtags.length > 0) {
        textToCopy += '\n\n' + preview.hashtags.join(' ');
    }
    
    navigator.clipboard.writeText(textToCopy).then(function() {
        SwalHelper.toast('{{ __("translation.content_generator.copied_success") }}');
    }).catch(function() {
        SwalHelper.error('{{ __("translation.content_generator.error") }}', '{{ __("translation.content_generator.copy_failed") }}');
    });
}

function toggleFbText(el) {
    const hidden = document.getElementById('fb-hidden-text');
    if (hidden.style.display === 'none') {
        hidden.style.display = 'inline';
        el.textContent = socialMediaTranslations.seeLess;
    } else {
        hidden.style.display = 'none';
        el.textContent = socialMediaTranslations.seeMore;
    }
}

function toggleLiText(el) {
    const hidden = document.getElementById('li-hidden-text');
    if (hidden.style.display === 'none') {
        hidden.style.display = 'inline';
        el.textContent = socialMediaTranslations.liSeeLess;
    } else {
        hidden.style.display = 'none';
        el.textContent = socialMediaTranslations.liSeeMore;
    }
}

function toggleIgText(el) {
    const hidden = document.getElementById('ig-hidden-text');
    if (hidden.style.display === 'none') {
        hidden.style.display = 'inline';
        el.style.display = 'none';
    }
}

function toggleIgTags(el) {
    const moreTags = document.getElementById('ig-more-tags');
    if (moreTags.style.display === 'none') {
        moreTags.style.display = 'inline';
        el.style.display = 'none';
    }
}
</script>
