<?php

namespace App\Services;

use App\Models\Template;
use App\Models\GeneratedContent;
use App\Models\User;
use App\Models\TeamWorkspace;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

class TemplateService
{
    private $openaiApiKey;
    private $timeout = 120;

    public function __construct()
    {
        $this->openaiApiKey = config('services.openai.api_key');
    }

    /**
     * Create new template
     */
    public function createTemplate(User $user, array $data): Template
    {
        $template = Template::create([
            'user_id' => $user->id,
            'specialty_id' => $data['specialty_id'] ?? null,
            'content_type_id' => $data['content_type_id'] ?? null,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'template_content' => $data['template_content'],
            'language' => $data['language'] ?? 'en',
            'variables' => $this->extractVariables($data['template_content']),
            'visibility' => $data['visibility'] ?? 'private',
            'team_workspace_id' => $data['team_workspace_id'] ?? null,
            'allow_team_edit' => $data['allow_team_edit'] ?? false,
            'is_active' => true,
            'version' => 1,
            'metadata' => [
                'tags' => $data['tags'] ?? [],
                'category' => $data['category'] ?? 'general',
                'difficulty' => $data['difficulty'] ?? 'medium',
            ],
        ]);

        return $template;
    }

    /**
     * Update existing template
     */
    public function updateTemplate(Template $template, array $data): Template
    {
        $updateData = [];

        if (isset($data['name'])) {
            $updateData['name'] = $data['name'];
        }

        if (isset($data['description'])) {
            $updateData['description'] = $data['description'];
        }

        if (isset($data['template_content'])) {
            $updateData['template_content'] = $data['template_content'];
            $updateData['variables'] = $this->extractVariables($data['template_content']);
        }

        if (isset($data['visibility'])) {
            $updateData['visibility'] = $data['visibility'];
        }

        if (isset($data['allow_team_edit'])) {
            $updateData['allow_team_edit'] = $data['allow_team_edit'];
        }

        if (isset($data['metadata'])) {
            $updateData['metadata'] = array_merge(
                $template->metadata ?? [],
                $data['metadata']
            );
        }

        $template->update($updateData);
        return $template->fresh();
    }

    /**
     * Extract variables from template content
     */
    private function extractVariables(string $content): array
    {
        preg_match_all('/\{\{([a-zA-Z0-9_]+)\}\}/', $content, $matches);
        
        $variables = [];
        foreach (array_unique($matches[1]) as $variable) {
            $variables[] = [
                'name' => $variable,
                'required' => true,
                'type' => $this->guessVariableType($variable),
                'description' => $this->generateVariableDescription($variable),
            ];
        }

        return $variables;
    }

    /**
     * Guess variable type from name
     */
    private function guessVariableType(string $name): string
    {
        $name = strtolower($name);

        if (str_contains($name, 'date') || str_contains($name, 'time')) {
            return 'date';
        }

        if (str_contains($name, 'age') || str_contains($name, 'count') || str_contains($name, 'number')) {
            return 'number';
        }

        if (str_contains($name, 'email')) {
            return 'email';
        }

        if (str_contains($name, 'url') || str_contains($name, 'link')) {
            return 'url';
        }

        return 'text';
    }

    /**
     * Generate variable description
     */
    private function generateVariableDescription(string $name): string
    {
        // Convert snake_case or camelCase to readable text
        $readable = preg_replace('/([a-z])([A-Z])/', '$1 $2', $name);
        $readable = str_replace('_', ' ', $readable);
        return ucfirst(strtolower($readable));
    }

    /**
     * Apply template to generate content
     */
    public function applyTemplate(Template $template, array $variables, User $user): array
    {
        try {
            // First, render the template with variables
            $renderedContent = $this->renderTemplate($template->template_content, $variables);

            // Then enhance with AI
            $enhancedContent = $this->enhanceWithAI($renderedContent, $template, $variables);

            // Track usage
            $template->incrementUsage();

            return [
                'success' => true,
                'content' => $enhancedContent,
                'rendered_content' => $renderedContent,
                'template_id' => $template->id,
                'variables_used' => $variables,
            ];

        } catch (\Exception $e) {
            Log::error('Template application failed', [
                'template_id' => $template->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to apply template: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Render template with variables
     */
    private function renderTemplate(string $template, array $variables): string
    {
        $rendered = $template;

        foreach ($variables as $key => $value) {
            $rendered = str_replace('{{' . $key . '}}', $value, $rendered);
        }

        // Check for unresolved variables
        if (preg_match('/\{\{([a-zA-Z0-9_]+)\}\}/', $rendered, $matches)) {
            throw new \Exception("Missing variable: {$matches[1]}");
        }

        return $rendered;
    }

    /**
     * Enhance template content with AI
     */
    private function enhanceWithAI(string $content, Template $template, array $variables): string
    {
        $prompt = "Enhance and polish the following medical content while maintaining all facts and structure.

Original Content:
{$content}

Requirements:
- Improve readability and flow
- Ensure medical accuracy
- Add professional medical terminology where appropriate
- Maintain the original structure and key points
- Make it more engaging while staying professional
- Do not add disclaimers or watermarks

Enhanced content:";

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->openaiApiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4-turbo-preview',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a professional medical content enhancer who improves clarity and professionalism while maintaining accuracy.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 4000,
                ]);

            if ($response->successful()) {
                $result = $response->json();
                $enhanced = $result['choices'][0]['message']['content'] ?? $content;
                return $enhanced;
            }

            return $content; // Return original if enhancement fails

        } catch (\Exception $e) {
            Log::error('AI enhancement failed', [
                'template_id' => $template->id,
                'error' => $e->getMessage()
            ]);
            return $content;
        }
    }

    /**
     * Duplicate template (create version)
     */
    public function duplicateTemplate(Template $template, User $user, ?string $newName = null): Template
    {
        $newTemplate = $template->replicate();
        $newTemplate->user_id = $user->id;
        $newTemplate->name = $newName ?? ($template->name . ' (Copy)');
        $newTemplate->parent_template_id = $template->id;
        $newTemplate->version = ($template->childVersions()->max('version') ?? 0) + 1;
        $newTemplate->usage_count = 0;
        $newTemplate->last_used_at = null;
        $newTemplate->save();

        return $newTemplate;
    }

    /**
     * Share template with team
     */
    public function shareWithTeam(Template $template, TeamWorkspace $team): bool
    {
        $template->update([
            'team_workspace_id' => $team->id,
            'visibility' => 'team',
        ]);

        return true;
    }

    /**
     * Make template public
     */
    public function makePublic(Template $template): bool
    {
        $template->update([
            'visibility' => 'public',
        ]);

        return true;
    }

    /**
     * Get popular templates
     */
    public function getPopularTemplates(int $limit = 10): Collection
    {
        return Template::where('visibility', 'public')
            ->where('is_active', true)
            ->orderBy('usage_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get user's templates
     */
    public function getUserTemplates(User $user, array $filters = []): Collection
    {
        $query = Template::where('user_id', $user->id);

        if (isset($filters['specialty_id'])) {
            $query->where('specialty_id', $filters['specialty_id']);
        }

        if (isset($filters['content_type_id'])) {
            $query->where('content_type_id', $filters['content_type_id']);
        }

        if (isset($filters['visibility'])) {
            $query->visibility($filters['visibility']);
        }

        if (isset($filters['search'])) {
            $query->search($filters['search']);
        }

        return $query->orderBy('last_used_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get team templates
     */
    public function getTeamTemplates(TeamWorkspace $team): Collection
    {
        return Template::forTeam($team->id)
            ->where('is_active', true)
            ->orderBy('usage_count', 'desc')
            ->get();
    }

    /**
     * Search templates
     */
    public function searchTemplates(string $query, User $user, array $filters = []): Collection
    {
        $templates = Template::search($query)
            ->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('visibility', 'public')
                  ->orWhereHas('teamWorkspace', function($q) use ($user) {
                      $q->whereHas('members', function($q) use ($user) {
                          $q->where('user_id', $user->id)
                            ->where('status', 'active');
                      });
                  });
            });

        if (isset($filters['specialty_id'])) {
            $templates->where('specialty_id', $filters['specialty_id']);
        }

        if (isset($filters['content_type_id'])) {
            $templates->where('content_type_id', $filters['content_type_id']);
        }

        return $templates->orderBy('usage_count', 'desc')->get();
    }

    /**
     * Delete template
     */
    public function deleteTemplate(Template $template): bool
    {
        // Soft delete by marking inactive
        $template->update(['is_active' => false]);
        return true;
    }

    /**
     * Get template statistics
     */
    public function getTemplateStatistics(Template $template): array
    {
        return [
            'usage_count' => $template->usage_count,
            'last_used' => $template->last_used_at?->diffForHumans(),
            'contents_generated' => $template->generatedContents()->count(),
            'avg_content_length' => $template->generatedContents()->avg('word_count'),
            'total_versions' => $template->childVersions()->count(),
            'team_members_using' => $template->teamWorkspace 
                ? $template->teamWorkspace->members()->where('status', 'active')->count()
                : 0,
        ];
    }
}
