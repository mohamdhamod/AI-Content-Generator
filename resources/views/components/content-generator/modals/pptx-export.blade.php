{{-- PowerPoint Export Modal Component --}}
@props(['content', 'pptxStyles' => [], 'pptxThemes' => [], 'pptxDetails' => [], 'currentLocale' => 'en'])

<div class="modal fade" id="pptxThemeModal" tabindex="-1" aria-labelledby="pptxThemeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="pptxThemeModalLabel">
                    <i class="bi bi-file-earmark-ppt me-2"></i>{{ __('translation.content_generator.pptx.modal_title') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Progress Steps -->
                <div class="d-flex justify-content-center mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary rounded-pill pptx-step-badge active" data-step="1">1</span>
                        <small class="text-muted d-none d-sm-inline">{{ __('translation.content_generator.pptx.step_style') }}</small>
                        <i class="bi bi-chevron-right text-muted mx-2"></i>
                        <span class="badge bg-secondary rounded-pill pptx-step-badge" data-step="2">2</span>
                        <small class="text-muted d-none d-sm-inline">{{ __('translation.content_generator.pptx.step_theme') }}</small>
                        <i class="bi bi-chevron-right text-muted mx-2"></i>
                        <span class="badge bg-secondary rounded-pill pptx-step-badge" data-step="3">3</span>
                        <small class="text-muted d-none d-sm-inline">{{ __('translation.content_generator.pptx.step_export') }}</small>
                    </div>
                </div>
                
                <!-- Hidden Inputs -->
                <input type="hidden" id="selectedPptxTheme" value="professional_blue">
                <input type="hidden" id="selectedPptxStyle" value="educational">
                <input type="hidden" id="selectedDetailLevel" value="standard">
                
                <!-- Step 1: Presentation Style -->
                <div id="pptxStep1">
                    <h6 class="fw-bold mb-3">{{ __('translation.content_generator.pptx.choose_style') }}</h6>
                    
                    <div class="row g-2 mb-4">
                        @php $firstStyle = true; @endphp
                        @foreach($pptxStyles as $styleKey => $style)
                            <div class="col-md-4 col-6">
                                <div class="card h-100 {{ $firstStyle ? 'border-primary' : '' }} pptx-style-card" data-style="{{ $styleKey }}" onclick="selectPptxStyle('{{ $styleKey }}')">
                                    <div class="card-body text-center p-3">
                                        <div class="fs-2 mb-2">{{ $style['icon'] }}</div>
                                        <h6 class="card-title mb-1">{{ $style['name'][$currentLocale] ?? $style['name']['en'] }}</h6>
                                        <small class="text-muted">{{ $style['description'][$currentLocale] ?? $style['description']['en'] }}</small>
                                        <i class="bi bi-check-circle-fill text-primary position-absolute top-0 end-0 m-2 pptx-check-icon {{ !$firstStyle ? 'd-none' : '' }}"></i>
                                    </div>
                                </div>
                            </div>
                            @php $firstStyle = false; @endphp
                        @endforeach
                    </div>
                    
                    <!-- Detail Level -->
                    <h6 class="fw-bold mb-3">{{ __('translation.content_generator.pptx.detail_level') }}</h6>
                    <div class="btn-group w-100" role="group">
                        @php 
                            $detailIcons = ['brief' => 'bi-lightning-charge', 'standard' => 'bi-check2-circle', 'detailed' => 'bi-list-check', 'comprehensive' => 'bi-book'];
                            $detailSlides = ['brief' => '5-10', 'standard' => '10-15', 'detailed' => '15-25', 'comprehensive' => '25+'];
                        @endphp
                        @foreach($pptxDetails as $detailKey => $detail)
                            <input type="radio" class="btn-check" name="detailLevel" id="level{{ ucfirst($detailKey) }}" value="{{ $detailKey }}" {{ $detailKey === 'standard' ? 'checked' : '' }} onclick="selectDetailLevel('{{ $detailKey }}')">
                            <label class="btn btn-outline-primary" for="level{{ ucfirst($detailKey) }}">
                                <i class="bi {{ $detailIcons[$detailKey] ?? 'bi-circle' }}"></i><br>
                                <small>{{ $detail['name'][$currentLocale] ?? $detail['name']['en'] }}<br>{{ $detailSlides[$detailKey] ?? '' }} {{ __('translation.content_generator.pptx.slides') }}</small>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <!-- Step 2: Theme Selection -->
                <div id="pptxStep2" class="d-none">
                    <h6 class="fw-bold mb-3">{{ __('translation.content_generator.pptx.choose_theme') }}</h6>
                    
                    <div class="row g-2">
                        @php
                            $themeGradients = [
                                'professional_blue' => 'linear-gradient(135deg,#1D4ED8,#3B82F6)',
                                'medical_green' => 'linear-gradient(135deg,#047857,#10B981)',
                                'academic_purple' => 'linear-gradient(135deg,#6D28D9,#8B5CF6)',
                                'modern_dark' => 'linear-gradient(135deg,#18181B,#27272A)',
                                'clean_minimal' => 'linear-gradient(135deg,#FAFAFA,#F4F4F5)',
                                'healthcare_teal' => 'linear-gradient(135deg,#0F766E,#14B8A6)',
                                'gradient_sunset' => 'linear-gradient(135deg,#DC2626,#F97316)',
                                'scientific_navy' => 'linear-gradient(135deg,#1E3A8A,#3B82F6)',
                            ];
                            $firstTheme = true;
                        @endphp
                        @foreach($pptxThemes as $themeKey => $theme)
                            <div class="col-lg-3 col-md-4 col-6">
                                <div class="card h-100 {{ $firstTheme ? 'border-primary' : '' }} pptx-theme-card" data-theme="{{ $themeKey }}" onclick="selectPptxTheme('{{ $themeKey }}')">
                                    <div class="card-body p-2 text-center">
                                        <div class="rounded mb-2 {{ $themeKey === 'clean_minimal' ? 'border' : '' }}" style="height:60px;background:{{ $themeGradients[$themeKey] ?? 'linear-gradient(135deg,#2563EB,#3B82F6)' }};"></div>
                                        <small class="fw-semibold">{{ $theme['name'] }}</small>
                                        <i class="bi bi-check-circle-fill text-primary position-absolute top-0 end-0 m-1 pptx-check-icon {{ !$firstTheme ? 'd-none' : '' }}"></i>
                                    </div>
                                </div>
                            </div>
                            @php $firstTheme = false; @endphp
                        @endforeach
                    </div>
                </div>
                
                <!-- Step 3: Export Preview -->
                <div id="pptxStep3" class="d-none">
                    <h6 class="fw-bold mb-3">{{ __('translation.content_generator.pptx.preview_title') }}</h6>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="rounded p-3 text-white text-center" id="slidePreview" style="background:linear-gradient(135deg,#1D4ED8,#3B82F6);min-height:150px;">
                                        <div class="fs-4 mb-1" id="previewStyleIcon">🎓</div>
                                        <h6 id="previewTitle" class="mb-1">{{ Str::limit($content->title ?? 'Presentation', 35) }}</h6>
                                        <small id="previewSpecialty" class="opacity-75">{{ $content->specialty?->name ?? '' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex align-items-center">
                                    <i class="bi bi-file-earmark-ppt text-danger me-3 fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block">{{ __('translation.content_generator.pptx.file_name') }}</small>
                                        <span class="fw-semibold text-truncate d-block" id="exportFileName2" style="max-width:200px;">{{ Str::limit($content->title ?? 'Presentation', 25) }}.pptx</span>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <span class="me-3 fs-4" id="styleInfoIcon">🎓</span>
                                    <div>
                                        <small class="text-muted d-block">{{ __('translation.content_generator.pptx.presentation_style') }}</small>
                                        <span class="fw-semibold" id="exportStyleName">{{ __('translation.content_generator.pptx.style_educational') }}</span>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <i class="bi bi-palette2 text-primary me-3 fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block">{{ __('translation.content_generator.pptx.selected_theme') }}</small>
                                        <span class="fw-semibold" id="exportThemeName">{{ __('translation.content_generator.pptx.theme_professional_blue') }}</span>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <i class="bi bi-layers text-success me-3 fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block">{{ __('translation.content_generator.pptx.detail_level') }}</small>
                                        <span class="fw-semibold" id="exportDetailName">{{ __('translation.content_generator.pptx.detail_standard') }} (10-15 {{ __('translation.content_generator.pptx.slides') }})</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-success-subtle text-success me-1"><i class="bi bi-check2 me-1"></i>{{ __('translation.content_generator.pptx.feature_professional') }}</span>
                                <span class="badge bg-success-subtle text-success me-1"><i class="bi bi-check2 me-1"></i>{{ __('translation.content_generator.pptx.feature_editable') }}</span>
                                <span class="badge bg-success-subtle text-success"><i class="bi bi-check2 me-1"></i>{{ __('translation.content_generator.pptx.feature_office') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="pptxBackBtn" onclick="pptxPrevStep()" style="display:none;">
                    <i class="bi bi-arrow-left me-1"></i>{{ __('translation.content_generator.pptx.btn_back') }}
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>{{ __('translation.content_generator.pptx.btn_cancel') }}
                </button>
                <button type="button" class="btn btn-primary" id="pptxNextBtn" onclick="pptxNextStep()">
                    {{ __('translation.content_generator.pptx.btn_next') }}<i class="bi bi-arrow-right ms-1"></i>
                </button>
                <button type="button" class="btn btn-success d-none" id="exportPptxBtn" onclick="exportPowerPoint()">
                    <i class="bi bi-download me-2"></i>{{ __('translation.content_generator.pptx.btn_export') }}
                </button>
            </div>
        </div>
    </div>
</div>
