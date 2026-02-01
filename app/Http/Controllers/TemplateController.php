<?php

namespace App\Http\Controllers;

use App\Services\TemplateService;
use App\Models\Template;
use App\Models\GeneratedContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class TemplateController extends Controller
{
    private $templateService;

    public function __construct(TemplateService $templateService)
    {
        $this->templateService = $templateService;
        $this->middleware('auth');
    }

    /**
     * List templates
     * GET /templates
     */
    public function index(Request $request)
    {
        // If AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            $filters = $request->only(['specialty_id', 'content_type_id', 'visibility', 'search']);

            $templates = $this->templateService->getUserTemplates(
                $request->user(),
                $filters
            );

            return response()->json([
                'success' => true,
                'data' => $templates->map(function($template) {
                    return [
                        'id' => $template->id,
                        'name' => $template->name,
                        'description' => $template->description,
                        'visibility' => $template->visibility,
                        'language' => $template->language,
                        'usage_count' => $template->usage_count,
                        'variables' => $template->variables,
                        'metadata' => $template->metadata,
                        'created_at' => $template->created_at->format('Y-m-d H:i'),
                        'last_used_at' => $template->last_used_at?->diffForHumans(),
                    ];
                })
            ]);
        }
        
        // Otherwise return view
        return view('templates.index');
    }

    /**
     * Get popular templates
     * GET /templates/popular
     */
    public function popular(Request $request)
    {
        $limit = $request->input('limit', 10);
        $templates = $this->templateService->getPopularTemplates($limit);

        return response()->json([
            'success' => true,
            'data' => $templates
        ]);
    }

    /**
     * Create template
     * POST /templates
     */
    public function store(Request $request)
    {
        // Rate limiting
        $key = 'create-template:' . $request->user()->id;
        if (RateLimiter::tooManyAttempts($key, 20)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many templates created. Please wait.'
            ], 429);
        }
        RateLimiter::hit($key, 60);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'template_content' => 'nullable|string',
            'content_id' => 'nullable|exists:generated_contents,id',
            'specialty_id' => 'nullable|exists:specialties,id',
            'content_type_id' => 'nullable|exists:content_types,id',
            'language' => 'nullable|string|in:en,ar,fr,es,de,it,pt,ru,zh,ja',
            'visibility' => 'nullable|in:private,team,public',
            'team_workspace_id' => 'nullable|exists:team_workspaces,id',
            'allow_team_edit' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'category' => 'nullable|string',
            'include_structure' => 'nullable|boolean',
            'include_tone' => 'nullable|boolean',
            'include_prompt_hints' => 'nullable|boolean',
            'share_with_team' => 'nullable|boolean',
        ]);

        try {
            $templateData = $request->all();
            
            // If content_id is provided, extract template from existing content
            if ($request->content_id) {
                $content = GeneratedContent::findOrFail($request->content_id);
                
                // Authorization - make sure user owns the content
                if ($content->user_id !== $request->user()->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized to create template from this content'
                    ], 403);
                }
                
                // Build template content from existing content
                $templateContent = '';
                
                if ($request->include_structure) {
                    // Extract structure (headings) from content
                    $templateContent = $this->extractContentStructure($content->output_text);
                } else {
                    $templateContent = $content->output_text;
                }
                
                $templateData['template_content'] = $templateContent;
                $templateData['specialty_id'] = $templateData['specialty_id'] ?? $content->specialty_id;
                $templateData['content_type_id'] = $templateData['content_type_id'] ?? $content->content_type_id;
                $templateData['language'] = $templateData['language'] ?? $content->language;
                
                // Include tone settings if requested
                if ($request->include_tone && isset($content->input_data['tone'])) {
                    $templateData['default_tone'] = $content->input_data['tone'];
                }
                
                // Include prompt hints if requested
                if ($request->include_prompt_hints && isset($content->input_data['topic'])) {
                    $templateData['prompt_hints'] = $content->input_data['topic'];
                }
                
                // Set visibility based on share_with_team
                if ($request->share_with_team) {
                    $templateData['visibility'] = 'team';
                }
            }
            
            // Ensure template_content exists
            if (empty($templateData['template_content'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Template content is required'
                ], 422);
            }

            $template = $this->templateService->createTemplate(
                $request->user(),
                $templateData
            );

            return response()->json([
                'success' => true,
                'message' => 'Template created successfully',
                'data' => [
                    'id' => $template->id,
                    'name' => $template->name,
                    'variables' => $template->variables,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create template: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Extract content structure (headings and sections) from text
     */
    private function extractContentStructure(string $content): string
    {
        $lines = explode("\n", $content);
        $structure = [];
        
        foreach ($lines as $line) {
            $trimmed = trim($line);
            
            // Check for markdown headings
            if (preg_match('/^(#{1,6})\s+(.+)$/', $trimmed, $matches)) {
                $level = strlen($matches[1]);
                $heading = $matches[2];
                $structure[] = str_repeat('#', $level) . ' [' . $heading . ' Section]';
                $structure[] = '';
                $structure[] = '[Content for ' . $heading . ' goes here...]';
                $structure[] = '';
            }
            // Check for HTML headings
            elseif (preg_match('/<h([1-6])[^>]*>(.+?)<\/h[1-6]>/i', $trimmed, $matches)) {
                $level = (int)$matches[1];
                $heading = strip_tags($matches[2]);
                $structure[] = str_repeat('#', $level) . ' [' . $heading . ' Section]';
                $structure[] = '';
                $structure[] = '[Content for ' . $heading . ' goes here...]';
                $structure[] = '';
            }
        }
        
        if (empty($structure)) {
            // If no headings found, create a basic structure
            $structure = [
                '# [Main Title]',
                '',
                '## [Introduction]',
                '[Opening content...]',
                '',
                '## [Main Content]',
                '[Core information...]',
                '',
                '## [Conclusion]',
                '[Closing content...]',
            ];
        }
        
        return implode("\n", $structure);
    }

    /**
     * View template
     * GET /templates/{id}
     */
    public function show($lang ,$id)
    {
        $template = Template::with(['user', 'specialty', 'contentType', 'teamWorkspace'])->findOrFail($id);

        // Authorization
        if (!$template->canEdit(request()->user()) && !$template->isPublic()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $statistics = $this->templateService->getTemplateStatistics($template);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $template->id,
                'name' => $template->name,
                'description' => $template->description,
                'template_content' => $template->template_content,
                'variables' => $template->variables,
                'language' => $template->language,
                'visibility' => $template->visibility,
                'version' => $template->version,
                'metadata' => $template->metadata,
                'statistics' => $statistics,
                'specialty' => $template->specialty?->name,
                'content_type' => $template->contentType?->name,
                'team' => $template->teamWorkspace?->name,
                'created_by' => $template->user->name,
                'created_at' => $template->created_at->format('Y-m-d H:i'),
            ]
        ]);
    }

    /**
     * Update template
     * PUT /templates/{id}
     */
    public function update(Request $request, $lang, $id)
    {
        $template = Template::findOrFail($id);

        // Authorization
        if (!$template->canEdit($request->user())) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:1000',
            'template_content' => 'sometimes|string',
            'visibility' => 'sometimes|in:private,team,public',
            'allow_team_edit' => 'sometimes|boolean',
            'metadata' => 'sometimes|array',
        ]);

        try {
            $updatedTemplate = $this->templateService->updateTemplate(
                $template,
                $request->all()
            );

            return response()->json([
                'success' => true,
                'message' => 'Template updated successfully',
                'data' => $updatedTemplate
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete template
     * DELETE /templates/{id}
     */
    public function destroy($lang, $id)
    {
        $template = Template::findOrFail($id);

        // Authorization - only owner can delete
        if ($template->user_id !== request()->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $this->templateService->deleteTemplate($template);

            return response()->json([
                'success' => true,
                'message' => 'Template deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Duplicate template
     * POST /templates/{id}/duplicate
     */
    public function duplicate(Request $request, $lang, $id)
    {
        $template = Template::findOrFail($id);

        // Check if user can view the template
        if (!$template->canEdit($request->user()) && !$template->isPublic()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'name' => 'nullable|string|max:255',
        ]);

        try {
            $newTemplate = $this->templateService->duplicateTemplate(
                $template,
                $request->user(),
                $request->input('name')
            );

            return response()->json([
                'success' => true,
                'message' => 'Template duplicated successfully',
                'data' => [
                    'id' => $newTemplate->id,
                    'name' => $newTemplate->name,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to duplicate template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Apply template to generate content
     * POST /templates/{id}/apply
     */
    public function apply(Request $request, $lang, $id)
    {
        // Rate limiting
        $key = 'apply-template:' . $request->user()->id;
        if (RateLimiter::tooManyAttempts($key, 10)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many template applications. Please wait.'
            ], 429);
        }
        RateLimiter::hit($key, 60);

        $template = Template::findOrFail($id);

        // Check access
        if (!$template->canEdit($request->user()) && !$template->isPublic() && !$template->isTeamAccessible()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Validate variables
        $variables = $template->variables ?? [];
        $requiredVariables = collect($variables)->where('required', true)->pluck('name')->toArray();

        $request->validate([
            'variables' => 'required|array',
        ]);

        $providedVariables = array_keys($request->input('variables'));
        $missing = array_diff($requiredVariables, $providedVariables);

        if (count($missing) > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Missing required variables: ' . implode(', ', $missing)
            ], 422);
        }

        try {
            $result = $this->templateService->applyTemplate(
                $template,
                $request->input('variables'),
                $request->user()
            );

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 500);
            }

            // Save as content
            $content = GeneratedContent::create([
                'user_id' => $request->user()->id,
                'specialty_id' => $template->specialty_id,
                'content_type_id' => $template->content_type_id,
                'template_id' => $template->id,
                'template_variables' => $request->input('variables'),
                'topic' => $request->input('variables')['topic'] ?? 'From Template',
                'language' => $template->language,
                'generated_content' => $result['content'],
                'word_count' => str_word_count($result['content']),
                'tone' => 'professional',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Content generated from template successfully',
                'data' => [
                    'content_id' => $content->id,
                    'content' => $result['content'],
                    'template_id' => $template->id,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to apply template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Share template with team
     * POST /templates/{id}/share
     */
    public function shareWithTeam(Request $request, $lang, $id)
    {
        $template = Template::findOrFail($id);

        // Authorization - only owner can share
        if ($template->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'team_workspace_id' => 'required|exists:team_workspaces,id',
        ]);

        $team = \App\Models\TeamWorkspace::findOrFail($request->team_workspace_id);

        try {
            $this->templateService->shareWithTeam($template, $team);

            return response()->json([
                'success' => true,
                'message' => 'Template shared with team successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to share template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search templates
     * GET /templates/search
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:3',
        ]);

        $filters = $request->only(['specialty_id', 'content_type_id']);

        $templates = $this->templateService->searchTemplates(
            $request->input('q'),
            $request->user(),
            $filters
        );

        return response()->json([
            'success' => true,
            'data' => $templates
        ]);
    }
}
