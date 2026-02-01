<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\GuardrailService;

class GuardrailServiceTest extends TestCase
{
    protected GuardrailService $guardrailService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->guardrailService = new GuardrailService();
    }

    /**
     * Test clean content passes filter
     */
    public function test_clean_content_passes_filter(): void
    {
        $content = "This is educational content about heart health. 
                    Maintaining a balanced diet and regular exercise can support cardiovascular wellness.
                    This information is for educational purposes only.";

        $result = $this->guardrailService->filter($content);

        $this->assertTrue($result['passed']);
        $this->assertFalse($result['needs_regeneration']);
    }

    /**
     * Test content with forbidden word is flagged
     */
    public function test_content_with_forbidden_word_is_flagged(): void
    {
        $content = "Based on your symptoms, I diagnose you with hypertension.";

        $result = $this->guardrailService->filter($content);

        $this->assertNotEmpty($result['issues']);
        $this->assertStringContainsString('diagnose', implode(' ', $result['issues']));
    }

    /**
     * Test content with dosage pattern is flagged
     */
    public function test_content_with_dosage_pattern_is_flagged(): void
    {
        $content = "You should take 500 mg of aspirin daily for your condition.";

        $result = $this->guardrailService->filter($content);

        $this->assertTrue($result['needs_regeneration']);
        $this->assertNotEmpty($result['issues']);
    }

    /**
     * Test missing disclaimer is appended
     */
    public function test_missing_disclaimer_is_appended(): void
    {
        $content = "Heart disease is a serious condition that affects many people.";

        $result = $this->guardrailService->filter($content);

        $this->assertStringContainsString('educational purposes only', 
            strtolower($result['clean_content']));
    }

    /**
     * Test content with disclaimer passes
     */
    public function test_content_with_disclaimer_passes(): void
    {
        $content = "Heart health is important. This content is for educational purposes only.";

        $result = $this->guardrailService->filter($content);

        $this->assertTrue($result['passed']);
    }

    /**
     * Test prescription pattern is detected
     */
    public function test_prescription_pattern_is_detected(): void
    {
        $content = "This medication has been prescribed for you to manage your condition.";

        $result = $this->guardrailService->filter($content);

        $this->assertTrue($result['needs_regeneration']);
    }

    /**
     * Test validateInput catches harmful requests
     */
    public function test_validate_input_catches_harmful_requests(): void
    {
        $input = ['topic' => 'how to diagnose diabetes at home'];

        $result = $this->guardrailService->validateInput($input);

        $this->assertFalse($result['valid']);
        $this->assertNotEmpty($result['issues']);
    }

    /**
     * Test validateInput accepts safe requests
     */
    public function test_validate_input_accepts_safe_requests(): void
    {
        $input = ['topic' => 'Understanding diabetes prevention through lifestyle'];

        $result = $this->guardrailService->validateInput($input);

        $this->assertTrue($result['valid']);
        $this->assertEmpty($result['issues']);
    }

    /**
     * Test multiple forbidden words are all flagged
     */
    public function test_multiple_forbidden_words_flagged(): void
    {
        $content = "I diagnose you with flu. Here is your prescription for medication.";

        $result = $this->guardrailService->filter($content);

        $this->assertNotEmpty($result['issues']);
        // Should flag multiple issues
        $this->assertGreaterThan(1, count($result['issues']));
    }

    /**
     * Test self-medicate is flagged
     */
    public function test_self_medicate_is_flagged(): void
    {
        $content = "You can self-medicate with over-the-counter remedies.";

        $result = $this->guardrailService->filter($content);

        $this->assertNotEmpty($result['issues']);
    }

    /**
     * Test dosage with ml pattern is detected
     */
    public function test_dosage_ml_pattern_detected(): void
    {
        $content = "Take 10 ml of this syrup twice daily.";

        $result = $this->guardrailService->filter($content);

        $this->assertTrue($result['needs_regeneration']);
    }

    /**
     * Test original content is preserved in response
     */
    public function test_original_content_preserved(): void
    {
        $content = "Original content here.";

        $result = $this->guardrailService->filter($content);

        $this->assertEquals($content, $result['original_content']);
    }

    /**
     * Test guaranteed cure phrases are flagged
     */
    public function test_guaranteed_cure_flagged(): void
    {
        $content = "This supplement is a guaranteed cure for your ailments.";

        $result = $this->guardrailService->filter($content);

        $this->assertNotEmpty($result['issues']);
    }

    /**
     * Test safe educational content passes all checks
     */
    public function test_safe_educational_content_passes(): void
    {
        $content = "Understanding Heart Health: An Educational Guide

Heart disease remains one of the leading causes of concern in modern healthcare. 
By understanding the factors that contribute to heart health, individuals can make 
informed lifestyle choices.

Key areas to discuss with your healthcare provider include:
- Diet and nutrition
- Physical activity levels
- Stress management
- Regular health screenings

This information is for educational purposes only and should not replace 
professional medical advice. Always consult with your healthcare provider 
before making any health-related decisions.";

        $result = $this->guardrailService->filter($content);

        $this->assertTrue($result['passed']);
        $this->assertFalse($result['needs_regeneration']);
    }
}
