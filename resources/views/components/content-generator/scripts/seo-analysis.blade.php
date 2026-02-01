{{-- SEO Analysis Script --}}
@props(['content'])

<script>
/**
 * Analyze SEO for the content
 */
async function analyzeSeo() {
    const form = document.getElementById('seoAnalysisForm');
    if (!form) return;
    
    const focusKeyword = document.getElementById('seoFocusKeyword')?.value || '';
    const targetAudience = document.getElementById('seoTargetAudience')?.value || '';
    
    try {
        const data = await ApiClient.post("{{ route('content.seo.analyze', ['id' => $content->id]) }}", {
            focus_keyword: focusKeyword,
            target_audience: targetAudience
        });
        
        if (data.success && data.data) {
            displaySeoResults(data.data);
        } else {
            SwalHelper.error('{{ __("translation.content_generator.error") }}', data.message || '{{ __("translation.content_generator.seo_analysis_failed") }}');
        }
    } catch (error) {
        console.error('Error:', error);
        SwalHelper.error('{{ __("translation.content_generator.error") }}', '{{ __("translation.content_generator.network_error") }}');
    }
}

/**
 * Display SEO analysis results
 */
function displaySeoResults(data) {
    const resultsContainer = document.getElementById('seoResults');
    const formContainer = document.getElementById('seoAnalysisForm');
    const btnAnalyze = document.getElementById('btnAnalyzeSeo');
    const btnAnalyzeAgain = document.getElementById('btnAnalyzeAgain');
    
    if (formContainer) formContainer.style.display = 'none';
    if (btnAnalyze) btnAnalyze.style.display = 'none';
    if (btnAnalyzeAgain) btnAnalyzeAgain.style.display = 'inline-block';
    
    if (resultsContainer) {
        resultsContainer.style.display = 'block';
        
        // Update overall score
        const score = data.overall_score || data.score || 0;
        const scoreElement = document.getElementById('seoOverallScore');
        if (scoreElement) {
            scoreElement.textContent = score;
        }
        
        // Update grade badge
        const gradeElement = document.getElementById('seoGrade');
        if (gradeElement) {
            const grade = score >= 90 ? 'A+' : score >= 80 ? 'A' : score >= 70 ? 'B' : score >= 60 ? 'C' : score >= 50 ? 'D' : 'F';
            const gradeClass = score >= 80 ? 'bg-success' : score >= 60 ? 'bg-warning' : 'bg-danger';
            gradeElement.textContent = grade;
            gradeElement.className = 'badge ' + gradeClass + ' fs-5';
        }
        
        // Update progress bar
        const progressBar = document.getElementById('seoProgressBar');
        if (progressBar) {
            progressBar.style.width = score + '%';
            progressBar.className = 'progress-bar bg-' + (score >= 80 ? 'success' : (score >= 60 ? 'warning' : 'danger'));
            progressBar.setAttribute('aria-valuenow', score);
        }
        
        // Update detailed scores
        const detailedContainer = document.getElementById('seoDetailedScores');
        if (detailedContainer && data.scores) {
            detailedContainer.innerHTML = Object.entries(data.scores).map(([key, val]) => {
                // Handle both direct values and object values with score property
                const value = typeof val === 'object' ? (val.score || val.value || 0) : val;
                const numValue = parseInt(value) || 0;
                return `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-capitalize">${Utils.escapeHtml(key.replace(/_/g, ' '))}</span>
                    <div class="d-flex align-items-center">
                        <div class="progress me-2" style="width: 100px; height: 8px;">
                            <div class="progress-bar bg-${numValue >= 80 ? 'success' : numValue >= 60 ? 'warning' : 'danger'}" style="width: ${numValue}%"></div>
                        </div>
                        <span class="badge bg-${numValue >= 80 ? 'success' : numValue >= 60 ? 'warning' : 'danger'}">${numValue}%</span>
                    </div>
                </div>
            `}).join('');
        }
        
        // Update recommendations
        const recommendationsContainer = document.getElementById('seoRecommendations');
        if (recommendationsContainer && (data.recommendations || data.suggestions)) {
            const items = data.recommendations || data.suggestions || [];
            recommendationsContainer.innerHTML = items.map(item => {
                const type = item.type || (item.status === 'pass' ? 'success' : item.status === 'warning' ? 'warning' : 'info');
                const icon = type === 'success' ? 'check-circle-fill' : type === 'warning' ? 'exclamation-triangle-fill' : 'info-circle-fill';
                return `
                    <div class="alert alert-${type} py-2 mb-2 d-flex align-items-start">
                        <i class="bi bi-${icon} me-2 mt-1"></i>
                        <span>${Utils.escapeHtml(item.message || item.text || item)}</span>
                    </div>
                `;
            }).join('');
        }
    }
}

/**
 * Reset SEO analysis form
 */
function resetSeoAnalysis() {
    const resultsContainer = document.getElementById('seoResults');
    const formContainer = document.getElementById('seoAnalysisForm');
    const btnAnalyze = document.getElementById('btnAnalyzeSeo');
    const btnAnalyzeAgain = document.getElementById('btnAnalyzeAgain');
    
    if (resultsContainer) resultsContainer.style.display = 'none';
    if (formContainer) formContainer.style.display = 'block';
    if (btnAnalyze) btnAnalyze.style.display = 'inline-block';
    if (btnAnalyzeAgain) btnAnalyzeAgain.style.display = 'none';
}

// Initialize SEO modal
document.getElementById('seoAnalysisModal')?.addEventListener('shown.bs.modal', function() {
    resetSeoAnalysis();
});
</script>
