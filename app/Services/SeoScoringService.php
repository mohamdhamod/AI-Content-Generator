<?php

namespace App\Services;

use App\Models\GeneratedContent;
use Illuminate\Support\Str;

class SeoScoringService
{
    /**
     * Calculate comprehensive SEO score for content
     *
     * @param GeneratedContent $content
     * @param array $options (target_keyword, meta_description, focus_keywords)
     * @return array
     */
    public function calculateScore(GeneratedContent $content, array $options = []): array
    {
        $text = $content->output_text;
        $targetKeyword = $options['target_keyword'] ?? null;
        $metaDescription = $options['meta_description'] ?? $this->extractFirstParagraph($text);
        $focusKeywords = $options['focus_keywords'] ?? [];

        $scores = [
            'content_length' => $this->scoreContentLength($text),
            'readability' => $this->scoreReadability($text),
            'keyword_density' => $this->scoreKeywordDensity($text, $targetKeyword),
            'headings_structure' => $this->scoreHeadingsStructure($text),
            'meta_description' => $this->scoreMetaDescription($metaDescription),
            'keyword_placement' => $this->scoreKeywordPlacement($text, $targetKeyword),
            'content_uniqueness' => $this->scoreContentUniqueness($text),
            'medical_terminology' => $this->scoreMedicalTerminology($text),
        ];

        $overallScore = $this->calculateOverallScore($scores);

        return [
            'overall_score' => $overallScore,
            'grade' => $this->getGrade($overallScore),
            'scores' => $scores,
            'recommendations' => $this->generateRecommendations($scores, $text, $targetKeyword),
            'statistics' => $this->getContentStatistics($text, $targetKeyword),
        ];
    }

    /**
     * Score content length (300-2500 words optimal for medical content)
     */
    protected function scoreContentLength(string $text): array
    {
        $wordCount = str_word_count(strip_tags($text));
        
        if ($wordCount < 300) {
            $score = ($wordCount / 300) * 50; // Max 50 if under 300
            $status = 'too_short';
            $message = "Content is too short ({$wordCount} words). Aim for 300-2500 words.";
        } elseif ($wordCount >= 300 && $wordCount <= 2500) {
            $score = 100; // Perfect range
            $status = 'optimal';
            $message = "Excellent content length ({$wordCount} words).";
        } elseif ($wordCount > 2500 && $wordCount <= 3500) {
            $score = 90; // Still good
            $status = 'good';
            $message = "Good content length ({$wordCount} words), but consider breaking into parts.";
        } else {
            $score = 70; // Too long
            $status = 'too_long';
            $message = "Content is very long ({$wordCount} words). Consider breaking into series.";
        }

        return [
            'score' => round($score),
            'status' => $status,
            'message' => $message,
            'word_count' => $wordCount,
        ];
    }

    /**
     * Score readability (Flesch Reading Ease adapted for medical content)
     */
    protected function scoreReadability(string $text): array
    {
        $text = strip_tags($text);
        $sentences = preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $sentenceCount = count($sentences);
        $wordCount = str_word_count($text);
        $syllableCount = $this->countSyllables($text);

        if ($sentenceCount === 0 || $wordCount === 0) {
            return [
                'score' => 0,
                'status' => 'error',
                'message' => 'Unable to calculate readability.',
                'level' => 'unknown',
            ];
        }

        // Flesch Reading Ease Score
        $avgWordsPerSentence = $wordCount / $sentenceCount;
        $avgSyllablesPerWord = $syllableCount / $wordCount;
        
        $fleschScore = 206.835 - (1.015 * $avgWordsPerSentence) - (84.6 * $avgSyllablesPerWord);
        $fleschScore = max(0, min(100, $fleschScore));

        // For medical content, 40-60 is acceptable (college level)
        if ($fleschScore >= 40 && $fleschScore <= 60) {
            $score = 100;
            $status = 'optimal';
            $level = 'college_level';
            $message = 'Readability is optimal for medical content (college level).';
        } elseif ($fleschScore > 60 && $fleschScore <= 80) {
            $score = 90;
            $status = 'good';
            $level = 'easy';
            $message = 'Content is very readable (high school to college level).';
        } elseif ($fleschScore > 80) {
            $score = 85;
            $status = 'very_easy';
            $level = 'elementary';
            $message = 'Content is extremely easy to read. Good for patient education.';
        } elseif ($fleschScore >= 20 && $fleschScore < 40) {
            $score = 80;
            $status = 'difficult';
            $level = 'professional';
            $message = 'Content is moderately difficult. Suitable for healthcare professionals.';
        } else {
            $score = 60;
            $status = 'very_difficult';
            $level = 'academic';
            $message = 'Content is very difficult. Consider simplifying for broader audience.';
        }

        return [
            'score' => round($score),
            'status' => $status,
            'message' => $message,
            'flesch_score' => round($fleschScore, 1),
            'level' => $level,
            'avg_words_per_sentence' => round($avgWordsPerSentence, 1),
        ];
    }

    /**
     * Score keyword density (1-3% optimal)
     */
    protected function scoreKeywordDensity(string $text, ?string $keyword): array
    {
        if (!$keyword) {
            return [
                'score' => 50,
                'status' => 'no_keyword',
                'message' => 'No target keyword specified.',
                'density' => 0,
                'count' => 0,
            ];
        }

        $text = strip_tags(strtolower($text));
        $keyword = strtolower($keyword);
        $wordCount = str_word_count($text);
        
        $keywordCount = substr_count($text, $keyword);
        $density = ($wordCount > 0) ? ($keywordCount / $wordCount) * 100 : 0;

        if ($density >= 1 && $density <= 3) {
            $score = 100;
            $status = 'optimal';
            $message = "Keyword density is optimal ({$density}%).";
        } elseif ($density > 0.5 && $density < 1) {
            $score = 80;
            $status = 'low';
            $message = "Keyword density is low ({$density}%). Consider adding more mentions.";
        } elseif ($density > 3 && $density <= 5) {
            $score = 70;
            $status = 'high';
            $message = "Keyword density is high ({$density}%). Risk of keyword stuffing.";
        } elseif ($density > 5) {
            $score = 40;
            $status = 'too_high';
            $message = "Keyword density is too high ({$density}%). Definite keyword stuffing.";
        } else {
            $score = 50;
            $status = 'very_low';
            $message = "Keyword density is very low ({$density}%). Add target keyword.";
        }

        return [
            'score' => round($score),
            'status' => $status,
            'message' => $message,
            'density' => round($density, 2),
            'count' => $keywordCount,
        ];
    }

    /**
     * Score headings structure (H1, H2, H3 hierarchy)
     */
    protected function scoreHeadingsStructure(string $text): array
    {
        $h1Count = preg_match_all('/<h1[^>]*>/i', $text);
        $h2Count = preg_match_all('/<h2[^>]*>/i', $text);
        $h3Count = preg_match_all('/<h3[^>]*>/i', $text);
        $totalHeadings = $h1Count + $h2Count + $h3Count;

        // Check for markdown headings too
        if ($totalHeadings === 0) {
            $h1Count = preg_match_all('/^# /m', $text);
            $h2Count = preg_match_all('/^## /m', $text);
            $h3Count = preg_match_all('/^### /m', $text);
            $totalHeadings = $h1Count + $h2Count + $h3Count;
        }

        if ($totalHeadings === 0) {
            return [
                'score' => 30,
                'status' => 'no_headings',
                'message' => 'No headings found. Add structure with H1, H2, H3 tags.',
                'h1_count' => 0,
                'h2_count' => 0,
                'h3_count' => 0,
            ];
        }

        $score = 50; // Base score

        // One H1 is ideal
        if ($h1Count === 1) {
            $score += 20;
        } elseif ($h1Count === 0) {
            $score -= 10;
        }

        // Multiple H2s are good
        if ($h2Count >= 2 && $h2Count <= 10) {
            $score += 30;
        } elseif ($h2Count > 0) {
            $score += 15;
        }

        // H3s provide depth
        if ($h3Count > 0) {
            $score += 10;
        }

        $score = min(100, max(0, $score));

        return [
            'score' => round($score),
            'status' => $score >= 80 ? 'optimal' : ($score >= 60 ? 'good' : 'needs_improvement'),
            'message' => $this->getHeadingsMessage($h1Count, $h2Count, $h3Count),
            'h1_count' => $h1Count,
            'h2_count' => $h2Count,
            'h3_count' => $h3Count,
            'total_headings' => $totalHeadings,
        ];
    }

    /**
     * Score meta description (150-160 characters optimal)
     */
    protected function scoreMetaDescription(string $description): array
    {
        $length = strlen($description);

        if ($length === 0) {
            return [
                'score' => 0,
                'status' => 'missing',
                'message' => 'Meta description is missing.',
                'length' => 0,
            ];
        }

        if ($length >= 150 && $length <= 160) {
            $score = 100;
            $status = 'optimal';
            $message = 'Meta description length is perfect.';
        } elseif ($length >= 130 && $length < 150) {
            $score = 90;
            $status = 'good';
            $message = 'Meta description is good but could be longer.';
        } elseif ($length > 160 && $length <= 200) {
            $score = 85;
            $status = 'too_long';
            $message = 'Meta description may be truncated in search results.';
        } elseif ($length < 130) {
            $score = 70;
            $status = 'too_short';
            $message = 'Meta description is too short. Aim for 150-160 characters.';
        } else {
            $score = 60;
            $status = 'way_too_long';
            $message = 'Meta description is too long and will be truncated.';
        }

        return [
            'score' => round($score),
            'status' => $status,
            'message' => $message,
            'length' => $length,
            'optimal_range' => '150-160 characters',
        ];
    }

    /**
     * Score keyword placement (in first paragraph, headings)
     */
    protected function scoreKeywordPlacement(string $text, ?string $keyword): array
    {
        if (!$keyword) {
            return [
                'score' => 50,
                'status' => 'no_keyword',
                'message' => 'No target keyword to analyze placement.',
                'in_first_paragraph' => false,
                'in_headings' => false,
            ];
        }

        $text = strip_tags($text);
        $keyword = strtolower($keyword);
        $firstParagraph = $this->extractFirstParagraph($text);
        $headings = $this->extractHeadings($text);

        $inFirstParagraph = stripos(strtolower($firstParagraph), $keyword) !== false;
        $inHeadings = false;
        
        foreach ($headings as $heading) {
            if (stripos(strtolower($heading), $keyword) !== false) {
                $inHeadings = true;
                break;
            }
        }

        $score = 0;
        if ($inFirstParagraph) $score += 50;
        if ($inHeadings) $score += 50;

        return [
            'score' => $score,
            'status' => $score >= 80 ? 'excellent' : ($score >= 50 ? 'good' : 'needs_improvement'),
            'message' => $this->getKeywordPlacementMessage($inFirstParagraph, $inHeadings),
            'in_first_paragraph' => $inFirstParagraph,
            'in_headings' => $inHeadings,
        ];
    }

    /**
     * Score content uniqueness (simplified check for duplicate phrases)
     */
    protected function scoreContentUniqueness(string $text): array
    {
        // This is a simplified check. For production, integrate with Copyscape API
        $text = strip_tags($text);
        $sentences = preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        // Check for repetitive sentences
        $uniqueSentences = array_unique(array_map('trim', $sentences));
        $uniquenessRatio = count($uniqueSentences) / max(count($sentences), 1);
        
        $score = $uniquenessRatio * 100;

        return [
            'score' => round($score),
            'status' => $score >= 95 ? 'excellent' : ($score >= 85 ? 'good' : 'needs_review'),
            'message' => $score >= 95 ? 'Content appears highly unique.' : 'Some repetitive content detected.',
            'uniqueness_ratio' => round($uniquenessRatio * 100, 1),
        ];
    }

    /**
     * Score medical terminology accuracy and usage
     */
    protected function scoreMedicalTerminology(string $text): array
    {
        // Medical keywords that should appear in medical content
        $medicalTerms = [
            'patient', 'treatment', 'diagnosis', 'symptom', 'therapy', 'doctor', 
            'medical', 'health', 'clinical', 'disease', 'condition', 'care',
            'procedure', 'medication', 'chronic', 'acute', 'syndrome'
        ];

        $text = strtolower(strip_tags($text));
        $foundTerms = 0;
        
        foreach ($medicalTerms as $term) {
            if (stripos($text, $term) !== false) {
                $foundTerms++;
            }
        }

        $score = min(100, ($foundTerms / count($medicalTerms)) * 200);

        return [
            'score' => round($score),
            'status' => $score >= 70 ? 'professional' : ($score >= 50 ? 'adequate' : 'needs_improvement'),
            'message' => $this->getMedicalTerminologyMessage($foundTerms),
            'terms_found' => $foundTerms,
            'terms_total' => count($medicalTerms),
        ];
    }

    /**
     * Calculate overall SEO score (weighted average)
     */
    protected function calculateOverallScore(array $scores): int
    {
        $weights = [
            'content_length' => 0.15,
            'readability' => 0.15,
            'keyword_density' => 0.20,
            'headings_structure' => 0.15,
            'meta_description' => 0.10,
            'keyword_placement' => 0.15,
            'content_uniqueness' => 0.05,
            'medical_terminology' => 0.05,
        ];

        $totalScore = 0;
        foreach ($scores as $key => $scoreData) {
            $totalScore += ($scoreData['score'] ?? 0) * ($weights[$key] ?? 0);
        }

        return round($totalScore);
    }

    /**
     * Get letter grade from score
     */
    protected function getGrade(int $score): string
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'F';
    }

    /**
     * Generate actionable recommendations
     */
    protected function generateRecommendations(array $scores, string $text, ?string $keyword): array
    {
        $recommendations = [];

        // Content length recommendations
        if ($scores['content_length']['score'] < 80) {
            $recommendations[] = [
                'priority' => 'high',
                'category' => 'content_length',
                'message' => $scores['content_length']['message'],
                'action' => 'Expand content with more details, examples, or sections.',
            ];
        }

        // Keyword recommendations
        if ($keyword && $scores['keyword_density']['score'] < 70) {
            $recommendations[] = [
                'priority' => 'high',
                'category' => 'keywords',
                'message' => 'Improve keyword usage',
                'action' => "Add more natural mentions of '{$keyword}' throughout the content.",
            ];
        }

        // Headings recommendations
        if ($scores['headings_structure']['score'] < 70) {
            $recommendations[] = [
                'priority' => 'medium',
                'category' => 'structure',
                'message' => 'Improve content structure',
                'action' => 'Add clear H2 and H3 headings to organize your content.',
            ];
        }

        // Readability recommendations
        if ($scores['readability']['score'] < 70) {
            $recommendations[] = [
                'priority' => 'medium',
                'category' => 'readability',
                'message' => 'Improve readability',
                'action' => 'Use shorter sentences and simpler words where possible.',
            ];
        }

        return $recommendations;
    }

    /**
     * Get content statistics
     */
    protected function getContentStatistics(string $text, ?string $keyword): array
    {
        $text = strip_tags($text);
        
        return [
            'word_count' => str_word_count($text),
            'character_count' => strlen($text),
            'sentence_count' => count(preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY)),
            'paragraph_count' => count(array_filter(explode("\n\n", $text))),
            'reading_time_minutes' => ceil(str_word_count($text) / 200), // 200 WPM average
            'keyword_mentions' => $keyword ? substr_count(strtolower($text), strtolower($keyword)) : 0,
        ];
    }

    // Helper methods
    protected function countSyllables(string $text): int
    {
        $text = strtolower(preg_replace('/[^a-z]/i', ' ', $text));
        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $syllableCount = 0;

        foreach ($words as $word) {
            $syllableCount += $this->countWordSyllables($word);
        }

        return $syllableCount;
    }

    protected function countWordSyllables(string $word): int
    {
        $word = strtolower($word);
        if (strlen($word) <= 3) return 1;

        $word = preg_replace('/(?:[^laeiouy]es|ed|[^laeiouy]e)$/', '', $word);
        $word = preg_replace('/^y/', '', $word);
        
        $syllables = preg_match_all('/[aeiouy]{1,2}/', $word);
        return max(1, $syllables);
    }

    protected function extractFirstParagraph(string $text): string
    {
        $text = strip_tags($text);
        $paragraphs = array_filter(explode("\n", $text));
        return $paragraphs[0] ?? '';
    }

    protected function extractHeadings(string $text): array
    {
        $headings = [];
        
        // HTML headings
        preg_match_all('/<h[1-6][^>]*>(.*?)<\/h[1-6]>/i', $text, $matches);
        $headings = array_merge($headings, $matches[1] ?? []);
        
        // Markdown headings
        preg_match_all('/^#{1,6}\s+(.+)$/m', $text, $matches);
        $headings = array_merge($headings, $matches[1] ?? []);
        
        return $headings;
    }

    protected function getHeadingsMessage(int $h1, int $h2, int $h3): string
    {
        if ($h1 === 0) return 'Add a main H1 heading for the page title.';
        if ($h1 > 1) return 'Multiple H1 tags found. Use only one H1 per page.';
        if ($h2 === 0) return 'Add H2 subheadings to structure your content.';
        if ($h2 >= 2 && $h2 <= 10) return 'Excellent heading structure.';
        return 'Good heading structure with room for improvement.';
    }

    protected function getKeywordPlacementMessage(bool $inFirst, bool $inHeadings): string
    {
        if ($inFirst && $inHeadings) return 'Excellent keyword placement in first paragraph and headings.';
        if ($inFirst) return 'Good: keyword in first paragraph. Try adding to headings.';
        if ($inHeadings) return 'Good: keyword in headings. Try adding to first paragraph.';
        return 'Add target keyword to first paragraph and at least one heading.';
    }

    protected function getMedicalTerminologyMessage(int $foundTerms): string
    {
        if ($foundTerms >= 10) return 'Excellent use of medical terminology.';
        if ($foundTerms >= 5) return 'Good medical terminology usage.';
        return 'Consider adding more professional medical terms.';
    }
}
