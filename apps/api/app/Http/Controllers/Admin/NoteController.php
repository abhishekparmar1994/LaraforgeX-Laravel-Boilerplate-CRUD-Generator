<?php

namespace App\Http\Controllers\Admin;

use App\Actions\LogActivityAction;
use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Controller for managing polymorphic notes across CRM entities.
 */
class NoteController extends Controller
{
    protected LogActivityAction $activityLogger;

    public function __construct(LogActivityAction $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display notes repository.
     */
    public function index(Request $request): View
    {
        $query = Note::with(['user', 'notable'])->orderBy('is_pinned', 'desc')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                    ->orWhere('content', 'like', "%{$s}%");
            });
        }

        if ($request->filled('entity_type')) {
            $query->where('notable_type', 'like', "%{$request->entity_type}%");
        }

        $notes = $query->paginate(18);

        return view('admin.notes.index', compact('notes'));
    }

    /**
     * Store new note.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'notable_type' => 'required|string',
            'notable_id' => 'required|string',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'is_pinned' => 'nullable|boolean',
        ]);

        $notableClass = $validated['notable_type'];
        if (!class_exists($notableClass)) {
            $notableClass = "App\\Models\\" . class_basename($validated['notable_type']);
        }

        $notable = $notableClass::findOrFail($validated['notable_id']);

        $note = Note::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'notable_type' => get_class($notable),
            'notable_id' => $notable->id,
            'title' => $validated['title'] ?? 'Note',
            'content' => $validated['content'],
            'is_pinned' => $request->boolean('is_pinned', false),
        ]);

        $this->activityLogger->execute($notable, 'note', "Note added: " . ($note->title ?? 'New Note'));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Note saved successfully', 'note' => $note]);
        }

        return redirect()->back()->with('success', 'Note saved successfully!');
    }

    /**
     * Toggle note pinned state.
     */
    public function togglePin(Note $note): JsonResponse
    {
        $note->update(['is_pinned' => !$note->is_pinned]);
        return response()->json(['success' => true, 'is_pinned' => $note->is_pinned]);
    }

    /**
     * Delete note.
     */
    public function destroy(Note $note): RedirectResponse|JsonResponse
    {
        $note->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Note deleted.']);
        }

        return redirect()->back()->with('success', 'Note deleted.');
    }
}
