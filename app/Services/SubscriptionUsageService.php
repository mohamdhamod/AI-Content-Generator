<?php

namespace App\Services;

use App\Models\ContentGeneration;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Log;

class SubscriptionUsageService
{
    /**
     * Check if user can generate more content based on their subscription.
     */
    public function canGenerate(User $user): array
    {
        $subscription = $user->activeSubscription;

        // No active subscription
        if (!$subscription) {
            return [
                'allowed' => false,
                'reason' => 'no_subscription',
                'message' => __('translation.subscription.no_active_subscription'),
            ];
        }

        // Check if subscription is expired
        if ($subscription->expires_at && $subscription->expires_at->isPast()) {
            return [
                'allowed' => false,
                'reason' => 'expired',
                'message' => __('translation.subscription.subscription_expired'),
            ];
        }

        $plan = $subscription->subscription;

        // Unlimited plan (-1)
        if ($plan && $plan->max_content_generations == -1) {
            return [
                'allowed' => true,
                'reason' => 'unlimited',
                'remaining' => -1,
                'message' => __('translation.subscription.unlimited_generations'),
            ];
        }

        // Count this month's generations
        $usedCount = $this->getMonthlyUsage($user->id);
        $maxAllowed = $plan?->max_content_generations ?? 0;
        $remaining = max(0, $maxAllowed - $usedCount);

        if ($remaining <= 0) {
            return [
                'allowed' => false,
                'reason' => 'limit_reached',
                'used' => $usedCount,
                'limit' => $maxAllowed,
                'remaining' => 0,
                'message' => __('translation.subscription.limit_reached'),
            ];
        }

        return [
            'allowed' => true,
            'reason' => 'within_limit',
            'used' => $usedCount,
            'limit' => $maxAllowed,
            'remaining' => $remaining,
            'message' => __('translation.subscription.generations_remaining', ['count' => $remaining]),
        ];
    }

    /**
     * Get the number of content generations for this month.
     */
    public function getMonthlyUsage(int $userId): int
    {
        return ContentGeneration::forUser($userId)
            ->thisMonth()
            ->completed()
            ->count();
    }

    /**
     * Record a content generation.
     */
    public function recordGeneration(User $user, array $data): ContentGeneration
    {
        $subscription = $user->activeSubscription;

        return ContentGeneration::create([
            'user_id' => $user->id,
            'user_subscription_id' => $subscription?->id,
            'content_type' => $data['content_type'] ?? 'unknown',
            'specialty' => $data['specialty'] ?? null,
            'language' => $data['language'] ?? 'en',
            'topic' => $data['topic'] ?? null,
            'prompt' => $data['prompt'] ?? null,
            'generated_content' => $data['generated_content'] ?? null,
            'tokens_used' => $data['tokens_used'] ?? 0,
            'model_used' => $data['model_used'] ?? null,
            'status' => $data['status'] ?? 'completed',
            'error_message' => $data['error_message'] ?? null,
            'metadata' => $data['metadata'] ?? null,
        ]);
    }

    /**
     * Get usage statistics for a user.
     */
    public function getUsageStats(User $user): array
    {
        $subscription = $user->activeSubscription;
        $plan = $subscription?->subscription;
        
        $monthlyUsage = $this->getMonthlyUsage($user->id);
        $maxAllowed = $plan?->max_content_generations ?? 0;
        $isUnlimited = $maxAllowed == -1;

        return [
            'subscription' => [
                'name' => $plan?->name ?? 'No Subscription',
                'expires_at' => $subscription?->expires_at?->format('Y-m-d'),
                'is_active' => $subscription?->isActive() ?? false,
            ],
            'usage' => [
                'this_month' => $monthlyUsage,
                'limit' => $isUnlimited ? 'unlimited' : $maxAllowed,
                'remaining' => $isUnlimited ? 'unlimited' : max(0, $maxAllowed - $monthlyUsage),
                'percentage' => $isUnlimited ? 0 : ($maxAllowed > 0 ? round(($monthlyUsage / $maxAllowed) * 100) : 0),
            ],
            'history' => [
                'total_all_time' => ContentGeneration::forUser($user->id)->completed()->count(),
                'by_content_type' => ContentGeneration::forUser($user->id)
                    ->completed()
                    ->thisMonth()
                    ->selectRaw('content_type, count(*) as count')
                    ->groupBy('content_type')
                    ->pluck('count', 'content_type')
                    ->toArray(),
            ],
        ];
    }
}
