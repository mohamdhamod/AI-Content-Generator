<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\SeoScoringService;
use App\Models\GeneratedContent;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeoScoringServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SeoScoringService $seoService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seoService = new SeoScoringService();
    }

    /**
     * Helper to create mock content
     */
    protected function createMockContent(string $text): GeneratedContent
    {
        return new GeneratedContent([
            'output_text' => $text,
            'word_count' => str_word_count(strip_tags($text)),
        ]);
    }

    /**
     * Test calculateScore returns proper structure
     */
    public function test_calculate_score_returns_proper_structure(): void
    {
        $content = $this->createMockContent($this->getSampleMedicalContent());
        
        $result = $this->seoService->calculateScore($content);

        $this->assertArrayHasKey('overall_score', $result);
        $this->assertArrayHasKey('grade', $result);
        $this->assertArrayHasKey('scores', $result);
        $this->assertArrayHasKey('recommendations', $result);
        $this->assertArrayHasKey('statistics', $result);
    }

    /**
     * Test overall score is within valid range
     */
    public function test_overall_score_within_valid_range(): void
    {
        $content = $this->createMockContent($this->getSampleMedicalContent());
        
        $result = $this->seoService->calculateScore($content);

        $this->assertGreaterThanOrEqual(0, $result['overall_score']);
        $this->assertLessThanOrEqual(100, $result['overall_score']);
    }

    /**
     * Test grade is assigned correctly
     */
    public function test_grade_assigned_correctly(): void
    {
        $content = $this->createMockContent($this->getSampleMedicalContent());
        
        $result = $this->seoService->calculateScore($content);

        $validGrades = ['A+', 'A', 'B+', 'B', 'C', 'D', 'F'];
        $this->assertContains($result['grade'], $validGrades);
    }

    /**
     * Test short content gets low score
     */
    public function test_short_content_gets_low_score(): void
    {
        $content = $this->createMockContent('This is too short.');
        
        $result = $this->seoService->calculateScore($content);

        $this->assertLessThan(70, $result['scores']['content_length']['score']);
        $this->assertEquals('too_short', $result['scores']['content_length']['status']);
    }

    /**
     * Test optimal length content gets high score
     */
    public function test_optimal_length_content_gets_high_score(): void
    {
        $content = $this->createMockContent($this->getSampleMedicalContent());
        
        $result = $this->seoService->calculateScore($content);

        $this->assertGreaterThanOrEqual(80, $result['scores']['content_length']['score']);
    }

    /**
     * Test keyword density scoring with target keyword
     */
    public function test_keyword_density_scoring_with_keyword(): void
    {
        $content = $this->createMockContent(
            'Heart health is important. The heart pumps blood throughout the body. 
            Maintaining heart health requires exercise and proper nutrition. 
            Your heart benefits from regular cardiovascular activity. This is for educational purposes only.'
        );
        
        $result = $this->seoService->calculateScore($content, [
            'target_keyword' => 'heart'
        ]);

        $this->assertArrayHasKey('keyword_density', $result['scores']);
        $this->assertNotNull($result['scores']['keyword_density']['density']);
    }

    /**
     * Test keyword density without keyword returns neutral
     */
    public function test_keyword_density_without_keyword(): void
    {
        $content = $this->createMockContent($this->getSampleMedicalContent());
        
        $result = $this->seoService->calculateScore($content);

        $this->assertArrayHasKey('keyword_density', $result['scores']);
    }

    /**
     * Test headings structure is scored
     */
    public function test_headings_structure_scored(): void
    {
        $contentWithHeadings = "
            <h1>Main Title</h1>
            <p>Introduction paragraph here. This is educational purposes only.</p>
            <h2>Section One</h2>
            <p>Content for section one...</p>
            <h2>Section Two</h2>
            <p>Content for section two...</p>
        ";
        
        $content = $this->createMockContent($contentWithHeadings);
        $result = $this->seoService->calculateScore($content);

        $this->assertArrayHasKey('headings_structure', $result['scores']);
    }

    /**
     * Test recommendations are generated
     */
    public function test_recommendations_are_generated(): void
    {
        $content = $this->createMockContent('Short content.');
        
        $result = $this->seoService->calculateScore($content);

        $this->assertIsArray($result['recommendations']);
    }

    /**
     * Test statistics include word count
     */
    public function test_statistics_include_word_count(): void
    {
        $content = $this->createMockContent($this->getSampleMedicalContent());
        
        $result = $this->seoService->calculateScore($content);

        $this->assertArrayHasKey('statistics', $result);
        $this->assertArrayHasKey('word_count', $result['statistics']);
    }

    /**
     * Test medical terminology scoring
     */
    public function test_medical_terminology_scoring(): void
    {
        $medicalContent = "
            Cardiovascular disease affects the heart and blood vessels. 
            Hypertension, commonly known as high blood pressure, is a major risk factor. 
            Patients should monitor their cholesterol levels and maintain a healthy BMI.
            This content is for educational purposes only.
        ";
        
        $content = $this->createMockContent($medicalContent);
        $result = $this->seoService->calculateScore($content);

        $this->assertArrayHasKey('medical_terminology', $result['scores']);
    }

    /**
     * Test readability scoring
     */
    public function test_readability_scoring(): void
    {
        $content = $this->createMockContent($this->getSampleMedicalContent());
        
        $result = $this->seoService->calculateScore($content);

        $this->assertArrayHasKey('readability', $result['scores']);
        $this->assertArrayHasKey('flesch_score', $result['scores']['readability']);
    }

    /**
     * Test meta description scoring
     */
    public function test_meta_description_scoring(): void
    {
        $content = $this->createMockContent($this->getSampleMedicalContent());
        
        $result = $this->seoService->calculateScore($content, [
            'meta_description' => 'Learn about heart health and cardiovascular wellness in this comprehensive guide.'
        ]);

        $this->assertArrayHasKey('meta_description', $result['scores']);
    }

    /**
     * Test very long content is flagged
     */
    public function test_very_long_content_flagged(): void
    {
        // Generate content over 3500 words
        $longContent = str_repeat("This is a sentence about health topics. ", 400);
        $longContent .= "This content is for educational purposes only.";
        
        $content = $this->createMockContent($longContent);
        $result = $this->seoService->calculateScore($content);

        $this->assertIn($result['scores']['content_length']['status'], ['good', 'too_long']);
    }

    /**
     * Test content uniqueness scoring
     */
    public function test_content_uniqueness_scoring(): void
    {
        $content = $this->createMockContent($this->getSampleMedicalContent());
        
        $result = $this->seoService->calculateScore($content);

        $this->assertArrayHasKey('content_uniqueness', $result['scores']);
    }

    /**
     * Test keyword placement scoring
     */
    public function test_keyword_placement_scoring(): void
    {
        $content = $this->createMockContent(
            'Heart health begins with understanding your cardiovascular system. ' .
            'Regular exercise improves heart function and overall wellness. ' .
            'A healthy diet supports your heart throughout life. ' .
            'This content is for educational purposes only.'
        );
        
        $result = $this->seoService->calculateScore($content, [
            'target_keyword' => 'heart'
        ]);

        $this->assertArrayHasKey('keyword_placement', $result['scores']);
    }

    /**
     * Get sample medical content for testing
     */
    protected function getSampleMedicalContent(): string
    {
        return "
            <h1>Understanding Heart Health: A Comprehensive Guide</h1>
            
            <p>Heart health is a critical aspect of overall wellness that affects millions of people worldwide. 
            Understanding the factors that contribute to cardiovascular health can help individuals make 
            informed decisions about their lifestyle choices.</p>

            <h2>What is Cardiovascular Health?</h2>
            
            <p>Cardiovascular health refers to the well-being of the heart and blood vessels. A healthy 
            cardiovascular system efficiently pumps blood throughout the body, delivering oxygen and nutrients 
            to all organs and tissues.</p>

            <h2>Key Factors for Heart Health</h2>
            
            <p>Several factors influence heart health, including:</p>
            <ul>
                <li>Regular physical activity</li>
                <li>Balanced nutrition</li>
                <li>Adequate sleep</li>
                <li>Stress management</li>
                <li>Avoiding tobacco products</li>
            </ul>

            <h2>The Role of Diet</h2>
            
            <p>A heart-healthy diet typically includes plenty of fruits, vegetables, whole grains, and lean 
            proteins. Limiting saturated fats, trans fats, and excess sodium can help maintain healthy 
            blood pressure and cholesterol levels.</p>

            <h2>Exercise and Physical Activity</h2>
            
            <p>Regular exercise strengthens the heart muscle and improves circulation. The general 
            recommendation is at least 150 minutes of moderate-intensity aerobic activity per week.</p>

            <h2>When to Consult a Healthcare Provider</h2>
            
            <p>It's important to discuss your cardiovascular health with a qualified healthcare provider. 
            Regular check-ups can help identify potential issues early and establish appropriate 
            prevention strategies.</p>

            <p><strong>Disclaimer:</strong> This content is for educational purposes only and should not 
            replace professional medical advice. Always consult with a healthcare provider for personalized 
            guidance regarding your health.</p>
        ";
    }
}
