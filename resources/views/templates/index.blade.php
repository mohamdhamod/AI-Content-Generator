@extends('layout.home.main')

@section('title', __('translation.content_generator.templates.title'))

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('content.index') }}">{{ __('translation.content_generator.templates.breadcrumb_content_generator') }}</a></li>
            <li class="breadcrumb-item active">{{ __('translation.content_generator.templates.breadcrumb_templates') }}</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="bi bi-collection text-primary me-2"></i>{{ __('translation.content_generator.templates.title') }}
            </h2>
            <p class="text-muted mb-0">{{ __('translation.content_generator.templates.subtitle') }}</p>
        </div>
        <a href="{{ route('content.index') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>{{ __('translation.content_generator.templates.create_content') }}
        </a>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="searchTemplates" placeholder="{{ __('translation.content_generator.templates.search_placeholder') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterCategory">
                        <option value="">{{ __('translation.content_generator.templates.all_categories') }}</option>
                        <option value="general">{{ __('translation.content_generator.templates.category_general') }}</option>
                        <option value="patient_education">{{ __('translation.content_generator.templates.category_patient_education') }}</option>
                        <option value="clinical_documentation">{{ __('translation.content_generator.templates.category_clinical_docs') }}</option>
                        <option value="research">{{ __('translation.content_generator.templates.category_research') }}</option>
                        <option value="marketing">{{ __('translation.content_generator.templates.category_marketing') }}</option>
                        <option value="social_media">{{ __('translation.content_generator.templates.category_social_media') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterVisibility">
                        <option value="">{{ __('translation.content_generator.templates.all_visibility') }}</option>
                        <option value="private">{{ __('translation.content_generator.templates.visibility_private') }}</option>
                        <option value="team">{{ __('translation.content_generator.templates.visibility_team') }}</option>
                        <option value="public">{{ __('translation.content_generator.templates.visibility_public') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary w-100" onclick="loadTemplates()">
                        <i class="bi bi-arrow-repeat me-1"></i>{{ __('translation.content_generator.templates.refresh') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Templates Grid -->
    <div id="templatesContainer">
        <div class="text-center py-5" id="loadingTemplates">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">{{ __('translation.content_generator.templates.loading') }}</span>
            </div>
            <p class="text-muted mt-2">{{ __('translation.content_generator.templates.loading_templates') }}</p>
        </div>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="text-center py-5" style="display: none;">
        <i class="bi bi-collection text-muted" style="font-size: 4rem;"></i>
        <h4 class="mt-3">{{ __('translation.content_generator.templates.no_templates_yet') }}</h4>
        <p class="text-muted">{{ __('translation.content_generator.templates.no_templates_message') }}</p>
        <a href="{{ route('content.index') }}" class="btn btn-primary mt-2">
            <i class="bi bi-plus-circle me-2"></i>{{ __('translation.content_generator.templates.generate_content') }}
        </a>
    </div>
</div>

<!-- Template Preview Modal -->
<div class="modal fade" id="templatePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalTitle">
                    <i class="bi bi-file-earmark-text me-2"></i>{{ __('translation.content_generator.templates.template_preview') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="templatePreviewContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.content_generator.templates.close') }}</button>
                <button type="button" class="btn btn-danger" id="deleteTemplateBtn" onclick="deleteTemplate()">
                    <i class="bi bi-trash me-1"></i>{{ __('translation.content_generator.templates.delete') }}
                </button>
                <button type="button" class="btn btn-primary" id="useTemplateBtn" onclick="useTemplate()">
                    <i class="bi bi-play-circle me-1"></i>{{ __('translation.content_generator.templates.use_template') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentTemplateId = null;
let templatesData = [];

document.addEventListener('DOMContentLoaded', function() {
    loadTemplates();
    
    // Search on input
    document.getElementById('searchTemplates').addEventListener('input', debounce(filterTemplates, 300));
    document.getElementById('filterCategory').addEventListener('change', filterTemplates);
    document.getElementById('filterVisibility').addEventListener('change', filterTemplates);
});

function loadTemplates() {
    document.getElementById('loadingTemplates').style.display = 'block';
    document.getElementById('emptyState').style.display = 'none';
    
    fetch("{{ route('templates.index') }}", {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => {
        console.log('Response status:', r.status);
        return r.json();
    })
    .then(data => {
        console.log('Templates response:', data);
        document.getElementById('loadingTemplates').style.display = 'none';
        
        if (data.success && data.data?.length > 0) {
            templatesData = data.data;
            renderTemplates(templatesData);
        } else {
            console.log('No templates found or success=false');
            document.getElementById('emptyState').style.display = 'block';
            document.getElementById('templatesContainer').innerHTML = '';
        }
    })
    .catch(error => {
        console.error('Error loading templates:', error);
        document.getElementById('loadingTemplates').style.display = 'none';
        document.getElementById('templatesContainer').innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ __('translation.content_generator.templates.failed_to_load') }}
            </div>
        `;
    });
}

function renderTemplates(templates) {
    if (templates.length === 0) {
        document.getElementById('emptyState').style.display = 'block';
        document.getElementById('templatesContainer').innerHTML = '';
        return;
    }
    
    document.getElementById('emptyState').style.display = 'none';
    
    const categoryLabels = {
        'general': '{{ __('translation.content_generator.templates.category_general') }}',
        'patient_education': '{{ __('translation.content_generator.templates.category_patient_education') }}',
        'clinical_documentation': '{{ __('translation.content_generator.templates.category_clinical_docs') }}',
        'research': '{{ __('translation.content_generator.templates.category_research') }}',
        'marketing': '{{ __('translation.content_generator.templates.category_marketing') }}',
        'social_media': '{{ __('translation.content_generator.templates.category_social_media') }}'
    };
    
    const visibilityIcons = {
        'private': '<i class="bi bi-lock text-secondary"></i>',
        'team': '<i class="bi bi-people text-info"></i>',
        'public': '<i class="bi bi-globe text-success"></i>'
    };
    
    let html = '<div class="row g-4">';
    templates.forEach(t => {
        const category = t.metadata?.category || 'general';
        html += `
            <div class="col-md-6 col-lg-4">
                <div class="card template-card h-100" onclick="previewTemplate(${t.id})">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="template-category category-${category}">${categoryLabels[category] || category}</span>
                            <span title="${t.visibility}">${visibilityIcons[t.visibility] || ''}</span>
                        </div>
                        <h5 class="card-title mb-2">${t.name}</h5>
                        <p class="template-preview">${t.description || '{{ __('translation.content_generator.templates.no_description') }}'}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>${t.created_at}
                            </small>
                            <small class="text-muted">
                                <i class="bi bi-arrow-repeat me-1"></i>${t.usage_count || 0} {{ __('translation.content_generator.templates.uses') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';
    
    document.getElementById('templatesContainer').innerHTML = html;
}

function filterTemplates() {
    const search = document.getElementById('searchTemplates').value.toLowerCase();
    const category = document.getElementById('filterCategory').value;
    const visibility = document.getElementById('filterVisibility').value;
    
    let filtered = templatesData.filter(t => {
        const matchSearch = !search || 
            t.name.toLowerCase().includes(search) || 
            (t.description && t.description.toLowerCase().includes(search));
        const matchCategory = !category || (t.metadata?.category === category);
        const matchVisibility = !visibility || t.visibility === visibility;
        
        return matchSearch && matchCategory && matchVisibility;
    });
    
    renderTemplates(filtered);
}

function previewTemplate(id) {
    currentTemplateId = id;
    
    fetch(`{{ url('/' . app()->getLocale() . '/templates') }}/${id}`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.data) {
            const t = data.data;
            document.getElementById('previewModalTitle').innerHTML = `
                <i class="bi bi-file-earmark-text me-2"></i>${t.name}
            `;
            document.getElementById('templatePreviewContent').innerHTML = `
                <div class="mb-4">
                    <strong>{{ __('translation.content_generator.templates.description') }}:</strong>
                    <p class="text-muted">${t.description || '{{ __('translation.content_generator.templates.no_description') }}'}</p>
                </div>
                ${t.variables?.length > 0 ? `
                    <div class="mb-4">
                        <strong>{{ __('translation.content_generator.templates.variables') }}:</strong>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            ${t.variables.map(v => `<span class="badge bg-primary">${v.name}</span>`).join('')}
                        </div>
                    </div>
                ` : ''}
                <div class="mb-3">
                    <strong>{{ __('translation.content_generator.templates.template_content') }}:</strong>
                    <div class="border rounded p-3 bg-light mt-2" style="max-height: 300px; overflow-y: auto;">
                        <pre class="mb-0" style="white-space: pre-wrap;">${t.template_content || '{{ __('translation.content_generator.templates.no_content') }}'}</pre>
                    </div>
                </div>
            `;
            
            new bootstrap.Modal(document.getElementById('templatePreviewModal')).show();
        }
    })
    .catch(console.error);
}

function useTemplate() {
    if (!currentTemplateId) return;
    window.location.href = `{{ route('content.index') }}?template_id=${currentTemplateId}`;
}

function deleteTemplate() {
    if (!currentTemplateId) return;
    
    Swal.fire({
        title: '{{ __('translation.content_generator.templates.delete_template_title') }}',
        text: '{{ __('translation.content_generator.templates.delete_template_text') }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: '{{ __('translation.content_generator.templates.yes_delete') }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ url('/' . app()->getLocale() . '/templates') }}/${currentTemplateId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('templatePreviewModal')).hide();
                    Swal.fire({ icon: 'success', title: '{{ __('translation.content_generator.templates.deleted') }}', timer: 1500, showConfirmButton: false });
                    loadTemplates();
                } else {
                    Swal.fire({ icon: 'error', title: '{{ __('translation.content_generator.templates.error') }}', text: data.message });
                }
            });
        }
    });
}

function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}
</script>
@endsection
