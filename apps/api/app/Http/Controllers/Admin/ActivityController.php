<?php

namespace App\Http\Controllers\Admin;

use App\Actions\LogActivityAction;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller for viewing and logging polymorphic CRM activities.
 */
class ActivityController extends Controller
{
    protected LogActivityAction $activityLogger;

    public function __construct(LogActivityAction $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display activities timeline.
     */
    public function index(Request $request): View
    {
        $query = Activity::with(['user', 'subject'])->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('description', 'like', "%{$s}%");
        }

        $activities = $query->paginate(25);

        return view('admin.activities.index', compact('activities'));
    }

    /**
     * Store new manual activity (e.g. Call log, WhatsApp log, Note).
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'subject_type' => 'required|string',
            'subject_id' => 'required|string',
            'type' => 'required|string',
            'description' => 'required|string',
        ]);

        $subjectClass = $validated['subject_type'];
        if (!class_exists($subjectClass)) {
            $subjectClass = "App\\Models\\" . class_basename($validated['subject_type']);
        }

        $subject = $subjectClass::findOrFail($validated['subject_id']);

        $activity = $this->activityLogger->execute(
            $subject,
            $validated['type'],
            $validated['description']
        );

        if ($request->ajax()) {
            return response()->json(['success' => true, 'activity' => $activity]);
        }

        return redirect()->back()->with('success', 'Activity logged successfully!');
    }
}
