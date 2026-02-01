{{-- 
    Content Generator Show Page - Refactored Version
    This file uses components for better organization and maintainability
--}}
@extends('layout.home.main')

@section('title', __('translation.content_generator.result_title'))

@push('styles')
@include('components.content-generator.styles')
@endpush

@section('content')
<div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('content.index') }}">{{ __('translation.content_generator.title') }}</a></li>
            <li class="breadcrumb-item active">{{ __('translation.content_generator.result_title') }}</li>
        </ol>
    </nav>

    <div class="row">
        {{-- Main Content Column --}}
        <div class="col-lg-8">
            {{-- Action Center --}}
            @include('components.content-generator.action-center', ['content' => $content])

            {{-- Content Card --}}
            @include('components.content-generator.content-card', ['content' => $content])
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            @include('components.content-generator.sidebar', ['content' => $content])
        </div>
    </div>
</div>

{{-- Floating Action Button --}}
@include('components.content-generator.fab')

{{-- Modals --}}
@include('components.content-generator.modals.social-preview', ['content' => $content])
@include('components.content-generator.modals.ai-refinement', ['content' => $content])
@include('components.content-generator.modals.version-history', ['content' => $content])
@include('components.content-generator.modals.seo-analysis', ['content' => $content])
@include('components.content-generator.modals.schedule', ['content' => $content])
@include('components.content-generator.modals.team-collaboration', ['content' => $content])
@include('components.content-generator.modals.comments', ['content' => $content])
@include('components.content-generator.modals.assign-content', ['content' => $content])
@include('components.content-generator.modals.translate', ['content' => $content])
@include('components.content-generator.modals.save-template', ['content' => $content])
@include('components.content-generator.modals.analytics', ['content' => $content])
@include('components.content-generator.modals.pptx-export', [
    'content' => $content,
    'pptxStyles' => $pptxStyles ?? [],
    'pptxThemes' => $pptxThemes ?? [],
    'pptxDetails' => $pptxDetails ?? [],
    'currentLocale' => app()->getLocale()
])

{{-- Scripts --}}
@include('components.content-generator.scripts', [
    'content' => $content,
    'pptxStyles' => $pptxStyles ?? [],
    'pptxThemes' => $pptxThemes ?? [],
    'pptxDetails' => $pptxDetails ?? [],
    'currentLocale' => app()->getLocale()
])

@endsection
