{{-- PowerPoint Export Script --}}
@props(['content', 'pptxStyles', 'pptxThemes', 'pptxDetails', 'currentLocale'])

<script>
// PowerPoint Export Wizard
let currentPptxStep = 1;
const totalPptxSteps = 3;

// Theme gradients for preview
const themeGradients = {
    'professional_blue': 'linear-gradient(135deg, #1D4ED8, #3B82F6)',
    'medical_green': 'linear-gradient(135deg, #047857, #10B981)',
    'academic_purple': 'linear-gradient(135deg, #6D28D9, #8B5CF6)',
    'modern_dark': 'linear-gradient(135deg, #18181B, #27272A)',
    'clean_minimal': 'linear-gradient(135deg, #FAFAFA, #F4F4F5)',
    'healthcare_teal': 'linear-gradient(135deg, #0F766E, #14B8A6)',
    'gradient_sunset': 'linear-gradient(135deg, #DC2626, #F97316)',
    'scientific_navy': 'linear-gradient(135deg, #1E3A8A, #3B82F6)'
};

// Style icons - dynamically from PHP
const styleIcons = @json(collect($pptxStyles)->mapWithKeys(fn($s, $k) => [$k => $s['icon']]));

// Detail level slide counts
const detailSlideCounts = {
    'brief': '5-10',
    'standard': '10-15',
    'detailed': '15-25',
    'comprehensive': '25+'
};

function pptxNextStep() {
    if (currentPptxStep < totalPptxSteps) {
        document.getElementById('pptxStep' + currentPptxStep).classList.add('d-none');
        currentPptxStep++;
        document.getElementById('pptxStep' + currentPptxStep).classList.remove('d-none');
        updatePptxNavigation();
        updatePptxStepBadges();
        
        if (currentPptxStep === 3) {
            updateExportPreview();
        }
    }
}

function pptxPrevStep() {
    if (currentPptxStep > 1) {
        document.getElementById('pptxStep' + currentPptxStep).classList.add('d-none');
        currentPptxStep--;
        document.getElementById('pptxStep' + currentPptxStep).classList.remove('d-none');
        updatePptxNavigation();
        updatePptxStepBadges();
    }
}

function updatePptxNavigation() {
    const backBtn = document.getElementById('pptxBackBtn');
    const nextBtn = document.getElementById('pptxNextBtn');
    const exportBtn = document.getElementById('exportPptxBtn');
    
    if (backBtn) backBtn.style.display = currentPptxStep > 1 ? 'inline-block' : 'none';
    
    if (currentPptxStep === totalPptxSteps) {
        if (nextBtn) nextBtn.classList.add('d-none');
        if (exportBtn) exportBtn.classList.remove('d-none');
    } else {
        if (nextBtn) nextBtn.classList.remove('d-none');
        if (exportBtn) exportBtn.classList.add('d-none');
    }
}

function updatePptxStepBadges() {
    document.querySelectorAll('.pptx-step-badge').forEach((badge, index) => {
        const stepNum = index + 1;
        badge.classList.remove('bg-primary', 'bg-secondary', 'bg-success');
        if (stepNum < currentPptxStep) {
            badge.classList.add('bg-success');
        } else if (stepNum === currentPptxStep) {
            badge.classList.add('bg-primary');
        } else {
            badge.classList.add('bg-secondary');
        }
    });
}

function selectPptxStyle(style) {
    // Update cards
    document.querySelectorAll('.pptx-style-card').forEach(card => {
        card.classList.remove('border-primary');
        card.querySelector('.pptx-check-icon')?.classList.add('d-none');
    });
    const selectedCard = document.querySelector(`.pptx-style-card[data-style="${style}"]`);
    if (selectedCard) {
        selectedCard.classList.add('border-primary');
        selectedCard.querySelector('.pptx-check-icon')?.classList.remove('d-none');
    }
    document.getElementById('selectedPptxStyle').value = style;
}

function selectDetailLevel(level) {
    document.getElementById('selectedDetailLevel').value = level;
}

function selectPptxTheme(themeKey) {
    // Update cards
    document.querySelectorAll('.pptx-theme-card').forEach(card => {
        card.classList.remove('border-primary');
        card.querySelector('.pptx-check-icon')?.classList.add('d-none');
    });
    const selectedCard = document.querySelector(`.pptx-theme-card[data-theme="${themeKey}"]`);
    if (selectedCard) {
        selectedCard.classList.add('border-primary');
        selectedCard.querySelector('.pptx-check-icon')?.classList.remove('d-none');
    }
    document.getElementById('selectedPptxTheme').value = themeKey;
}

function updateExportPreview() {
    const styles = @json(collect($pptxStyles)->mapWithKeys(fn($s, $k) => [$k => $s['name'][$currentLocale] ?? $s['name']['en']]));
    
    const themes = @json(collect($pptxThemes)->mapWithKeys(fn($t, $k) => [$k => $t['name']]));
    
    const details = @json(collect($pptxDetails)->mapWithKeys(fn($d, $k) => [$k => $d['name'][$currentLocale] ?? $d['name']['en']]));
    
    const style = document.getElementById('selectedPptxStyle')?.value;
    const theme = document.getElementById('selectedPptxTheme')?.value;
    const detail = document.getElementById('selectedDetailLevel')?.value;
    
    // Update slide preview
    const slidePreview = document.getElementById('slidePreview');
    if (slidePreview) {
        slidePreview.style.background = themeGradients[theme] || themeGradients['professional_blue'];
        
        // Adjust text color for light themes
        if (theme === 'clean_minimal') {
            slidePreview.querySelectorAll('h6, small').forEach(el => {
                el.style.color = Utils.colors.bodyColor;
            });
        } else {
            slidePreview.querySelectorAll('h6').forEach(el => el.style.color = 'white');
            slidePreview.querySelectorAll('small').forEach(el => el.style.color = 'rgba(255,255,255,0.8)');
        }
    }
    
    // Update style icon in preview
    const previewStyleIcon = document.getElementById('previewStyleIcon');
    if (previewStyleIcon) {
        previewStyleIcon.textContent = styleIcons[style] || '🎓';
    }
    
    // Update info panel
    const styleName = document.getElementById('exportStyleName');
    const themeName = document.getElementById('exportThemeName');
    const detailName = document.getElementById('exportDetailName');
    const styleIcon = document.getElementById('styleInfoIcon');
    
    if (styleName) styleName.textContent = styles[style] || style;
    if (themeName) themeName.textContent = themes[theme] || theme;
    if (detailName) detailName.textContent = (details[detail] || detail) + ' (' + (detailSlideCounts[detail] || '10-15') + ' {{ __("translation.content_generator.pptx.slides") }})';
    if (styleIcon) styleIcon.textContent = styleIcons[style] || '🎓';
}

function exportPowerPoint() {
    const theme = document.getElementById('selectedPptxTheme')?.value;
    const style = document.getElementById('selectedPptxStyle')?.value;
    const detail = document.getElementById('selectedDetailLevel')?.value;
    
    const exportUrl = "{{ route('content.export.pptx', $content->id) }}?theme=" + theme + "&style=" + style + "&detail=" + detail;
    
    const btn = document.getElementById('exportPptxBtn');
    const originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __("translation.content_generator.pptx.generating") }}';
    
    // Download using iframe
    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.src = exportUrl;
    document.body.appendChild(iframe);
    
    setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = originalContent;
        ModalManager.close('pptxThemeModal');
        
        // Reset wizard
        currentPptxStep = 1;
        document.getElementById('pptxStep1')?.classList.remove('d-none');
        document.getElementById('pptxStep2')?.classList.add('d-none');
        document.getElementById('pptxStep3')?.classList.add('d-none');
        updatePptxNavigation();
        updatePptxStepBadges();
        
        SwalHelper.toast('{{ __("translation.content_generator.pptx.download_started") }}');
        
        setTimeout(() => iframe.remove(), 5000);
    }, 3000);
}

// Reset wizard when modal opens
document.getElementById('pptxThemeModal')?.addEventListener('show.bs.modal', function() {
    currentPptxStep = 1;
    document.getElementById('pptxStep1')?.classList.remove('d-none');
    document.getElementById('pptxStep2')?.classList.add('d-none');
    document.getElementById('pptxStep3')?.classList.add('d-none');
    updatePptxNavigation();
    updatePptxStepBadges();
});
</script>
