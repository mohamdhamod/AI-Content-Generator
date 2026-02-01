<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionsRequest;
use App\Models\Subscription;
use Illuminate\Http\Request;


class ManageSubscriptionsController extends Controller
{
    /**
     * Show subscription plans for admin management.
     */
    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_SUBSCRIPTIONS);
    }

    public function index(Request $request)
    {
        $subscriptions = Subscription::with(['translations'])->orderBy('sort_order')->latest()->paginate(12);

        return view('dashboard.subscriptions.manage.index', ['subscriptions' => $subscriptions]);
    }


    public function store(SubscriptionsRequest $request)
    {
        try {
            $model = Subscription::create([
                'price' => $request->price,
                'currency' => $request->currency ?? 'EUR',
                'duration_months' => $request->duration_months ?? 1,
                'max_content_generations' => $request->max_content_generations,
                'digistore_product_id' => $request->digistore_product_id,
                'digistore_checkout_url' => $request->digistore_checkout_url,
                'features' => $request->features ? json_decode($request->features, true) : null,
                'sort_order' => $request->sort_order ?? 0,
            ]);
            
            // Save translations
            $locale = app()->getLocale();
            $model->translateOrNew($locale)->name = $request->name;
            $model->translateOrNew($locale)->description = $request->description;
            $model->save();

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
        return response()->json([
            'success' => true,
            'redirect' => route('subscriptions.dashboard.manage.index'),
            'message' => __('translation.messages.added_successfully')
        ], 200);
    }

    public function update(SubscriptionsRequest $request, $lang, $id)
    {
        $model = $id instanceof Subscription ? $id : Subscription::findOrFail($id);
        
        $model->update([
            'price' => $request->price,
            'currency' => $request->currency ?? 'EUR',
            'duration_months' => $request->duration_months ?? 1,
            'max_content_generations' => $request->max_content_generations,
            'digistore_product_id' => $request->digistore_product_id,
            'digistore_checkout_url' => $request->digistore_checkout_url,
            'features' => $request->features ? json_decode($request->features, true) : null,
            'sort_order' => $request->sort_order ?? 0,
        ]);
        
        // Save translations
        $locale = app()->getLocale();
        $model->translateOrNew($locale)->name = $request->name;
        $model->translateOrNew($locale)->description = $request->description;
        $model->save();
        
        return response()->json([
            'success' => true,
            'redirect' => route('subscriptions.dashboard.manage.index'),
            'message' => __('translation.messages.updated_successfully')
        ], 200);
    }

    public function destroy($lang, $id)
    {
        $model = Subscription::findOrFail($id);
        $model->delete();
        $model->deleteTranslations();
        return response()->json([
            'success' => true,
            'redirect' => route('subscriptions.dashboard.manage.index'),
            'message' => __('translation.messages.deleted_successfully')
        ], 200);
    }

    public function updateActiveStatus($lang, $id)
    {
        $model = Subscription::findOrFail($id);
        $active = $model->active ? 0 : 1;
        $model->update([
            'active' => $active
        ]);
        return response()->json([
            'success' => true,
            'redirect' => route('subscriptions.dashboard.manage.index'),
            'message' => __('translation.messages.activated_successfully')
        ], 200);
    }
}
