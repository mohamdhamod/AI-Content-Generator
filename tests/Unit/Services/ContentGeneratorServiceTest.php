<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ContentGeneratorService;
use App\Services\OpenAIService;
use App\Services\MedicalPromptService;
use App\Services\GuardrailService;
use App\Models\User;
use App\Models\ContentType;
use App\Models\GeneratedContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ContentGeneratorServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ContentGeneratorService $service;
    protected $mockOpenAI;
    protected $mockPromptService;
    protected $mockGuardrail;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create mocks
        $this->mockOpenAI = Mockery::mock(OpenAIService::class);
        $this->mockPromptService = Mockery::mock(MedicalPromptService::class);
        $this->mockGuardrail = Mockery::mock(GuardrailService::class);
        
        // Create service with mocks
        $this->service = new ContentGeneratorService(
            $this->mockOpenAI,
            $this->mockPromptService,
            $this->mockGuardrail
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test generation fails when OpenAI not configured
     */
    public function test_generation_fails_when_openai_not_configured(): void
    {
        $user = User::factory()->create();
        
        $this->mockOpenAI
            ->shouldReceive('isConfigured')
            ->once()
            ->andReturn(false);

        $result = $this->service->generate(
            $user,
            1,
            null,
            null,
            ['topic' => 'test'],
            'en'
        );

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('not configured', $result['error']);
    }

    /**
     * Test generation fails with invalid content type
     */
    public function test_generation_fails_with_invalid_content_type(): void
    {
        $user = User::factory()->create();
        
        $this->mockOpenAI
            ->shouldReceive('isConfigured')
            ->once()
            ->andReturn(true);

        $result = $this->service->generate(
            $user,
            99999, // Invalid ID
            null,
            null,
            ['topic' => 'test'],
            'en'
        );

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Invalid content type', $result['error']);
    }

    /**
     * Test generation fails with insufficient credits
     */
    public function test_generation_fails_with_insufficient_credits(): void
    {
        $user = User::factory()->create([
            'monthly_credits' => 5,
            'used_credits' => 5, // No credits left
        ]);

        $contentType = ContentType::factory()->create([
            'credits_cost' => 1,
        ]);

        $this->mockOpenAI
            ->shouldReceive('isConfigured')
            ->once()
            ->andReturn(true);

        $result = $this->service->generate(
            $user,
            $contentType->id,
            null,
            null,
            ['topic' => 'test'],
            'en'
        );

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Insufficient credits', $result['error']);
    }

    /**
     * Test generation fails with harmful input
     */
    public function test_generation_fails_with_harmful_input(): void
    {
        $user = User::factory()->create([
            'monthly_credits' => 100,
            'used_credits' => 0,
        ]);

        $contentType = ContentType::factory()->create([
            'credits_cost' => 1,
        ]);

        $this->mockOpenAI
            ->shouldReceive('isConfigured')
            ->once()
            ->andReturn(true);

        $this->mockGuardrail
            ->shouldReceive('validateInput')
            ->once()
            ->andReturn([
                'valid' => false,
                'issues' => ['Contains harmful content request'],
            ]);

        $result = $this->service->generate(
            $user,
            $contentType->id,
            null,
            null,
            ['topic' => 'how to diagnose cancer at home'],
            'en'
        );

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('cannot be processed', $result['error']);
    }

    /**
     * Test successful generation flow
     */
    public function test_successful_generation_flow(): void
    {
        $user = User::factory()->create([
            'monthly_credits' => 100,
            'used_credits' => 0,
        ]);

        $contentType = ContentType::factory()->create([
            'credits_cost' => 1,
            'key' => 'blog_post',
        ]);

        $this->mockOpenAI
            ->shouldReceive('isConfigured')
            ->once()
            ->andReturn(true);

        $this->mockGuardrail
            ->shouldReceive('validateInput')
            ->once()
            ->andReturn(['valid' => true, 'issues' => []]);

        $this->mockOpenAI
            ->shouldReceive('chat')
            ->once()
            ->andReturn([
                'success' => true,
                'content' => 'Generated content about heart health. This is for educational purposes only.',
                'tokens_used' => 500,
            ]);

        $this->mockGuardrail
            ->shouldReceive('filter')
            ->once()
            ->andReturn([
                'passed' => true,
                'issues' => [],
                'needs_regeneration' => false,
                'clean_content' => 'Generated content about heart health. This is for educational purposes only.',
            ]);

        $result = $this->service->generate(
            $user,
            $contentType->id,
            null,
            null,
            ['topic' => 'heart health', 'word_count' => 500],
            'en'
        );

        $this->assertTrue($result['success']);
        $this->assertNotEmpty($result['content']);
        $this->assertArrayHasKey('content_id', $result);
        $this->assertArrayHasKey('tokens_used', $result);
        $this->assertArrayHasKey('credits_used', $result);
    }

    /**
     * Test credits are deducted after successful generation
     */
    public function test_credits_deducted_after_successful_generation(): void
    {
        $user = User::factory()->create([
            'monthly_credits' => 100,
            'used_credits' => 10,
        ]);

        $contentType = ContentType::factory()->create([
            'credits_cost' => 2,
            'key' => 'blog_post',
        ]);

        $this->mockOpenAI->shouldReceive('isConfigured')->andReturn(true);
        $this->mockGuardrail->shouldReceive('validateInput')->andReturn(['valid' => true, 'issues' => []]);
        $this->mockOpenAI->shouldReceive('chat')->andReturn([
            'success' => true,
            'content' => 'Test content. Educational purposes only.',
            'tokens_used' => 100,
        ]);
        $this->mockGuardrail->shouldReceive('filter')->andReturn([
            'passed' => true,
            'issues' => [],
            'needs_regeneration' => false,
            'clean_content' => 'Test content. Educational purposes only.',
        ]);

        $this->service->generate(
            $user,
            $contentType->id,
            null,
            null,
            ['topic' => 'test'],
            'en'
        );

        $user->refresh();
        $this->assertEquals(12, $user->used_credits); // 10 + 2 credits
    }

    /**
     * Test generated content is saved to database
     */
    public function test_generated_content_saved_to_database(): void
    {
        $user = User::factory()->create([
            'monthly_credits' => 100,
            'used_credits' => 0,
        ]);

        $contentType = ContentType::factory()->create([
            'credits_cost' => 1,
            'key' => 'blog_post',
        ]);

        $this->mockOpenAI->shouldReceive('isConfigured')->andReturn(true);
        $this->mockGuardrail->shouldReceive('validateInput')->andReturn(['valid' => true, 'issues' => []]);
        $this->mockOpenAI->shouldReceive('chat')->andReturn([
            'success' => true,
            'content' => 'Saved content for testing. Educational purposes only.',
            'tokens_used' => 200,
        ]);
        $this->mockGuardrail->shouldReceive('filter')->andReturn([
            'passed' => true,
            'issues' => [],
            'needs_regeneration' => false,
            'clean_content' => 'Saved content for testing. Educational purposes only.',
        ]);

        $result = $this->service->generate(
            $user,
            $contentType->id,
            null,
            null,
            ['topic' => 'testing'],
            'en'
        );

        $this->assertDatabaseHas('generated_contents', [
            'user_id' => $user->id,
            'content_type_id' => $contentType->id,
            'status' => 'completed',
        ]);
    }

    /**
     * Test AI failure is handled gracefully
     */
    public function test_ai_failure_handled_gracefully(): void
    {
        $user = User::factory()->create([
            'monthly_credits' => 100,
            'used_credits' => 0,
        ]);

        $contentType = ContentType::factory()->create([
            'credits_cost' => 1,
            'key' => 'blog_post',
        ]);

        $this->mockOpenAI->shouldReceive('isConfigured')->andReturn(true);
        $this->mockGuardrail->shouldReceive('validateInput')->andReturn(['valid' => true, 'issues' => []]);
        $this->mockOpenAI->shouldReceive('chat')->andReturn([
            'success' => false,
            'error' => 'API rate limit exceeded',
            'content' => null,
        ]);

        $result = $this->service->generate(
            $user,
            $contentType->id,
            null,
            null,
            ['topic' => 'test'],
            'en'
        );

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Failed to generate', $result['error']);
    }

    /**
     * Test getUserHistory returns user's content
     */
    public function test_get_user_history_returns_content(): void
    {
        $user = User::factory()->create();
        $contentType = ContentType::factory()->create();

        // Create some content for the user
        GeneratedContent::factory()->count(5)->create([
            'user_id' => $user->id,
            'content_type_id' => $contentType->id,
            'status' => 'completed',
        ]);

        $history = $this->service->getUserHistory($user, 10);

        $this->assertCount(5, $history);
    }

    /**
     * Test getContent returns specific content for user
     */
    public function test_get_content_returns_specific_content(): void
    {
        $user = User::factory()->create();
        $contentType = ContentType::factory()->create();

        $content = GeneratedContent::factory()->create([
            'user_id' => $user->id,
            'content_type_id' => $contentType->id,
        ]);

        $result = $this->service->getContent($user, $content->id);

        $this->assertNotNull($result);
        $this->assertEquals($content->id, $result->id);
    }

    /**
     * Test getContent returns null for other user's content
     */
    public function test_get_content_returns_null_for_other_users_content(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $contentType = ContentType::factory()->create();

        $content = GeneratedContent::factory()->create([
            'user_id' => $user1->id,
            'content_type_id' => $contentType->id,
        ]);

        $result = $this->service->getContent($user2, $content->id);

        $this->assertNull($result);
    }
}
