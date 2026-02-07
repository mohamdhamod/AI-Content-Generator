<?php

namespace App\Services;

use App\Models\Topic;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

/**
 * Medical Prompt Service
 * 
 * Simplified service that combines:
 * - Global rules from medical_prompts_library.json
 * - Specialties, Topics, Content Types from database
 * 
 * @author Senior Laravel Architect + AI Product Designer + Senior AI Prompt Engineer + Senior Doctor
 */
class MedicalPromptService
{
    protected array $globalRules;
    
    public function __construct()
    {
        $this->loadGlobalRules();
    }
    
    /**
     * Load global rules from JSON file
     */
    protected function loadGlobalRules(): void
    {
        $path = database_path('data/medical_prompts_library.json');
        
        if (File::exists($path)) {
            $data = json_decode(File::get($path), true);
            $this->globalRules = $data['global_rules'] ?? [];
        } else {
            $this->globalRules = $this->getDefaultGlobalRules();
        }
    }
    
    /**
     * Build comprehensive prompt for content generation
     * 
     * @param Topic $topic The medical topic
     * @param string $contentType Type of content from configurations table
     * @param string $language Target language (from config/languages.php)
     * @param array $variables Additional variables (country, audience, platform, etc.)
     * @return array Complete prompt with system and user messages
     */
    public function buildPrompt(
        Topic $topic,
        string $contentType,
        string $language = 'en',
        array $variables = []
    ): array {
        $specialty = $topic->specialty;
        
        // Build system prompt: Global rules + Topic prompt_hint
        $systemPrompt = $this->buildSystemPrompt($topic, $language);
        
        // Build user prompt with variables
        $userPrompt = $this->buildUserPrompt($topic, $contentType, $language, $variables);
        
        // Get disclaimer in target language
        $disclaimer = $this->globalRules['mandatory_disclaimers'][$language] ?? 
                     $this->globalRules['mandatory_disclaimers']['en'];
        
        return [
            'system_prompt' => $systemPrompt,
            'user_prompt' => $userPrompt,
            'disclaimer' => $disclaimer,
            'global_rules' => $this->globalRules,
            'content_type' => $contentType,
            'language' => $language,
        ];
    }
    
    /**
     * Build system prompt combining global rules + topic hint
     */
    protected function buildSystemPrompt(Topic $topic, string $language): string
    {
        $parts = [];
        
        // 1. Role definition
        $parts[] = "You are a medical content specialist creating educational materials for {$topic->specialty->name} clinics.";
        
        // 2. Topic-specific hint from database (already localized)
        if ($topic->prompt_hint) {
            $parts[] = "\n### Topic-Specific Guidelines:\n" . $topic->prompt_hint;
        }
        
        // 3. Content Restrictions
        if (isset($this->globalRules['content_restrictions'])) {
            $restrictions = [];
            foreach ($this->globalRules['content_restrictions'] as $rule => $value) {
                if ($value) {
                    $restrictions[] = '- ' . ucfirst(str_replace('_', ' ', $rule));
                }
            }
            if (!empty($restrictions)) {
                $parts[] = "\n### CRITICAL Content Restrictions:\n" . implode("\n", $restrictions);
            }
        }
        
        // 4. Content Requirements
        if (isset($this->globalRules['content_requirements'])) {
            $requirements = [];
            foreach ($this->globalRules['content_requirements'] as $rule => $value) {
                if ($value) {
                    $requirements[] = '- ' . ucfirst(str_replace('_', ' ', $rule));
                }
            }
            if (!empty($requirements)) {
                $parts[] = "\n### Required Content Standards:\n" . implode("\n", $requirements);
            }
        }
        
        // 5. Prohibited content
        if (isset($this->globalRules['prohibited_content'])) {
            $parts[] = "\n### Prohibited Content:\n- " . implode("\n- ", $this->globalRules['prohibited_content']);
        }
        
        // 6. Mandatory disclaimer
        $disclaimer = $this->globalRules['mandatory_disclaimers'][$language] ?? 
                     $this->globalRules['mandatory_disclaimers']['en'];
        $parts[] = "\n### Mandatory Disclaimer:\nAlways end content with: \"{$disclaimer}\"";
        
        // 7. Tone
        if (isset($this->globalRules['default_tone'])) {
            $parts[] = "\n### Tone: " . $this->globalRules['default_tone'];
        }
        
        return implode("\n", $parts);
    }
    
    /**
     * Build user prompt with variable substitution
     */
    protected function buildUserPrompt(
        Topic $topic,
        string $contentType,
        string $language,
        array $variables
    ): string {
        // Get content type configuration from database
        $contentTypeConfig = $this->getContentTypeConfig($contentType);
        
        // Build basic prompt
        $prompt = "Create {$contentTypeConfig['description']} about '{$topic->name}' ";
        $prompt .= "for a {$topic->specialty->name} clinic. ";
        $prompt .= "Content language: {$this->getLanguageName($language)}. ";
        
        // Add variables
        if (isset($variables['country'])) {
            $prompt .= "Target country/region: {$variables['country']}. ";
        }
        
        if (isset($variables['audience'])) {
            $prompt .= "Target audience: {$variables['audience']}. ";
        }
        
        if (isset($variables['platform']) && $contentType === 'social_media_post') {
            $prompt .= "Platform: {$variables['platform']}. ";
        }
        
        // Add content type specific requirements
        if (isset($contentTypeConfig['requirements'])) {
            $prompt .= "\n\nContent Requirements:\n" . $contentTypeConfig['requirements'];
        }
        
        return $prompt;
    }
    
    /**
     * Get content type configuration from database
     */
    protected function getContentTypeConfig(string $contentType): array
    {
        // Get from content_types table
        $type = \App\Models\ContentType::where('key', $contentType)
            ->where('active', true)
            ->first();
        
        if ($type) {
            return [
                'name' => $type->name,
                'description' => $type->description ?? 'content',
                'placeholder' => $type->placeholder ?? '',
                'requirements' => $this->getContentTypeRequirements($contentType),
                'credits_cost' => $type->credits_cost ?? 1,
            ];
        }
        
        // Fallback defaults if not found in database
        return $this->getDefaultContentTypeConfig($contentType);
    }
    
    /**
     * Get specific requirements for each content type
     */
    protected function getContentTypeRequirements(string $contentType): string
    {
        $requirements = [
            'patient_education' => 'Include: introduction, general information, wellness tips, when to see a doctor, disclaimer. Length: 800-1200 words. Reading level: general public. Write comprehensive and detailed content.',
            'seo_blog_article' => 'Include: H2/H3 headings, meta description, focus keywords, engaging introduction, informational sections, CTA. Length: 1500-2500 words. Write comprehensive and detailed content.',
            'social_media_post' => 'Include: engaging hook, educational tip/fact, relevant hashtags, CTA, emoji suggestions. Length: 200-400 words.',
            'google_review_reply' => 'Include: thank you, acknowledgment of feedback, commitment to care. Length: 100-200 words. Maintain patient privacy.',
            'email_follow_up' => 'Include: subject line, warm greeting, care reminder, general tips, appointment CTA, disclaimer. Length: 300-500 words.',
            'website_faq' => 'Create 8-12 Q&A pairs. Each answer: 100-200 words. Informative but encourage professional consultation.',
            'what_to_expect' => 'Describe: appointment flow, typical duration, preparation tips. Length: 600-1000 words. Friendly, step-by-step format.',
            'university_lecture' => $this->getUniversityLectureRequirements(),
        ];
        
        return $requirements[$contentType] ?? 'Create comprehensive, detailed, patient-friendly educational content. Length: 800-1200 words.';
    }
    
    /**
     * Get detailed requirements for university lecture content type
     * Designed for PowerPoint export with professional academic structure
     * 
     * @return string Comprehensive prompt requirements
     */
    protected function getUniversityLectureRequirements(): string
    {
        return <<<'LECTURE'
Generate a comprehensive university-level medical lecture with the following EXACT structure:

## LEARNING OBJECTIVES
List 4-6 clear, measurable learning objectives starting with action verbs (Understand, Describe, Identify, Explain, Compare, Analyze).
Format each as: "• [Objective text]"

## LECTURE OUTLINE
Provide a numbered outline of all major sections that will be covered.

## SECTION 1: [Introduction/Overview Title]
- Provide comprehensive content for this section
- Include relevant clinical context and epidemiology
- Use bullet points for key concepts
- Add clinical pearls where appropriate

## SECTION 2: [Pathophysiology/Mechanism Title]
- Detailed explanation of underlying mechanisms
- Include relevant diagrams descriptions (marked as [FIGURE: description])
- Cover molecular/cellular aspects if applicable

## SECTION 3: [Clinical Presentation Title]
- Signs and symptoms
- Differential diagnosis considerations
- Physical examination findings
- Use tables for comparisons (marked as [TABLE: description])

## SECTION 4: [Diagnosis Title]
- Diagnostic criteria
- Laboratory investigations
- Imaging modalities
- Diagnostic algorithms

## SECTION 5: [Management/Treatment Title]
- Treatment approaches (general principles only)
- Evidence-based recommendations
- Management algorithms
- Note: Do NOT provide specific medication dosages

## SECTION 6: [Prognosis & Prevention Title]
- Outcomes and prognosis factors
- Prevention strategies
- Patient counseling points

## CASE DISCUSSION
Present 1-2 brief clinical case scenarios for discussion:
- Patient presentation
- Key questions for students
- Teaching points

## KEY TAKEAWAYS
Summarize 5-7 essential points students must remember.
Format each as: "✓ [Key point]"

## REFERENCES
List 3-5 key references in academic format (can be placeholder format like "Author et al., Journal, Year").

## QUESTIONS FOR REVIEW
Include 3-5 review questions (multiple choice or short answer format) to test understanding.

IMPORTANT FORMATTING RULES:
1. Use clear ## headers for main sections
2. Use ### for subsections
3. Use bullet points (•, -, *) for lists
4. Mark figures as [FIGURE: description]
5. Mark tables as [TABLE: description]
6. Keep academic and evidence-based tone
7. Length: 2000-3000 words total
8. Suitable for medical students, residents, or continuing education
LECTURE;
    }
    
    
    protected function getDefaultContentTypeConfig(string $contentType): array
    {
        $defaults = [
            'patient_education' => [
                'name' => 'Patient Education',
                'description' => 'educational content for patients',
                'requirements' => 'Include introduction, general information, wellness tips, when to see a doctor, and disclaimer. 400-600 words.',
                'credits_cost' => 1,
            ],
            'seo_blog_article' => [
                'name' => 'SEO Blog Article',
                'description' => 'SEO-optimized blog article',
                'requirements' => 'Include H2/H3 headings, meta description, focus keywords, engaging introduction, informational sections, and CTA. 800-1200 words.',
                'credits_cost' => 2,
            ],
            'social_media_post' => [
                'name' => 'Social Media Post',
                'description' => 'engaging social media post',
                'requirements' => 'Include hook, educational tip, relevant hashtags, CTA, and emoji suggestions. Platform-appropriate length.',
                'credits_cost' => 1,
            ],
            'google_review_reply' => [
                'name' => 'Google Review Reply',
                'description' => 'professional Google review response',
                'requirements' => 'Thank reviewer, acknowledge feedback, show commitment to care. 50-150 words. Maintain privacy.',
                'credits_cost' => 1,
            ],
            'email_follow_up' => [
                'name' => 'Follow-up Email',
                'description' => 'patient follow-up email',
                'requirements' => 'Include subject line, warm greeting, care reminder, general tips, appointment CTA. 150-250 words.',
                'credits_cost' => 1,
            ],
            'website_faq' => [
                'name' => 'Website FAQ',
                'description' => 'FAQ content',
                'requirements' => 'Create 5-8 Q&A pairs. Each answer 50-100 words. Informative but encourage professional consultation.',
                'credits_cost' => 1,
            ],
            'what_to_expect' => [
                'name' => 'What to Expect Guide',
                'description' => 'patient visit guide',
                'requirements' => 'Describe appointment flow, duration, preparation tips. 300-500 words. Friendly, step-by-step format.',
                'credits_cost' => 1,
            ],
            'university_lecture' => [
                'name' => 'University Lecture',
                'description' => 'comprehensive university-level medical lecture',
                'requirements' => $this->getUniversityLectureRequirements(),
                'credits_cost' => 3,
            ],
        ];
        
        return $defaults[$contentType] ?? $defaults['patient_education'];
    }
    
    /**
     * Validate generated content against global rules
     */
    public function validateContent(string $content): array
    {
        $violations = [];
        
        // Check for prohibited patterns
        $patterns = [
            'diagnostic' => ['/\b(diagnosed with|you have|this is)\b/i', 'Contains diagnostic language'],
            'prescriptions' => ['/\b(take|prescribed|dosage|mg|ml)\b/i', 'Contains prescription/medication language'],
            'medical_advice' => ['/\b(should take|must take|recommended dose)\b/i', 'Contains medical advice'],
            'treatment_instructions' => ['/\b(treatment plan|follow this protocol)\b/i', 'Contains treatment instructions'],
        ];
        
        foreach ($patterns as $type => $pattern) {
            if (preg_match($pattern[0], $content)) {
                $violations[] = [
                    'type' => $type,
                    'message' => $pattern[1],
                    'severity' => 'high'
                ];
            }
        }
        
        // Check for disclaimer presence (in any supported language)
        $hasDisclaimer = false;
        foreach ($this->globalRules['mandatory_disclaimers'] as $disclaimer) {
            if (stripos($content, substr($disclaimer, 0, 50)) !== false) {
                $hasDisclaimer = true;
                break;
            }
        }
        
        if (!$hasDisclaimer) {
            $violations[] = [
                'type' => 'missing_disclaimer',
                'message' => 'Content missing mandatory medical disclaimer',
                'severity' => 'high'
            ];
        }
        
        return [
            'valid' => empty($violations),
            'violations' => $violations,
            'checked_at' => now()->toIso8601String()
        ];
    }
    
    /**
     * Get language name
     */
    protected function getLanguageName(string $code): string
    {
        $languages = config('languages');
        return $languages[$code]['display'] ?? $code;
    }
    
    /**
     * Get default global rules
     */
    protected function getDefaultGlobalRules(): array
    {
        return [
            'content_restrictions' => [
                'non_diagnostic' => true,
                'no_medical_advice' => true,
                'no_prescriptions' => true,
            ],
            'mandatory_disclaimers' => [
                'en' => 'This content is for educational purposes only and does not replace professional medical consultation.'
            ],
            'supported_languages' => array_keys(config('languages', [])),
        ];
    }
}