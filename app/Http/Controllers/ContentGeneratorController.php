<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Models\ContentType;
use App\Models\GeneratedContent;
use App\Models\ContentFavorite;
use App\Models\Specialty;
use App\Models\Topic;
use App\Services\ContentGeneratorService;
use App\Services\PdfExportService;
use App\Services\PowerPointExportService;
use App\Services\SocialMediaPreviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContentGeneratorController extends Controller
{
    protected ContentGeneratorService $contentService;
    protected PdfExportService $pdfService;
    protected PowerPointExportService $pptService;
    protected SocialMediaPreviewService $socialMediaService;

    public function __construct(
        ContentGeneratorService $contentService, 
        PdfExportService $pdfService,
        PowerPointExportService $pptService,
        SocialMediaPreviewService $socialMediaService
    ) {
        $this->middleware(['auth', 'verified']);
        $this->contentService = $contentService;
        $this->pdfService = $pdfService;
        $this->pptService = $pptService;
        $this->socialMediaService = $socialMediaService;
    }

    /**
     * Show the content generator dashboard (Chat Interface).
     */
    public function index()
    {
        $user = Auth::user();
        
        // Load specialties with their active topics
        $specialties = Specialty::active()
            ->ordered()
            ->with(['activeTopics.translations'])
            ->get();
            
        $contentTypes = ContentType::active()->ordered()->get();
        
        $recentContents = $this->contentService->getUserHistory($user, 10);
        
        // Calculate available credits from subscription
        $availableCredits = $this->calculateAvailableCredits($user);

        return view('content-generator.chat', [
            'specialties' => $specialties,
            'contentTypes' => $contentTypes,
            'recentContents' => $recentContents,
            'availableCredits' => $availableCredits,
            'user' => $user,
            'languages' => $this->getSupportedLanguages(),
            'tones' => $this->getSupportedTones(),
        ]);
    }
    
    /**
     * Calculate available credits for a user based on their subscription.
     */
    private function calculateAvailableCredits($user): int
    {
        $subscription = $user->activeSubscription;
        
        if (!$subscription) {
            return 0;
        }

        $plan = $subscription->subscription;
        
        if (!$plan) {
            return 0;
        }

        // Unlimited plan
        if ($plan->max_content_generations == -1) {
            return -1; // Indicates unlimited
        }

        $monthlyUsage = $user->getMonthlyGenerationsCount();
        $maxAllowed = $plan->max_content_generations;

        return max(0, $maxAllowed - $monthlyUsage);
    }

    /**
     * Get topics for a specific specialty (AJAX).
     */
    public function getTopics($lang, $specialtyId)
    {
        $specialty = Specialty::with(['activeTopics.translations'])->find($specialtyId);
        
        if (!$specialty) {
            return response()->json(['topics' => []]);
        }

        $topics = $specialty->activeTopics->map(function ($topic) {
            return [
                'id' => $topic->id,
                'name' => $topic->name,
                'description' => $topic->description,
                'icon' => $topic->icon,
            ];
        });

        return response()->json(['topics' => $topics]);
    }

    /**
     * Generate content.
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'specialty_id' => 'nullable|exists:specialties,id',
            'topic_id' => 'nullable|exists:topics,id',
            'content_type_id' => 'required|exists:content_types,id',
            'prompt' => 'required|string|min:5|max:1000',
            'language' => 'required|string|max:10',
            'tone' => 'nullable|string|max:50',
            'word_count' => 'nullable|integer|min:100|max:2000',
            'target_audience' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:50',
            'additional_instructions' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        // Get related models
        $contentType = ContentType::find($validated['content_type_id']);
        $specialty = isset($validated['specialty_id']) 
            ? Specialty::find($validated['specialty_id']) 
            : null;
        $topic = isset($validated['topic_id']) 
            ? Topic::find($validated['topic_id']) 
            : null;

        // Prepare input data
        $inputData = [
            'topic' => $validated['prompt'],
            'language' => $this->getLanguageName($validated['language']),
            'tone' => $validated['tone'] ?? 'professional and friendly',
            'word_count' => $validated['word_count'] ?? 500,
            'target_audience' => $validated['target_audience'] ?? 'patients',
            'country' => $validated['country'] ?? null,
            'additional_instructions' => $validated['additional_instructions'] ?? '',
        ];

        // Generate content with topic context
        $result = $this->contentService->generate(
            $user,
            $contentType->id,
            $specialty?->id,
            $topic?->id,
            $inputData,
            $validated['language']
        );

        // Always return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($result);
        }

        if (!$result['success']) {
            return back()->withErrors(['generation' => $result['error']])->withInput();
        }

        return redirect()->route('content.show', $result['content_id'])
            ->with('success', __('translation.content_generator.generated_successfully'));
    }

    /**
     * Show generated content.
     */
    public function show($lang,int $id)
    {
        $user = Auth::user();
        $content = $this->contentService->getContent($user, $id);

        if (!$content) {
            abort(404);
        }

        // Track content view
        $content->incrementViews();

        // Get PowerPoint themes and styles with current locale
        $pptxThemes = PowerPointExportService::getAvailableThemes(app()->getLocale());
        $pptxStyles = \App\Services\PowerPoint\SlideContentGenerator::getPresentationStyles();
        $pptxDetails = \App\Services\PowerPoint\SlideContentGenerator::getDetailLevels();

        return view('content-generator.show', [
            'content' => $content,
            'pptxThemes' => $pptxThemes,
            'pptxStyles' => $pptxStyles,
            'pptxDetails' => $pptxDetails,
            'currentLocale' => app()->getLocale(),
        ]);
    }

    /**
     * Show content history.
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        
        $query = GeneratedContent::with(['specialty', 'contentType', 'topic'])
            ->forUser($user->id)
            ->completed();

        // Filter by specialty (using slug)
        if ($request->has('specialty') && $request->specialty) {
            $specialty = Specialty::where('slug', $request->specialty)->first();
            if ($specialty) {
                $query->where('specialty_id', $specialty->id);
            }
        }

        // Filter by content type (using slug)
        if ($request->has('content_type') && $request->content_type) {
            $contentType = ContentType::where('slug', $request->content_type)->first();
            if ($contentType) {
                $query->where('content_type_id', $contentType->id);
            }
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $contents = $query->orderBy('created_at', 'desc')->paginate(15);

        $specialties = Specialty::active()->ordered()->get();
        $contentTypes = ContentType::active()->ordered()->get();

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'html' => view('content-generator.partials.history-table', compact('contents'))->render(),
                'total' => $contents->total(),
            ]);
        }

        return view('content-generator.history', [
            'contents' => $contents,
            'specialties' => $specialties,
            'contentTypes' => $contentTypes,
        ]);
    }

    /**
     * Delete generated content.
     */
    public function destroy($lang,int $id)
    {
        $user = Auth::user();
        $content = GeneratedContent::forUser($user->id)->find($id);

        if (!$content) {
            abort(404);
        }

        $content->delete();

        return redirect()->route('content.history')
            ->with('success', __('translation.content_generator.deleted_successfully'));
    }

    /**
     * Export content as text file.
     */
    public function export($lang,int $id)
    {
        $user = Auth::user();
        $content = $this->contentService->getContent($user, $id);

        if (!$content) {
            abort(404);
        }

        $filename = 'content-' . $content->id . '-' . now()->format('Y-m-d') . '.txt';
        
        $headers = [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $exportContent = "Generated Content\n";
        $exportContent .= "================\n\n";
        $exportContent .= "Type: " . ($content->contentType->name ?? 'N/A') . "\n";
        $exportContent .= "Specialty: " . ($content->specialty->name ?? 'General') . "\n";
        $exportContent .= "Topic: " . ($content->input_data['topic'] ?? 'N/A') . "\n";
        $exportContent .= "Generated: " . $content->created_at->format('Y-m-d H:i') . "\n";
        $exportContent .= "\n================\n\n";
        $exportContent .= $content->output_text;

        return response($exportContent, 200, $headers);
    }

    /**
     * Export content as PDF with professional medical formatting.
     */
    public function exportPdf($lang, int $id, Request $request)
    {
        $user = Auth::user();
        $content = $this->contentService->getContent($user, $id);

        if (!$content) {
            abort(404);
        }

        // Get format and orientation from request
        $format = $request->get('format', 'a4'); // a4, letter, a5
        $orientation = $request->get('orientation', 'portrait'); // portrait, landscape
        $action = $request->get('action', 'download'); // download or stream

        $options = [
            'format' => $format,
            'orientation' => $orientation,
        ];

        // Stream in browser or download
        if ($action === 'stream') {
            return $this->pdfService->streamPdf($content, $options);
        }

        return $this->pdfService->exportToPdf($content, $options);
    }

    /**
     * Export content as PowerPoint presentation with theme selection.
     */
    public function exportPowerPoint($lang, int $id, Request $request)
    {
        $user = Auth::user();
        $content = $this->contentService->getContent($user, $id);

        if (!$content) {
            abort(404);
        }

        // Get export options from request
        $options = [
            'theme' => $request->get('theme', 'professional_blue'),
            'style' => $request->get('style', 'educational'),
            'detail' => $request->get('detail', 'standard'),
        ];

        try {
            $filepath = $this->pptService->export($content, $options['theme'], $options);
            
            $filename = pathinfo($filepath, PATHINFO_BASENAME);
            
            return response()->download($filepath, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            ])->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            \Log::error('PowerPoint export failed', [
                'content_id' => $id,
                'options' => $options,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', __('Failed to export PowerPoint presentation.'));
        }
    }

    /**
     * Get available PowerPoint themes.
     */
    public function getPowerPointThemes()
    {
        return response()->json([
            'success' => true,
            'themes' => PowerPointExportService::getAvailableThemes()
        ]);
    }

    /**
     * Get supported languages from config.
     */
    protected function getSupportedLanguages(): array
    {
        $languages = config('languages', []);
        $result = [];
        foreach ($languages as $code => $data) {
            $result[$code] = $data['display'] ?? $code;
        }
        return $result;
    }

    /**
     * Get language name from code.
     */
    protected function getLanguageName(string $code): string
    {
        $languages = config('languages', []);
        return $languages[$code]['display'] ?? 'English';
    }

    /**
     * Get supported tones.
     */
    protected function getSupportedTones(): array
    {
        return [
            'professional' => __('translation.content_generator.tones.professional'),
            'friendly' => __('translation.content_generator.tones.friendly'),
            'educational' => __('translation.content_generator.tones.educational'),
            'reassuring' => __('translation.content_generator.tones.reassuring'),
            'empathetic' => __('translation.content_generator.tones.empathetic'),
        ];
    }

    /**
     * Toggle favorite status for content.
     */
    public function toggleFavorite($lang, int $id)
    {
        $user = Auth::user();
        $content = GeneratedContent::forUser($user->id)->find($id);

        if (!$content) {
            return response()->json(['success' => false, 'message' => __('translation.common.not_found')], 404);
        }

        $favorite = ContentFavorite::where('user_id', $user->id)
            ->where('content_id', $id)
            ->first();

        if ($favorite) {
            // Unfavorite
            $favorite->delete();
            $isFavorited = false;
            $message = __('translation.content_generator.unfavorited');
        } else {
            // Favorite
            ContentFavorite::create([
                'user_id' => $user->id,
                'content_id' => $id,
            ]);
            $isFavorited = true;
            $message = __('translation.content_generator.favorited');
        }

        return response()->json([
            'success' => true,
            'is_favorited' => $isFavorited,
            'message' => $message,
        ]);
    }

    /**
     * Show user's favorited content.
     */
    public function favorites()
    {
        $user = Auth::user();
        
        $favorites = $user->favoriteContents()
            ->with(['specialty', 'contentType', 'topic'])
            ->paginate(15);

        $specialties = Specialty::active()->ordered()->get();
        $contentTypes = ContentType::active()->ordered()->get();

        return view('content-generator.favorites', [
            'contents' => $favorites,
            'specialties' => $specialties,
            'contentTypes' => $contentTypes,
        ]);
    }

    /**
     * Get social media preview for content.
     */
    public function getSocialPreview($lang, int $id, Request $request)
    {
        $user = Auth::user();
        $content = $this->contentService->getContent($user, $id);

        if (!$content) {
            return response()->json(['success' => false, 'message' => __('translation.common.not_found')], 404);
        }

        $platform = $request->get('platform', 'facebook');
        
        try {
            $preview = $this->socialMediaService->generatePreview($content, $platform);
            
            // Track social preview analytics
            \App\Models\ContentAnalytics::track($content->id, 'social_preview', $platform);
            
            return response()->json([
                'success' => true,
                'preview' => $preview,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
