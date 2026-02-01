<?php

namespace App\Services;

use App\Models\GeneratedContent;

class SocialMediaPreviewService
{
    /**
     * Generate social media preview for different platforms.
     *
     * @param GeneratedContent $content
     * @param string $platform facebook, twitter, linkedin, instagram
     * @return array
     */
    public function generatePreview(GeneratedContent $content, string $platform): array
    {
        $baseText = strip_tags($content->output_text);
        $language = $content->input_data['language'] ?? $content->language ?? 'English';
        $isRtl = $this->isRtlLanguage($language);
        
        return match($platform) {
            'facebook' => $this->generateFacebookPreview($content, $baseText, $isRtl),
            'twitter' => $this->generateTwitterPreview($content, $baseText, $isRtl),
            'linkedin' => $this->generateLinkedInPreview($content, $baseText, $isRtl),
            'instagram' => $this->generateInstagramPreview($content, $baseText, $isRtl),
            'tiktok' => $this->generateTikTokPreview($content, $baseText, $isRtl),
            default => throw new \InvalidArgumentException(__('translation.content_generator.social_media.unsupported_platform', ['platform' => $platform]))
        };
    }

    /**
     * Generate Facebook post preview.
     */
    protected function generateFacebookPreview(GeneratedContent $content, string $text, bool $isRtl = false): array
    {
        $topic = $content->input_data['topic'] ?? __('translation.content_generator.social_media.medical_content');
        $specialty = $content->specialty ? $content->specialty->name : __('translation.content_generator.social_media.health');
        
        // Facebook: Return full text - truncation handled by frontend
        $fullText = $this->cleanText($text);
        
        return [
            'platform' => 'facebook',
            'icon' => 'bi-facebook',
            'color' => '#1877f2',
            'max_length' => 63206,
            'recommended_length' => 500,
            'headline' => $this->createHeadline($topic, 60),
            'text' => $fullText,
            'current_length' => mb_strlen($fullText),
            'hashtags' => $this->generateHashtags($specialty, 5),
            'direction' => $isRtl ? 'rtl' : 'ltr',
            'text_align' => $isRtl ? 'right' : 'left',
            'cta' => [
                'text' => __('translation.content_generator.social_media.learn_more'),
                'type' => 'link',
            ],
            'best_practices' => [
                __('translation.content_generator.social_media.best_practices_tips.fb_visuals'),
                __('translation.content_generator.social_media.best_practices_tips.fb_questions'),
                __('translation.content_generator.social_media.best_practices_tips.fb_peak_hours'),
                __('translation.content_generator.social_media.best_practices_tips.fb_cta'),
                __('translation.content_generator.social_media.best_practices_tips.fb_paragraphs'),
            ],
        ];
    }

    /**
     * Generate Twitter/X post preview.
     */
    protected function generateTwitterPreview(GeneratedContent $content, string $text, bool $isRtl = false): array
    {
        $topic = $content->input_data['topic'] ?? __('translation.content_generator.social_media.medical_content');
        $specialty = $content->specialty ? $content->specialty->name : __('translation.content_generator.social_media.health');
        
        // Twitter/X: Return full text for preview, frontend will show character count
        // X Premium allows up to 25,000 characters, regular is 280
        $fullText = $this->cleanText($text);
        
        return [
            'platform' => 'twitter',
            'icon' => 'bi-twitter-x',
            'color' => '#000000',
            'max_length' => 280, // Standard limit
            'premium_max_length' => 25000, // X Premium limit
            'recommended_length' => 240,
            'text' => $fullText,
            'current_length' => mb_strlen($fullText),
            'hashtags' => $this->generateHashtags($specialty, 3),
            'direction' => $isRtl ? 'rtl' : 'ltr',
            'text_align' => $isRtl ? 'right' : 'left',
            'mentions' => [],
            'thread_suggestion' => mb_strlen($fullText) > 280 ? $this->suggestThread($text) : [],
            'best_practices' => [
                __('translation.content_generator.social_media.best_practices_tips.x_concise'),
                __('translation.content_generator.social_media.best_practices_tips.x_hashtags'),
                __('translation.content_generator.social_media.best_practices_tips.x_media'),
                __('translation.content_generator.social_media.best_practices_tips.x_peak_hours'),
                __('translation.content_generator.social_media.best_practices_tips.x_engage'),
            ],
        ];
    }

    /**
     * Generate LinkedIn post preview.
     */
    protected function generateLinkedInPreview(GeneratedContent $content, string $text, bool $isRtl = false): array
    {
        $topic = $content->input_data['topic'] ?? __('translation.content_generator.social_media.medical_content');
        $specialty = $content->specialty ? $content->specialty->name : __('translation.content_generator.social_media.health');
        
        // LinkedIn: Return full text for professional posts
        $fullText = $this->cleanText($text);
        
        return [
            'platform' => 'linkedin',
            'icon' => 'bi-linkedin',
            'color' => '#0a66c2',
            'max_length' => 3000,
            'recommended_length' => 1300,
            'headline' => $this->createHeadline($topic, 70),
            'text' => $fullText,
            'current_length' => mb_strlen($fullText),
            'hashtags' => $this->generateHashtags($specialty, 5, true),
            'direction' => $isRtl ? 'rtl' : 'ltr',
            'text_align' => $isRtl ? 'right' : 'left',
            'professional_tips' => [
                __('translation.content_generator.social_media.professional_tips.share_insights'),
                __('translation.content_generator.social_media.professional_tips.terminology'),
                __('translation.content_generator.social_media.professional_tips.credentials'),
                __('translation.content_generator.social_media.professional_tips.discussion'),
            ],
            'best_practices' => [
                __('translation.content_generator.social_media.best_practices_tips.li_weekdays'),
                __('translation.content_generator.social_media.best_practices_tips.li_line_breaks'),
                __('translation.content_generator.social_media.best_practices_tips.li_tag'),
                __('translation.content_generator.social_media.best_practices_tips.li_question'),
                __('translation.content_generator.social_media.best_practices_tips.li_hashtags'),
            ],
        ];
    }

    /**
     * Generate Instagram caption preview.
     */
    protected function generateInstagramPreview(GeneratedContent $content, string $text, bool $isRtl = false): array
    {
        $topic = $content->input_data['topic'] ?? __('translation.content_generator.social_media.medical_content');
        $specialty = $content->specialty ? $content->specialty->name : __('translation.content_generator.social_media.health');
        
        // Instagram: Return full text - truncation handled by frontend with "...more"
        $fullText = $this->cleanText($text);
        $hookText = $this->extractSummary($text, 100); // First line hook
        
        return [
            'platform' => 'instagram',
            'icon' => 'bi-instagram',
            'color' => '#e4405f',
            'max_length' => 2200,
            'recommended_length' => 1000,
            'hook' => $hookText,
            'text' => $fullText,
            'current_length' => mb_strlen($fullText),
            'direction' => $isRtl ? 'rtl' : 'ltr',
            'text_align' => $isRtl ? 'right' : 'left',
            'hashtags' => $this->generateHashtags($specialty, 15),
            'emoji_suggestions' => $this->suggestEmojis($specialty),
            'best_practices' => [
                __('translation.content_generator.social_media.best_practices_tips.ig_hook'),
                __('translation.content_generator.social_media.best_practices_tips.ig_hashtags'),
                __('translation.content_generator.social_media.best_practices_tips.ig_emojis'),
                __('translation.content_generator.social_media.best_practices_tips.ig_carousel'),
                __('translation.content_generator.social_media.best_practices_tips.ig_stories'),
                __('translation.content_generator.social_media.best_practices_tips.ig_peak_hours'),
            ],
        ];
    }

    /**
     * Generate TikTok video description preview.
     */
    protected function generateTikTokPreview(GeneratedContent $content, string $text, bool $isRtl = false): array
    {
        $topic = $content->input_data['topic'] ?? __('translation.content_generator.social_media.medical_content');
        $specialty = $content->specialty ? $content->specialty->name : __('translation.content_generator.social_media.health');
        
        // TikTok: Return full text for preview
        $fullText = $this->cleanText($text);
        $hookText = $this->extractSummary($text, 80); // Opening hook
        
        return [
            'platform' => 'tiktok',
            'icon' => 'bi-tiktok',
            'color' => '#000000',
            'max_length' => 2200,
            'recommended_length' => 150,
            'hook' => $hookText,
            'text' => $fullText,
            'current_length' => mb_strlen($fullText),
            'direction' => $isRtl ? 'rtl' : 'ltr',
            'text_align' => $isRtl ? 'right' : 'left',
            'hashtags' => $this->generateTikTokHashtags($specialty, 5),
            'trending_sounds' => $this->suggestTrendingSounds($specialty),
            'video_ideas' => $this->suggestVideoIdeas($specialty),
            'best_practices' => [
                __('translation.content_generator.social_media.best_practices_tips.tt_hook'),
                __('translation.content_generator.social_media.best_practices_tips.tt_length'),
                __('translation.content_generator.social_media.best_practices_tips.tt_sounds'),
                __('translation.content_generator.social_media.best_practices_tips.tt_captions'),
                __('translation.content_generator.social_media.best_practices_tips.tt_frequency'),
                __('translation.content_generator.social_media.best_practices_tips.tt_hashtags'),
            ],
        ];
    }

    /**
     * Generate TikTok-specific hashtags.
     */
    protected function generateTikTokHashtags(string $specialty, int $count): array
    {
        $tiktokHashtags = [
            'FYP', 'ForYou', 'Viral', 'LearnOnTikTok', 'TikTokTaughtMe',
            'MedTok', 'DoctorTikTok', 'NurseTok', 'HealthTok', 'MedicalTikTok'
        ];
        
        $specialtyTags = [
            'Cardiology' => ['HeartHealth', 'CardioCheck', 'HeartDoctor'],
            'Dermatology' => ['SkinTok', 'DermDoctor', 'SkinCareTips', 'GlowUp'],
            'Pediatrics' => ['MomTok', 'ParentingTips', 'BabyHealth', 'KidHealth'],
            'Orthopedics' => ['PhysioTok', 'JointHealth', 'BackPain', 'FitnessHealth'],
            'Neurology' => ['BrainHealth', 'NeuroTok', 'MentalHealth'],
            'Psychiatry' => ['MentalHealthMatters', 'TherapyTok', 'AnxietyTips'],
            'default' => ['HealthTips', 'MedicalFacts', 'DoctorAdvice'],
        ];

        $base = array_slice($tiktokHashtags, 0, 3);
        $specialty_specific = $specialtyTags[$specialty] ?? $specialtyTags['default'];
        
        $allTags = array_merge($base, $specialty_specific);
        $selectedTags = array_slice(array_unique($allTags), 0, $count);

        return array_map(fn($tag) => '#' . $tag, $selectedTags);
    }

    /**
     * Suggest trending sounds for TikTok.
     */
    protected function suggestTrendingSounds(string $specialty): array
    {
        return [
            __('translation.content_generator.social_media.trending_sounds.voiceover'),
            __('translation.content_generator.social_media.trending_sounds.educational'),
            __('translation.content_generator.social_media.trending_sounds.calm_music'),
            __('translation.content_generator.social_media.trending_sounds.upbeat_music'),
        ];
    }

    /**
     * Suggest video ideas for TikTok.
     */
    protected function suggestVideoIdeas(string $specialty): array
    {
        $specialtyKey = strtolower($specialty);
        $translationKey = 'translation.content_generator.social_media.video_ideas.' . $specialtyKey;
        
        // Check if specialty-specific ideas exist
        $ideas = __($translationKey);
        
        // If translation returns the key itself, use default
        if ($ideas === $translationKey || !is_array($ideas)) {
            $ideas = __('translation.content_generator.social_media.video_ideas.default');
        }
        
        return is_array($ideas) ? $ideas : [];
    }

    /**
     * Clean text by removing HTML tags and extra whitespace.
     */
    protected function cleanText(string $text): string
    {
        // Remove HTML tags if any
        $text = strip_tags($text);
        
        // Normalize whitespace but preserve intentional line breaks
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace('/\n\s*\n\s*\n/', "\n\n", $text);
        
        return trim($text);
    }

    /**
     * Extract summary from text.
     */
    protected function extractSummary(string $text, int $maxLength, bool $professional = false): string
    {
        // Split into sentences
        $sentences = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        if (empty($sentences)) {
            return '';
        }

        $summary = '';
        foreach ($sentences as $sentence) {
            $newLength = mb_strlen($summary) + mb_strlen($sentence) + 1;
            
            if ($newLength > $maxLength) {
                break;
            }
            
            $summary .= ($summary ? ' ' : '') . $sentence;
        }

        // If still empty or too short, use first N characters
        if (empty($summary) || mb_strlen($summary) < 50) {
            $summary = mb_substr($text, 0, $maxLength);
        }

        return trim($summary);
    }

    /**
     * Create headline from topic.
     */
    protected function createHeadline(string $topic, int $maxLength): string
    {
        $headline = trim($topic);
        
        if (mb_strlen($headline) > $maxLength) {
            $headline = mb_substr($headline, 0, $maxLength - 3) . '...';
        }

        return $headline;
    }

    /**
     * Generate relevant hashtags.
     */
    protected function generateHashtags(string $specialty, int $count, bool $professional = false): array
    {
        // Medical-grade hashtags with specialty-specific recommendations
        $commonHashtags = [
            'MedicalEducation', 'HealthcareInnovation', 'PatientCare', 'MedicalAdvice', 
            'HealthAndWellness', 'PreventiveMedicine', 'ClinicalExcellence', 'HealthyLiving',
            'MedicalKnowledge', 'HealthcareProfessionals'
        ];

        $professionalHashtags = [
            'MedEd', 'HealthTech', 'ClinicalResearch', 'EvidenceBasedMedicine',
            'MedicalScience', 'HealthcareQuality', 'PatientSafety', 'CME',
            'HealthcareLeadership', 'MedicalInnovation'
        ];

        // Specialty-specific hashtags with broader reach and medical precision
        $specialtyHashtags = [
            'Cardiology' => ['Cardiology', 'HeartHealth', 'CardiovascularDisease', 'CardiacCare', 'HeartDiseasePrevention', 'AtrialFibrillation', 'Hypertension', 'HeartFailure'],
            'Dermatology' => ['Dermatology', 'SkinHealth', 'Dermatologist', 'SkinCare', 'Acne', 'Eczema', 'Psoriasis', 'SkinCancer'],
            'Pediatrics' => ['Pediatrics', 'ChildHealth', 'Pediatrician', 'ChildDevelopment', 'ParentingTips', 'ChildWellness', 'VaccinesSaveLives'],
            'Orthopedics' => ['Orthopedics', 'BoneHealth', 'JointPain', 'SportsInjury', 'Arthritis', 'FractureRepair', 'Osteoporosis'],
            'Neurology' => ['Neurology', 'BrainHealth', 'Neurologist', 'Stroke', 'Epilepsy', 'MultipleSclerosis', 'Parkinsons', 'Alzheimers'],
            'Psychiatry' => ['Psychiatry', 'MentalHealth', 'MentalHealthAwareness', 'Depression', 'Anxiety', 'TherapyWorks', 'MentalWellbeing'],
            'Oncology' => ['Oncology', 'CancerAwareness', 'CancerCare', 'CancerPrevention', 'CancerResearch', 'Chemotherapy', 'FightCancer'],
            'Endocrinology' => ['Endocrinology', 'Diabetes', 'ThyroidHealth', 'Hormones', 'DiabetesCare', 'Type2Diabetes', 'Insulin'],
            'Gastroenterology' => ['Gastroenterology', 'DigestiveHealth', 'GutHealth', 'IBS', 'IBD', 'CrohnsDisease', 'Liver'],
            'Pulmonology' => ['Pulmonology', 'LungHealth', 'Asthma', 'COPD', 'RespiratoryHealth', 'Breathe', 'PulmonaryFibrosis'],
            'Nephrology' => ['Nephrology', 'KidneyHealth', 'KidneyDisease', 'Dialysis', 'RenalCare', 'CKD'],
            'Obstetrics' => ['OBGYN', 'WomensHealth', 'Pregnancy', 'PrenatalCare', 'Fertility', 'MaternalHealth'],
            'Emergency Medicine' => ['EmergencyMedicine', 'ER', 'TraumaRecovery', 'FirstAid', 'EmergencyCare', 'SaveLives'],
            'default' => ['MedicalAdvice', 'Healthcare', 'WellnessTips', 'HealthyLiving', 'PreventiveCare', 'MedicalInfo'],
        ];

        $base = $professional ? $professionalHashtags : $commonHashtags;
        $specialtyTags = $specialtyHashtags[$specialty] ?? $specialtyHashtags['default'];

        // Merge and shuffle for variety
        $allTags = array_merge($specialtyTags, $base);
        $allTags = array_unique($allTags);
        
        // Take requested count
        $selectedTags = array_slice($allTags, 0, min($count, count($allTags)));

        return array_map(fn($tag) => '#' . str_replace([' ', '-'], '', $tag), $selectedTags);
    }

    /**
     * Suggest emojis based on specialty.
     */
    protected function suggestEmojis(string $specialty): array
    {
        $emojiMap = [
            'Cardiology' => ['❤️', '🫀', '💓', '🩺'],
            'Dermatology' => ['🧴', '✨', '🌟', '💆'],
            'Pediatrics' => ['👶', '🍼', '👨‍👩‍👧', '🧸'],
            'Orthopedics' => ['🦴', '🦵', '🏃', '💪'],
            'Neurology' => ['🧠', '🎯', '💭', '🔬'],
            'default' => ['🏥', '⚕️', '🩺', '💊', '👨‍⚕️', '👩‍⚕️'],
        ];

        return $emojiMap[$specialty] ?? $emojiMap['default'];
    }

    /**
     * Suggest thread for Twitter.
     */
    protected function suggestThread(string $text): array
    {
        $sentences = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        $threads = [];
        $currentThread = '';
        $threadNumber = 1;

        foreach ($sentences as $sentence) {
            $newLength = mb_strlen($currentThread) + mb_strlen($sentence) + 1;
            
            if ($newLength > 240) { // Leave room for thread number
                if (!empty($currentThread)) {
                    $threads[] = "({$threadNumber}/{$threadNumber}) " . trim($currentThread);
                    $threadNumber++;
                }
                $currentThread = $sentence;
            } else {
                $currentThread .= ($currentThread ? ' ' : '') . $sentence;
            }
        }

        if (!empty($currentThread)) {
            $threads[] = "({$threadNumber}/{$threadNumber}) " . trim($currentThread);
        }

        // Update thread numbers to show total
        $total = count($threads);
        foreach ($threads as $i => $thread) {
            $threads[$i] = preg_replace('/\(\d+\/\d+\)/', '(' . ($i + 1) . '/' . $total . ')', $thread);
        }

        return array_slice($threads, 0, 5); // Max 5 tweets in thread
    }

    /**
     * Check if language is RTL (Right-to-Left).
     *
     * @param string $language
     * @return bool
     */
    protected function isRtlLanguage(string $language): bool
    {
        $rtlLanguages = ['arabic', 'ar', 'العربية', 'hebrew', 'he', 'urdu', 'ur', 'persian', 'fa'];
        return in_array(strtolower($language), $rtlLanguages);
    }
}
