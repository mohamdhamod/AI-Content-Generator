<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionFeaturesController extends Controller
{
    /**
     * Constructor with permission middleware.
     */
    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_SUBSCRIPTIONS);
    }

    /**
     * Display a listing of features for a subscription.
     */
    public function index(Request $request, $lang, Subscription $manage_subscription)
    {
        $features = $manage_subscription->features()
            ->with('translations')
            ->orderBy('sort_order')
            ->paginate(15);

        return view('dashboard.subscriptions.features.index', [
            'subscription' => $manage_subscription,
            'features' => $features
        ]);
    }

    /**
     * Store a newly created feature.
     */
    public function store(Request $request, $lang, Subscription $manage_subscription)
    {
        $request->validate([
            'feature_text' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'is_highlighted' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

            $feature = SubscriptionFeature::create([
                'subscription_id' => $manage_subscription->id,
                'icon' => $request->icon ?? 'bi-check',
                'is_highlighted' => $request->boolean('is_highlighted'),
                'sort_order' => $request->sort_order ?? ($manage_subscription->features()->max('sort_order') + 1),
                'active' => true,
            ]);

            $feature->translateOrNew(app()->getLocale())->fill([
                'feature_text' => $request->feature_text,
            ]);
            $feature->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

        return response()->json([
            'success' => true,
            'redirect' => route('subscriptions.manage.features.index', ['manage_subscription' => $manage_subscription->id]),
            'message' => __('translation.messages.added_successfully')
        ], 200);
    }

    /**
     * Update the specified feature.
     */
    public function update(Request $request, $lang, Subscription $manage_subscription, $feature)
    {
        $request->validate([
            'feature_text' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'is_highlighted' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

            $featureModel = SubscriptionFeature::where('subscription_id', $manage_subscription->id)->findOrFail($feature);

            $featureModel->update([
                'icon' => $request->icon ?? $featureModel->icon,
                'is_highlighted' => $request->boolean('is_highlighted'),
                'sort_order' => $request->sort_order ?? $featureModel->sort_order,
            ]);

            $featureModel->translateOrNew(app()->getLocale())->fill([
                'feature_text' => $request->feature_text,
            ]);
            $featureModel->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

        return response()->json([
            'success' => true,
            'redirect' => route('subscriptions.manage.features.index', ['manage_subscription' => $manage_subscription->id]),
            'message' => __('translation.messages.updated_successfully')
        ], 200);
    }

    /**
     * Remove the specified feature.
     */
    public function destroy($lang, Subscription $manage_subscription, $feature)
    {
        $featureModel = SubscriptionFeature::where('subscription_id', $manage_subscription->id)->findOrFail($feature);
        
        $featureModel->delete();

        return response()->json([
            'success' => true,
            'redirect' => route('subscriptions.manage.features.index', ['manage_subscription' => $manage_subscription->id]),
            'message' => __('translation.messages.deleted_successfully')
        ], 200);
    }

    /**
     * Update the active status of a feature.
     */
    public function updateActiveStatus(Request $request, $lang, Subscription $subscription, $id)
    {
        $feature = SubscriptionFeature::where('subscription_id', $subscription->id)->findOrFail($id);
        $feature->active = !$feature->active;
        $feature->save();

        return response()->json([
            'success' => true,
            'message' => __('translation.messages.status_updated')
        ], 200);
    }
}
