<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Log;

class Digistore24Service
{
    /**
     * Digistore24 IPN signature key from config.
     */
    protected string $ipnSignatureKey;

    public function __construct()
    {
        $this->ipnSignatureKey = config('services.digistore24.ipn_signature_key') ?? '';
    }

    /**
     * Verify the IPN request signature from Digistore24.
     */
    public function verifySignature(array $data): bool
    {
        if (empty($this->ipnSignatureKey)) {
            Log::warning('Digistore24 IPN signature key not configured');
            return false;
        }

        $signature = $data['sha_sign'] ?? '';
        unset($data['sha_sign']);

        // Sort by key
        ksort($data);

        // Build signature string
        $signatureString = '';
        foreach ($data as $key => $value) {
            if ($value !== '' && $key !== 'sha_sign') {
                $signatureString .= $key . '=' . $value . $this->ipnSignatureKey;
            }
        }

        $expectedSignature = strtoupper(sha1($signatureString));

        return hash_equals($expectedSignature, strtoupper($signature));
    }

    /**
     * Process IPN notification from Digistore24.
     */
    public function processIpn(array $data): array
    {
        $event = $data['event'] ?? '';
        
        Log::info('Digistore24 IPN received', ['event' => $event, 'order_id' => $data['order_id'] ?? 'N/A']);

        return match ($event) {
            'on_payment' => $this->handlePayment($data),
            'on_payment_missed' => $this->handlePaymentMissed($data),
            'on_refund' => $this->handleRefund($data),
            'on_chargeback' => $this->handleChargeback($data),
            'on_rebill_resumed' => $this->handleRebillResumed($data),
            'on_rebill_cancelled' => $this->handleRebillCancelled($data),
            default => ['success' => true, 'message' => 'Event ignored: ' . $event],
        };
    }

    /**
     * Handle successful payment.
     */
    protected function handlePayment(array $data): array
    {
        $email = $data['email'] ?? null;
        $orderId = $data['order_id'] ?? null;
        $productId = $data['product_id'] ?? null;
        $amount = $data['billing_amount'] ?? $data['amount'] ?? 0;
        $currency = $data['currency'] ?? 'EUR';

        if (!$email || !$orderId) {
            return ['success' => false, 'message' => 'Missing email or order_id'];
        }

        // Find or create user
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            Log::warning('Digistore24: User not found for email', ['email' => $email]);
            return ['success' => false, 'message' => 'User not found'];
        }

        // Find subscription plan by Digistore product ID
        $subscription = Subscription::where('digistore_product_id', $productId)->first();

        if (!$subscription) {
            Log::warning('Digistore24: Subscription plan not found', ['product_id' => $productId]);
            // Use default subscription if available
            $subscription = Subscription::active()->first();
        }

        // Check for existing subscription with this order
        $existingSubscription = UserSubscription::where('digistore_order_id', $orderId)->first();
        
        if ($existingSubscription) {
            // Update existing subscription
            $existingSubscription->update([
                'status' => 'active',
                'started_at' => now(),
                'expires_at' => now()->addMonths($subscription->duration_months ?? 1),
                'ipn_data' => $data,
            ]);

            // Reset user credits for renewal
            $this->updateUserCredits($user, $subscription);

            Log::info('Digistore24: Subscription renewed', ['user_id' => $user->id, 'order_id' => $orderId]);
            return ['success' => true, 'message' => 'Subscription renewed'];
        }

        // Create new subscription
        UserSubscription::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription?->id,
            'digistore_order_id' => $orderId,
            'digistore_product_id' => $productId,
            'digistore_affiliate_id' => $data['affiliate'] ?? null,
            'amount' => $amount,
            'currency' => $currency,
            'payment_method' => $data['pay_method'] ?? null,
            'status' => 'active',
            'started_at' => now(),
            'expires_at' => now()->addMonths($subscription->duration_months ?? 1),
            'ipn_data' => $data,
        ]);

        // Set user credits based on subscription plan
        $this->updateUserCredits($user, $subscription);

        Log::info('Digistore24: Subscription created', ['user_id' => $user->id, 'order_id' => $orderId]);

        return ['success' => true, 'message' => 'Subscription created'];
    }

    /**
     * Handle missed payment (rebill failed).
     */
    protected function handlePaymentMissed(array $data): array
    {
        $orderId = $data['order_id'] ?? null;

        if (!$orderId) {
            return ['success' => false, 'message' => 'Missing order_id'];
        }

        $subscription = UserSubscription::where('digistore_order_id', $orderId)->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'expired',
                'ipn_data' => $data,
            ]);

            Log::info('Digistore24: Subscription marked as expired due to missed payment', ['order_id' => $orderId]);
        }

        return ['success' => true, 'message' => 'Payment missed processed'];
    }

    /**
     * Handle refund.
     */
    protected function handleRefund(array $data): array
    {
        $orderId = $data['order_id'] ?? null;

        if (!$orderId) {
            return ['success' => false, 'message' => 'Missing order_id'];
        }

        $subscription = UserSubscription::where('digistore_order_id', $orderId)->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'refunded',
                'cancelled_at' => now(),
                'ipn_data' => $data,
            ]);

            Log::info('Digistore24: Subscription refunded', ['order_id' => $orderId]);
        }

        return ['success' => true, 'message' => 'Refund processed'];
    }

    /**
     * Handle chargeback.
     */
    protected function handleChargeback(array $data): array
    {
        return $this->handleRefund($data);
    }

    /**
     * Handle rebill resumed.
     */
    protected function handleRebillResumed(array $data): array
    {
        $orderId = $data['order_id'] ?? null;

        if (!$orderId) {
            return ['success' => false, 'message' => 'Missing order_id'];
        }

        $subscription = UserSubscription::where('digistore_order_id', $orderId)->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'active',
                'cancelled_at' => null,
                'ipn_data' => $data,
            ]);

            Log::info('Digistore24: Subscription rebill resumed', ['order_id' => $orderId]);
        }

        return ['success' => true, 'message' => 'Rebill resumed processed'];
    }

    /**
     * Handle rebill cancelled.
     */
    protected function handleRebillCancelled(array $data): array
    {
        $orderId = $data['order_id'] ?? null;

        if (!$orderId) {
            return ['success' => false, 'message' => 'Missing order_id'];
        }

        $subscription = UserSubscription::where('digistore_order_id', $orderId)->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'ipn_data' => $data,
            ]);

            Log::info('Digistore24: Subscription rebill cancelled', ['order_id' => $orderId]);
        }

        return ['success' => true, 'message' => 'Rebill cancelled processed'];
    }

    /**
     * Get checkout URL for a subscription plan.
     */
    public function getCheckoutUrl(Subscription $subscription, ?User $user = null): string
    {
        $baseUrl = $subscription->digistore_checkout_url 
            ?? 'https://www.digistore24.com/product/' . $subscription->digistore_product_id;

        $params = [];

        if ($user) {
            $params['email'] = $user->email;
            if ($user->name) {
                $nameParts = explode(' ', $user->name, 2);
                $params['first_name'] = $nameParts[0] ?? '';
                $params['last_name'] = $nameParts[1] ?? '';
            }
        }

        // Add custom tracking parameter
        $params['custom'] = 'ai_content_generator';

        if (!empty($params)) {
            $baseUrl .= (str_contains($baseUrl, '?') ? '&' : '?') . http_build_query($params);
        }

        return $baseUrl;
    }

    /**
     * Update user credits based on subscription plan.
     */
    protected function updateUserCredits(User $user, ?Subscription $subscription): void
    {
        if (!$subscription) {
            return;
        }

        $maxCredits = $subscription->max_content_generations ?? 0;
        
        // -1 means unlimited, use a high number for credits
        if ($maxCredits == -1) {
            $maxCredits = 999999;
        }

        $user->update([
            'monthly_credits' => $maxCredits,
            'used_credits' => 0,
            'credits_reset_at' => now(),
        ]);

        Log::info('Digistore24: User credits updated', [
            'user_id' => $user->id,
            'monthly_credits' => $maxCredits,
        ]);
    }
}
