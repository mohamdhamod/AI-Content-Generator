<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\CreditService;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class CreditServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CreditService $creditService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->creditService = new CreditService();
    }

    /**
     * Test free tier user has default credits
     */
    public function test_free_user_has_default_credits(): void
    {
        $user = User::factory()->create([
            'monthly_credits' => 5,
            'used_credits' => 0,
        ]);

        $remaining = $this->creditService->getRemainingCredits($user);
        
        $this->assertEquals(5, $remaining);
    }

    /**
     * Test user with used credits shows correct remaining
     */
    public function test_remaining_credits_calculated_correctly(): void
    {
        $user = User::factory()->create([
            'monthly_credits' => 10,
            'used_credits' => 3,
        ]);

        $remaining = $this->creditService->getRemainingCredits($user);
        
        $this->assertEquals(7, $remaining);
    }

    /**
     * Test hasEnoughCredits returns true when sufficient
     */
    public function test_has_enough_credits_returns_true_when_sufficient(): void
    {
        $user = User::factory()->create([
            'monthly_credits' => 10,
            'used_credits' => 5,
        ]);

        $this->assertTrue($this->creditService->hasEnoughCredits($user, 5));
        $this->assertTrue($this->creditService->hasEnoughCredits($user, 1));
    }

    /**
     * Test hasEnoughCredits returns false when insufficient
     */
    public function test_has_enough_credits_returns_false_when_insufficient(): void
    {
        $user = User::factory()->create([
            'monthly_credits' => 10,
            'used_credits' => 8,
        ]);

        $this->assertFalse($this->creditService->hasEnoughCredits($user, 5));
    }

    /**
     * Test deductCredits decrements user credits
     */
    public function test_deduct_credits_decrements_user_credits(): void
    {
        $user = User::factory()->create([
            'monthly_credits' => 10,
            'used_credits' => 0,
        ]);

        $result = $this->creditService->deductCredits($user, 3);
        $user->refresh();

        $this->assertTrue($result);
        $this->assertEquals(3, $user->used_credits);
    }

    /**
     * Test deductCredits fails when insufficient credits
     */
    public function test_deduct_credits_fails_when_insufficient(): void
    {
        $user = User::factory()->create([
            'monthly_credits' => 5,
            'used_credits' => 4,
        ]);

        $result = $this->creditService->deductCredits($user, 3);
        $user->refresh();

        $this->assertFalse($result);
        $this->assertEquals(4, $user->used_credits); // Should not change
    }

    /**
     * Test resetCredits sets used_credits to zero
     */
    public function test_reset_credits_sets_used_to_zero(): void
    {
        $user = User::factory()->create([
            'monthly_credits' => 100,
            'used_credits' => 75,
        ]);

        $result = $this->creditService->resetCredits($user);
        $user->refresh();

        $this->assertTrue($result);
        $this->assertEquals(0, $user->used_credits);
    }

    /**
     * Test getUsageStats returns correct structure
     */
    public function test_get_usage_stats_returns_correct_structure(): void
    {
        $user = User::factory()->create([
            'monthly_credits' => 100,
            'used_credits' => 25,
        ]);

        $stats = $this->creditService->getUsageStats($user);

        $this->assertArrayHasKey('total', $stats);
        $this->assertArrayHasKey('used', $stats);
        $this->assertArrayHasKey('remaining', $stats);
        $this->assertArrayHasKey('is_unlimited', $stats);
        $this->assertArrayHasKey('percentage_used', $stats);

        $this->assertEquals(100, $stats['total']);
        $this->assertEquals(25, $stats['used']);
        $this->assertEquals(75, $stats['remaining']);
        $this->assertFalse($stats['is_unlimited']);
        $this->assertEquals(25.0, $stats['percentage_used']);
    }

    /**
     * Test percentage calculation is correct
     */
    public function test_percentage_calculation(): void
    {
        $user = User::factory()->create([
            'monthly_credits' => 200,
            'used_credits' => 50,
        ]);

        $stats = $this->creditService->getUsageStats($user);

        $this->assertEquals(25.0, $stats['percentage_used']);
    }

    /**
     * Test remaining credits never goes negative
     */
    public function test_remaining_credits_never_negative(): void
    {
        $user = User::factory()->create([
            'monthly_credits' => 5,
            'used_credits' => 10, // More used than allowed (edge case)
        ]);

        $remaining = $this->creditService->getRemainingCredits($user);

        $this->assertEquals(0, $remaining);
        $this->assertGreaterThanOrEqual(0, $remaining);
    }
}
