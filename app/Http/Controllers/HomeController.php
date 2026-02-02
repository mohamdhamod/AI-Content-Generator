<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\UserSubscription;
use App\Services\Digistore24Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->only(['digistore24Webhook']);
    }

    /**
     * Show the application dashboard.
     */
    public function index(Request $request)
    {
        try {
            // Get real-time statistics
            $stats = [
                'specialties_count' => \App\Models\Specialty::active()->count(),
                'topics_count' => \App\Models\Topic::active()->count(),
                'content_types_count' => \App\Models\ContentType::active()->count(),
                'generated_count' => \App\Models\GeneratedContent::count(),
            ];
            
            // Get featured specialties with topics (for showcasing)
            $featuredSpecialties = \App\Models\Specialty::active()
                ->withCount(['topics' => function($query) {
                    $query->active();
                }])
                ->ordered()
                ->limit(8)
                ->get();
            
            // Get sample generated content (anonymized) for social proof
            $recentGenerations = \App\Models\GeneratedContent::with(['user', 'specialty', 'topic', 'contentType'])
                ->latest()
                ->limit(3)
                ->get()
                ->map(function($content) {
                    return [
                        'specialty' => $content->specialty->name ?? 'Medical',
                        'topic' => $content->topic->name ?? 'Healthcare',
                        'content_type' => $content->contentType->name ?? 'Article',
                        'created_at' => $content->created_at->diffForHumans(),
                        'word_count' => str_word_count(strip_tags($content->generated_content)),
                    ];
                });
            
            // Get subscription plans for pricing section
            $subscriptionPlans = \App\Models\Subscription::active()
                ->ordered()
                ->with('translations')
                ->get();
            
            // User-specific data
            $userData = null;
            if (auth()->check()) {
                $user = auth()->user();
                $userData = [
                    'active_subscription' => $user->activeSubscription,
                    'credits_available' => $this->calculateAvailableCredits($user),
                    'total_generated' => $user->generatedContents()->count(),
                ];
            }

            return view('home.index', compact('stats', 'featuredSpecialties', 'recentGenerations', 'subscriptionPlans', 'userData'));
        } catch (\Exception $e) {
            Log::error('HomeController index error: ' . $e->getMessage());
            
            // Fallback with minimal data
            return view('home.index', [
                'stats' => ['specialties_count' => 11, 'topics_count' => 121, 'content_types_count' => 7, 'generated_count' => 0],
                'featuredSpecialties' => collect(),
                'recentGenerations' => collect(),
                'subscriptionPlans' => collect(),
                'userData' => null,
            ]);
        }
    }

    /**
     * Check user login status
     */
    public function checkLoginStatus(): JsonResponse
    {
        return response()->json([
            'authenticated' => auth()->check(),
            'login_url' => route('login'),
            'register_url' => route('register'),
        ]);
    }

    /**
     * Show subscription plans.
     */
    public function subscriptions()
    {
        $user = auth()->user();
        $activeSubscription = $user->activeSubscription;
        
        $subscriptionPlans = Subscription::active()
            ->ordered()
            ->with(['translations', 'activeFeatures.translations'])
            ->get();

        $digistore24Service = new Digistore24Service();

        // Add checkout URLs to plans
        $subscriptionPlans->each(function ($plan) use ($user, $digistore24Service) {
            $plan->checkout_url = $digistore24Service->getCheckoutUrl($plan, $user);
        });

        return view('home.subscriptions', compact('subscriptionPlans', 'activeSubscription'));
    }

    /**
     * Show subscription plans (API).
     */
    public function subscriptionPlans(): JsonResponse
    {
        $user = auth()->user();
        $digistore24Service = new Digistore24Service();

        $plans = Subscription::active()
            ->ordered()
            ->with('translations')
            ->get()
            ->map(function ($plan) use ($user, $digistore24Service) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'description' => $plan->description,
                    'price' => $plan->price,
                    'currency' => $plan->currency,
                    'duration_months' => $plan->duration_months,
                    'max_content_generations' => $plan->max_content_generations,
                    'features' => $plan->features,
                    'checkout_url' => $digistore24Service->getCheckoutUrl($plan, $user),
                ];
            });

        return response()->json([
            'success' => true,
            'plans' => $plans,
        ]);
    }

    /**
     * Handle Digistore24 IPN webhook.
     */
    public function digistore24Webhook(Request $request): JsonResponse
    {
        $data = $request->all();

        Log::info('Digistore24 IPN received', ['ip' => $request->ip()]);

        $digistore24Service = new Digistore24Service();

        // Verify signature (optional but recommended)
        if (config('services.digistore24.ipn_signature_key')) {
            if (!$digistore24Service->verifySignature($data)) {
                Log::warning('Digistore24 IPN signature verification failed');
                return response()->json(['error' => 'Invalid signature'], 403);
            }
        }

        // Process the IPN
        $result = $digistore24Service->processIpn($data);

        return response()->json($result);
    }

    /**
     * Calculate available credits for a user based on their subscription.
     */
    private function calculateAvailableCredits($user): int
    {
        $subscription = $user->activeSubscription;
        
        if (!$subscription) {
            return 0;
        }

        $plan = $subscription->subscription;
        
        if (!$plan) {
            return 0;
        }

        // Unlimited plan
        if ($plan->max_content_generations == -1) {
            return -1; // Indicates unlimited
        }

        $monthlyUsage = $user->getMonthlyGenerationsCount();
        $maxAllowed = $plan->max_content_generations;

        return max(0, $maxAllowed - $monthlyUsage);
    }

    /**
     * Load the medical prompts library.
     */
    protected function loadPromptsLibrary(): ?array
    {
        $path = database_path('data/medical_prompts_library.json');

        if (!file_exists($path)) {
            return null;
        }

        $content = file_get_contents($path);
        return json_decode($content, true);
    }
}

