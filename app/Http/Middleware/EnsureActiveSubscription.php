<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    /**
     * Handle an incoming request.
     * 
     * This middleware checks if user has either:
     * 1. An active subscription (paid users)
     * 2. Available free credits (freemium users)
     * 3. Admin role (bypass all checks)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If not authenticated, let the auth middleware handle it
        if (!$user) {
            return redirect()->guest(route('login'));
        }

        // Admins bypass all subscription checks
        if ($user->hasRole(RoleEnum::ADMIN)) {
            return $next($request);
        }

        // Check if user has an active subscription
        $hasActiveSubscription = $user->hasActiveSubscription();
        
        // Check if user has available credits (Freemium model)
        $availableCredits = $user->monthly_credits - $user->used_credits;
        
        // Allow access if user has either subscription OR available credits
        if ($hasActiveSubscription || $availableCredits > 0) {
            return $next($request);
        }

        // User has neither subscription nor credits - show appropriate message
        return $this->deny($request, $hasActiveSubscription);
    }

    /**
     * Build the denial response depending on context (API vs Web)
     */
    protected function deny(Request $request, bool $hadSubscription = false): Response
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => __('translation.messages.subscription_or_credits_required'),
                'credits_remaining' => 0,
            ], 402);
        }

        // Determine appropriate message
        $message = $hadSubscription 
            ? __('translation.messages.subscription_expired_renew')
            : __('translation.messages.no_credits_subscribe');

        // Redirect to subscriptions page
        if (\Illuminate\Support\Facades\Route::has('subscriptions.index')) {
            return redirect()->route('subscriptions.index')
                ->with('warning', $message);
        }

        // Fallback
        return redirect()->route('home')
            ->with('warning', $message);
    }
}
