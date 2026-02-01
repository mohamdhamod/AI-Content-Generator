<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Credit Service
 * 
 * Centralized service for managing user content generation credits.
 * Handles checking, deducting, and tracking credit usage.
 * 
 * @author Senior Laravel Architect
 */
class CreditService
{
    /**
     * Check if user has enough credits for content generation
     *
     * @param User $user
     * @param int $required Number of credits required (default: 1)
     * @return bool
     */
    public function hasEnoughCredits(User $user, int $required = 1): bool
    {
        $remaining = $this->getRemainingCredits($user);
        return $remaining >= $required || $remaining === -1; // -1 means unlimited
    }

    /**
     * Deduct credits from user account
     *
     * @param User $user
     * @param int $amount Amount to deduct (default: 1)
     * @return bool Success status
     */
    public function deductCredits(User $user, int $amount = 1): bool
    {
        if (!$this->hasEnoughCredits($user, $amount)) {
            Log::warning('Credit deduction failed: insufficient credits', [
                'user_id' => $user->id,
                'required' => $amount,
                'available' => $this->getRemainingCredits($user),
            ]);
            return false;
        }

        // If user has unlimited credits, no need to deduct
        if ($this->isUnlimited($user)) {
            return true;
        }

        $user->increment('used_credits', $amount);
        
        Log::info('Credits deducted', [
            'user_id' => $user->id,
            'amount' => $amount,
            'remaining' => $this->getRemainingCredits($user),
        ]);

        return true;
    }

    /**
     * Get remaining credits for user
     *
     * @param User $user
     * @return int Remaining credits (-1 for unlimited)
     */
    public function getRemainingCredits(User $user): int
    {
        $subscription = $user->activeSubscription;
        
        if (!$subscription) {
            // Free tier: use default credits
            $maxCredits = $user->monthly_credits ?? config('app.default_monthly_credits', 5);
            $usedCredits = $user->used_credits ?? 0;
            return max(0, $maxCredits - $usedCredits);
        }

        $maxGenerations = $subscription->subscription->max_content_generations ?? 0;
        
        // -1 or very high number means unlimited
        if ($maxGenerations < 0 || $maxGenerations > 100000) {
            return -1; // Unlimited
        }

        $usedCredits = $user->used_credits ?? 0;
        return max(0, $maxGenerations - $usedCredits);
    }

    /**
     * Check if user has unlimited credits
     *
     * @param User $user
     * @return bool
     */
    public function isUnlimited(User $user): bool
    {
        return $this->getRemainingCredits($user) === -1;
    }

    /**
     * Get total credits allowed for user
     *
     * @param User $user
     * @return int Total credits (-1 for unlimited)
     */
    public function getTotalCredits(User $user): int
    {
        $subscription = $user->activeSubscription;
        
        if (!$subscription) {
            return $user->monthly_credits ?? config('app.default_monthly_credits', 5);
        }

        $maxGenerations = $subscription->subscription->max_content_generations ?? 0;
        
        if ($maxGenerations < 0 || $maxGenerations > 100000) {
            return -1; // Unlimited
        }

        return $maxGenerations;
    }

    /**
     * Get used credits for user
     *
     * @param User $user
     * @return int
     */
    public function getUsedCredits(User $user): int
    {
        return $user->used_credits ?? 0;
    }

    /**
     * Reset user credits (typically called at billing cycle)
     *
     * @param User $user
     * @return bool
     */
    public function resetCredits(User $user): bool
    {
        $user->update(['used_credits' => 0]);
        
        Log::info('Credits reset', [
            'user_id' => $user->id,
        ]);

        return true;
    }

    /**
     * Add bonus credits to user
     *
     * @param User $user
     * @param int $amount
     * @return bool
     */
    public function addBonusCredits(User $user, int $amount): bool
    {
        $currentBonus = $user->bonus_credits ?? 0;
        $user->update(['bonus_credits' => $currentBonus + $amount]);
        
        Log::info('Bonus credits added', [
            'user_id' => $user->id,
            'amount' => $amount,
            'new_total' => $currentBonus + $amount,
        ]);

        return true;
    }

    /**
     * Get credit usage statistics for user
     *
     * @param User $user
     * @return array
     */
    public function getUsageStats(User $user): array
    {
        $total = $this->getTotalCredits($user);
        $used = $this->getUsedCredits($user);
        $remaining = $this->getRemainingCredits($user);
        $isUnlimited = $this->isUnlimited($user);

        return [
            'total' => $isUnlimited ? 'unlimited' : $total,
            'used' => $used,
            'remaining' => $isUnlimited ? 'unlimited' : $remaining,
            'is_unlimited' => $isUnlimited,
            'percentage_used' => $isUnlimited ? 0 : ($total > 0 ? round(($used / $total) * 100, 1) : 0),
        ];
    }
}
