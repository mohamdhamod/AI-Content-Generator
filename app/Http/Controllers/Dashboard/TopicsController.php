<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Models\Specialty;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopicsController extends Controller
{
    /**
     * Constructor with permission middleware.
     */
    public function __construct()
    {
        $this->middleware('permission:' . PermissionEnum::MANAGE_SPECIALTIES_VIEW)->only(['index']);
        $this->middleware('permission:' . PermissionEnum::MANAGE_SPECIALTIES_ADD)->only(['store']);
        $this->middleware('permission:' . PermissionEnum::MANAGE_SPECIALTIES_UPDATE)->only(['update', 'updateActiveStatus']);
        $this->middleware('permission:' . PermissionEnum::MANAGE_SPECIALTIES_DELETE)->only(['destroy']);
    }

    /**
     * Display a listing of topics for a specialty.
     */
    public function index(Request $request, $lang, Specialty $specialty)
    {
        $topics = $specialty->topics()
            ->with('translations')
            ->orderBy('sort_order')
            ->paginate(15);

        return view('dashboard.topics.index', [
            'specialty' => $specialty,
            'topics' => $topics
        ]);
    }

    /**
     * Store a newly created topic.
     */
    public function store(Request $request, $lang, Specialty $specialty)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prompt_hint' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

            $topic = Topic::create([
                'specialty_id' => $specialty->id,
                'icon' => $request->icon,
                'sort_order' => $request->sort_order ?? ($specialty->topics()->max('sort_order') + 1),
                'active' => true,
            ]);

            $topic->translateOrNew(app()->getLocale())->fill([
                'name' => $request->name,
                'description' => $request->description,
                'prompt_hint' => $request->prompt_hint,
            ]);
            $topic->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

        return response()->json([
            'success' => true,
            'redirect' => route('specialties.topics.index', ['specialty' => $specialty->id]),
            'message' => __('translation.messages.added_successfully')
        ], 200);
    }

    /**
     * Update the specified topic.
     */
    public function update(Request $request, $lang, Specialty $specialty, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prompt_hint' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

            $topic = Topic::where('specialty_id', $specialty->id)->findOrFail($id);

            $topic->update([
                'icon' => $request->icon,
                'sort_order' => $request->sort_order ?? $topic->sort_order,
            ]);

            $topic->translateOrNew(app()->getLocale())->fill([
                'name' => $request->name,
                'description' => $request->description,
                'prompt_hint' => $request->prompt_hint,
            ]);
            $topic->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

        return response()->json([
            'success' => true,
            'redirect' => route('specialties.topics.index', ['specialty' => $specialty->id]),
            'message' => __('translation.messages.updated_successfully')
        ], 200);
    }

    /**
     * Remove the specified topic.
     */
    public function destroy($lang, Specialty $specialty, $id)
    {
        $topic = Topic::where('specialty_id', $specialty->id)->findOrFail($id);
        
        $topic->delete();
        $topic->deleteTranslations();

        return response()->json([
            'success' => true,
            'redirect' => route('specialties.topics.index', ['specialty' => $specialty->id]),
            'message' => __('translation.messages.deleted_successfully')
        ], 200);
    }

    /**
     * Update the active status of a topic.
     */
    public function updateActiveStatus(Request $request, $lang, Specialty $specialty, $id)
    {
        $topic = Topic::where('specialty_id', $specialty->id)->findOrFail($id);
        $topic->active = !$topic->active;
        $topic->save();

        return response()->json([
            'success' => true,
            'message' => __('translation.messages.updated_successfully'),
            'redirect' => route('specialties.topics.index', ['specialty' => $specialty->id]),
        ], 200);
    }
}
