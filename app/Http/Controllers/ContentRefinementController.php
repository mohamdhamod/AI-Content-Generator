<?php

namespace App\Http\Controllers;

use App\Models\GeneratedContent;
use App\Services\ContentRefinementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContentRefinementController extends Controller
{
    protected $refinementService;

    public function __construct(ContentRefinementService $refinementService)
    {
        $this->refinementService = $refinementService;
    }

    /**
     * Get available refinement options
     */
    public function getOptions()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'actions' => ContentRefinementService::getAvailableActions(),
                'tones' => ContentRefinementService::getAvailableTones(),
            ],
        ]);
    }

    /**
     * Refine content using AI
     */
    public function refine($lang, int $id, Request $request)
    {
        $request->validate([
            'action' => 'required|string|in:' . implode(',', array_keys(ContentRefinementService::REFINEMENT_ACTIONS)),
            'tone' => 'nullable|string|in:' . implode(',', array_keys(ContentRefinementService::TONE_STYLES)),
        ]);

        $user = Auth::user();
        $content = GeneratedContent::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        try {
            DB::beginTransaction();

            $options = [];
            if ($request->filled('tone')) {
                $options['tone'] = $request->tone;
            }

            $refinedContent = $this->refinementService->refineContent(
                $content,
                $request->action,
                $options
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('translation.content_generator.refinement_success'),
                'data' => [
                    'new_version_id' => $refinedContent->id,
                    'version' => $refinedContent->version,
                    'redirect_url' => route('content.show', ['locale' => $lang, 'id' => $refinedContent->id]),
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => __('translation.content_generator.refinement_failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Adjust content tone
     */
    public function adjustTone($lang, int $id, Request $request)
    {
        $request->validate([
            'tone' => 'required|string|in:' . implode(',', array_keys(ContentRefinementService::TONE_STYLES)),
        ]);

        $user = Auth::user();
        $content = GeneratedContent::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        try {
            DB::beginTransaction();

            $refinedContent = $this->refinementService->adjustTone(
                $content,
                $request->tone
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('translation.content_generator.tone_adjustment_success'),
                'data' => [
                    'new_version_id' => $refinedContent->id,
                    'version' => $refinedContent->version,
                    'redirect_url' => route('content.show', ['locale' => $lang, 'id' => $refinedContent->id]),
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => __('translation.content_generator.tone_adjustment_failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get version history
     */
    public function versionHistory($lang, int $id)
    {
        $user = Auth::user();
        $content = GeneratedContent::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $history = $this->refinementService->getVersionHistory($content);

        return response()->json([
            'success' => true,
            'data' => $history,
        ]);
    }

    /**
     * Compare two versions
     */
    public function compareVersions($lang, Request $request)
    {
        $request->validate([
            'version1_id' => 'required|integer|exists:generated_contents,id',
            'version2_id' => 'required|integer|exists:generated_contents,id',
        ]);

        $user = Auth::user();

        $version1 = GeneratedContent::where('id', $request->version1_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $version2 = GeneratedContent::where('id', $request->version2_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $comparison = $this->refinementService->compareVersions($version1, $version2);

        // Track comparison
        \App\Models\ContentAnalytics::track(
            $version1->id,
            'version_compare',
            null,
            [
                'compared_with' => $version2->id,
                'version1' => $version1->version,
                'version2' => $version2->version,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $comparison,
        ]);
    }

    /**
     * Restore a previous version (create new version from old one)
     */
    public function restoreVersion($lang, int $id, Request $request)
    {
        $request->validate([
            'restore_to_id' => 'required|integer|exists:generated_contents,id',
        ]);

        $user = Auth::user();

        $currentContent = GeneratedContent::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $restoreFrom = GeneratedContent::where('id', $request->restore_to_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        try {
            DB::beginTransaction();

            // Create new version with old content
            $restoredVersion = $currentContent->replicate();
            $restoredVersion->output_text = $restoreFrom->output_text;
            $restoredVersion->version = $currentContent->version + 1;
            $restoredVersion->parent_content_id = $currentContent->parent_content_id ?? $currentContent->id;
            $restoredVersion->review_status = 'draft';
            $restoredVersion->is_published = false;
            $restoredVersion->review_notes = "Restored from version {$restoreFrom->version}";
            $restoredVersion->word_count = $restoreFrom->word_count;
            $restoredVersion->view_count = 0;
            $restoredVersion->share_count = 0;
            $restoredVersion->pdf_download_count = 0;
            $restoredVersion->save();

            // Track restoration
            \App\Models\ContentAnalytics::track(
                $restoredVersion->id,
                'version_restore',
                null,
                [
                    'restored_from_id' => $restoreFrom->id,
                    'restored_from_version' => $restoreFrom->version,
                    'new_version' => $restoredVersion->version,
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('translation.content_generator.version_restored'),
                'data' => [
                    'new_version_id' => $restoredVersion->id,
                    'version' => $restoredVersion->version,
                    'redirect_url' => route('content.show', ['locale' => $lang, 'id' => $restoredVersion->id]),
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => __('translation.content_generator.version_restore_failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
