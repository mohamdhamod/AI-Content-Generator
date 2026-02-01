{{-- Analytics Script --}}
@props(['content'])

<script>
/**
 * Load content analytics when modal opens
 */
document.getElementById('analyticsModal')?.addEventListener('shown.bs.modal', function() {
    loadContentAnalytics();
});

/**
 * Load and display content analytics
 */
async function loadContentAnalytics() {
    const loading = document.getElementById('analyticsLoading');
    const content = document.getElementById('analyticsContent');
    
    if (loading) loading.style.display = 'block';
    if (content) content.style.display = 'none';
    
    try {
        const data = await ApiClient.get("{{ route('analytics.content', ['id' => $content->id]) }}", { showLoading: false });
        
        if (loading) loading.style.display = 'none';
        if (content) content.style.display = 'block';
        
        if (data.success && data.data) {
            const analytics = data.data;
            
            // Update view count
            const viewsEl = document.getElementById('analyticsViews');
            if (viewsEl) viewsEl.textContent = analytics.views || 0;
            
            // Update read time
            const readTimeEl = document.getElementById('analyticsReadTime');
            if (readTimeEl) readTimeEl.textContent = (analytics.avg_read_time || 0) + 'm';
            
            // Update engagement rate
            const engagementEl = document.getElementById('analyticsEngagement');
            if (engagementEl) engagementEl.textContent = (analytics.engagement_rate || 0) + '%';
            
            // Update shares count
            const sharesEl = document.getElementById('analyticsShares');
            if (sharesEl) sharesEl.textContent = analytics.shares || 0;
            
            // Update chart if exists
            if (analytics.chart_data && typeof renderAnalyticsChart === 'function') {
                renderAnalyticsChart(analytics.chart_data);
            }
            
            // Update detailed stats if available
            if (analytics.detailed) {
                updateDetailedStats(analytics.detailed);
            }
        }
    } catch (error) {
        console.error('Error:', error);
        if (loading) loading.style.display = 'none';
        if (content) content.style.display = 'block';
    }
}

/**
 * Update detailed statistics section
 */
function updateDetailedStats(detailed) {
    // Word count
    const wordCountEl = document.getElementById('statsWordCount');
    if (wordCountEl && detailed.word_count !== undefined) {
        wordCountEl.textContent = detailed.word_count;
    }
    
    // Character count
    const charCountEl = document.getElementById('statsCharCount');
    if (charCountEl && detailed.char_count !== undefined) {
        charCountEl.textContent = detailed.char_count;
    }
    
    // Reading time
    const readTimeEl = document.getElementById('statsReadTime');
    if (readTimeEl && detailed.reading_time !== undefined) {
        readTimeEl.textContent = detailed.reading_time + ' min';
    }
}

/**
 * Render analytics chart (if Chart.js is available)
 */
function renderAnalyticsChart(chartData) {
    const canvas = document.getElementById('analyticsChart');
    if (!canvas || typeof Chart === 'undefined') return;
    
    const ctx = canvas.getContext('2d');
    
    // Destroy existing chart if any
    if (window.analyticsChartInstance) {
        window.analyticsChartInstance.destroy();
    }
    
    window.analyticsChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels || [],
            datasets: [{
                label: '{{ __("translation.content_generator.views") }}',
                data: chartData.views || [],
                borderColor: Utils.colors.blue,
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

/**
 * Export analytics as PDF
 */
async function exportAnalyticsPdf() {
    try {
        const blob = await ApiClient.downloadFile("{{ route('analytics.export') }}", {
            content_id: {{ $content->id }},
            format: 'pdf'
        });
        
        Utils.downloadBlob(blob, 'analytics-{{ $content->id }}.pdf');
    } catch (error) {
        console.error('Error:', error);
        SwalHelper.error('{{ __("translation.content_generator.error") }}', '{{ __("translation.content_generator.export_failed") }}');
    }
}

/**
 * Export analytics as CSV
 */
async function exportAnalyticsCsv() {
    try {
        const blob = await ApiClient.downloadFile("{{ route('analytics.export') }}", {
            content_id: {{ $content->id }},
            format: 'csv'
        });
        
        Utils.downloadBlob(blob, 'analytics-{{ $content->id }}.csv');
    } catch (error) {
        console.error('Error:', error);
    }
}
</script>
