<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class GuardrailService
{
    /**
     * Forbidden words that indicate medical advice.
     */
    protected array $forbiddenWords = [
        'diagnose',
        'diagnosed',
        'diagnosis',
        'prescribe',
        'prescribed',
        'prescription',
        'dosage',
        'dose',
        'medication',
        'medications',
        'drug',
        'drugs',
        'treatment plan',
        'treatment protocol',
        'i recommend you take',
        'you should take',
        'take this medication',
        'this will cure',
        'guaranteed cure',
        'guaranteed to cure',
        'will definitely heal',
        'stop taking your medication',
        'instead of your doctor',
        'no need to see a doctor',
        'self-medicate',
        'self-diagnose',
    ];

    /**
     * Forbidden patterns (regex).
     */
    protected array $forbiddenPatterns = [
        '/take\s+\d+\s*(mg|ml|tablet|pill|capsule)/i',
        '/\d+\s*(mg|ml)\s+(of|daily|twice|three)/i',
        '/prescribed?\s+for\s+you/i',
        '/your\s+diagnosis\s+is/i',
        '/you\s+have\s+(been\s+)?diagnosed\s+with/i',
    ];

    /**
     * Required elements in output.
     */
    protected array $requiredElements = [
        'disclaimer' => 'educational purposes only',
    ];

    /**
     * Filter and validate generated content.
     */
    public function filter(string $content): array
    {
        $issues = [];
        $cleanContent = $content;
        $needsRegeneration = false;

        // Check for forbidden words
        foreach ($this->forbiddenWords as $word) {
            if (stripos($content, $word) !== false) {
                $issues[] = "Contains forbidden word: '{$word}'";
                // Try to remove/replace the problematic content
                $cleanContent = $this->sanitizeWord($cleanContent, $word);
            }
        }

        // Check for forbidden patterns
        foreach ($this->forbiddenPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                $issues[] = "Contains forbidden pattern matching: '{$pattern}'";
                $needsRegeneration = true;
            }
        }

        // Check for required elements
        foreach ($this->requiredElements as $name => $element) {
            if (stripos($content, $element) === false) {
                $issues[] = "Missing required element: '{$name}'";
                // Append disclaimer if missing
                if ($name === 'disclaimer') {
                    $cleanContent = $this->appendDisclaimer($cleanContent);
                }
            }
        }

        // Log issues for monitoring
        if (!empty($issues)) {
            Log::warning('Content guardrail issues detected', [
                'issues' => $issues,
                'needs_regeneration' => $needsRegeneration,
            ]);
        }

        return [
            'passed' => empty($issues) || !$needsRegeneration,
            'issues' => $issues,
            'needs_regeneration' => $needsRegeneration,
            'clean_content' => $cleanContent,
            'original_content' => $content,
        ];
    }

    /**
     * Validate input before sending to AI.
     */
    public function validateInput(array $input): array
    {
        $issues = [];

        // Check for potentially harmful requests
        $harmfulKeywords = [
            'how to diagnose',
            'what medication',
            'prescribe me',
            'dose for',
            'dosage for',
            'cure for',
            'treatment for my',
        ];

        $topic = strtolower($input['topic'] ?? '');

        foreach ($harmfulKeywords as $keyword) {
            if (str_contains($topic, $keyword)) {
                $issues[] = "Input contains potentially harmful request: '{$keyword}'";
            }
        }

        return [
            'valid' => empty($issues),
            'issues' => $issues,
        ];
    }

    /**
     * Sanitize a specific word from content.
     */
    protected function sanitizeWord(string $content, string $word): string
    {
        // Replace with safer alternatives
        $replacements = [
            'diagnose' => 'identify',
            'diagnosed' => 'identified',
            'diagnosis' => 'assessment',
            'prescribe' => 'recommend consulting your doctor about',
            'prescribed' => 'recommended by your healthcare provider',
            'prescription' => 'healthcare provider recommendation',
            'dosage' => 'amount recommended by your doctor',
            'medication' => 'treatment option',
            'medications' => 'treatment options',
        ];

        $replacement = $replacements[strtolower($word)] ?? '[consult your healthcare provider]';
        
        return preg_replace('/\b' . preg_quote($word, '/') . '\b/i', $replacement, $content);
    }

    /**
     * Append disclaimer to content.
     */
    protected function appendDisclaimer(string $content): string
    {
        $disclaimer = "\n\n---\n*Disclaimer: This content is for educational purposes only and does not replace professional medical consultation. Always consult with a qualified healthcare provider for medical advice, diagnosis, or treatment.*";
        
        return $content . $disclaimer;
    }

    /**
     * Get list of forbidden words (for admin panel).
     */
    public function getForbiddenWords(): array
    {
        return $this->forbiddenWords;
    }

    /**
     * Add a forbidden word.
     */
    public function addForbiddenWord(string $word): void
    {
        if (!in_array(strtolower($word), $this->forbiddenWords)) {
            $this->forbiddenWords[] = strtolower($word);
        }
    }
}
