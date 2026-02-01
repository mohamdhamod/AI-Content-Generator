<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Models\UserSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionsController extends Controller
{
    /**
     * Show pending subscriptions for admin approval.
     */
    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_SUBSCRIPTIONS);
    }

    /**
     * Show all user subscriptions (active, pending, expired).
     */
    public function index()
    {
        $subscriptions = UserSubscription::with(['user', 'subscription.translations'])
            ->latest()
            ->paginate(20);

        return view('dashboard.subscriptions.index', [
            'subscriptions' => $subscriptions
        ]);
    }

    /**
     * Show pending subscriptions for admin approval.
     */
    public function pending()
    {
        $pendingSubscriptions = UserSubscription::with(['user', 'subscription.translations'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('dashboard.subscriptions.pending', [
            'pendingSubscriptions' => $pendingSubscriptions
        ]);
    }

    /**
     * Activate subscription by admin (sets start and end dates).
     */
    public function activate(Request $request, $lang, $subscriptionId): JsonResponse
    {
        try {
            $userSubscription = UserSubscription::with('subscription')->findOrFail($subscriptionId);
            $subscription = $userSubscription->subscription;

            // Get duration based on subscription plan
            $durationMonths = $subscription->duration_months ?? 1;

            // Set activation dates
            $startDate = Carbon::now();
            $endDate = $startDate->copy()->addMonths($durationMonths);

            // Update the user subscription with activation dates
            $userSubscription->update([
                'started_at' => $startDate,
                'expires_at' => $endDate,
                'status' => 'active',
            ]);

            return response()->json([
                'redirect' => route('subscriptions.dashboard.index'),
                'success' => true,
                'message' => __('translation.subscription.activation_success'),
                'data' => [
                    'started_at' => $startDate->format('Y-m-d H:i:s'),
                    'expires_at' => $endDate->format('Y-m-d H:i:s'),
                    'duration_months' => $durationMonths,
                    'user_name' => $userSubscription->user->name
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Subscription activation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('translation.subscription.activation_failed'),
                'errors' => ['general' => [__('translation.subscription.unexpected_error')]]
            ], 500);
        }
    }

    /**
     * Update subscription payment status.
     */
    public function updatePaymentStatus(Request $request, $lang, UserSubscription $userSubscription): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,active,cancelled,expired,refunded'],
        ]);

        $newStatus = $validated['status'];

        try {
            DB::transaction(function () use ($userSubscription, $newStatus) {
                if ($newStatus === 'active') {
                    $subscription = $userSubscription->subscription;
                    $durationMonths = $subscription->duration_months ?? 1;
                    $startDate = Carbon::now();
                    $endDate = (clone $startDate)->addMonths($durationMonths);

                    $userSubscription->update([
                        'status' => $newStatus,
                        'started_at' => $startDate,
                        'expires_at' => $endDate,
                    ]);
                    
                    return;
                }

                if ($newStatus === 'cancelled' || $newStatus === 'refunded') {
                    $userSubscription->update([
                        'status' => $newStatus,
                        'cancelled_at' => Carbon::now(),
                    ]);
                    
                    return;
                }

                // pending/expired
                $userSubscription->update([
                    'status' => $newStatus,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => __('translation.messages.operation_completed_successfully'),
                'status' => $newStatus,
            ]);
        } catch (\Throwable $e) {
            Log::error('User subscription status update failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('translation.messages.an_error_occurred'),
            ], 500);
        }
    }

    /**
     * Show subscription details.
     */
    public function show($lang, $subscriptionId)
    {
        $subscription = UserSubscription::with(['user', 'subscription.translations'])
            ->findOrFail($subscriptionId);

        return view('dashboard.subscriptions.show', [
            'subscription' => $subscription
        ]);
    }
}
