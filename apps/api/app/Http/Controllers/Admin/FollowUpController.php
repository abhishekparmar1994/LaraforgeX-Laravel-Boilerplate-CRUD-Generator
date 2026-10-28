<?php

namespace App\Http\Controllers\Admin;

use App\Actions\LogActivityAction;
use App\Domains\User\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Contact;
use App\Models\FollowUp;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

/**
 * Controller for managing client and lead follow-up schedules.
 */
class FollowUpController extends Controller
{
    protected LogActivityAction $activityLogger;

    public function __construct(LogActivityAction $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display follow-ups list.
     */
    public function index(Request $request): View
    {
        $query = FollowUp::with(['lead', 'client', 'contact', 'assignedTo'])->orderBy('scheduled_at', 'asc');

        if ($request->filled('filter')) {
            if ($request->filter === 'today') {
                $query->whereDate('scheduled_at', Carbon::today());
            } elseif ($request->filter === 'upcoming') {
                $query->where('scheduled_at', '>', Carbon::now()->endOfDay())
                    ->where('status', 'scheduled');
            } elseif ($request->filter === 'overdue') {
                $query->where('scheduled_at', '<', Carbon::now()->startOfDay())
                    ->where('status', 'scheduled');
            } elseif ($request->filter === 'completed') {
                $query->where('status', 'completed');
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('follow_up_type', $request->type);
        }

        if ($request->filled('developer_id')) {
            $query->where('assigned_to_user_id', $request->developer_id);
        }

        $followups = $query->paginate(24);
        $counts = [
            'all' => FollowUp::count(),
            'overdue' => FollowUp::where('scheduled_at', '<', Carbon::now()->startOfDay())->where('status', 'scheduled')->count(),
            'today' => FollowUp::whereDate('scheduled_at', Carbon::today())->count(),
            'upcoming' => FollowUp::where('scheduled_at', '>', Carbon::now()->endOfDay())->where('status', 'scheduled')->count(),
            'completed' => FollowUp::where('status', 'completed')->count(),
            'cancelled' => FollowUp::where('status', 'cancelled')->count(),
        ];
        $developers = User::all();
        $leads = Lead::orderBy('company_name')->get();
        $clients = Client::orderBy('name')->get();
        $contacts = Contact::orderBy('first_name')->get();

        return view('admin.followups.index', compact('followups', 'counts', 'developers', 'leads', 'clients', 'contacts'));
    }

    /**
     * Store new follow-up.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'lead_id' => 'nullable|exists:crm_leads,id',
            'client_id' => 'nullable|exists:crm_clients,id',
            'contact_id' => 'nullable|exists:crm_contacts,id',
            'assigned_to_user_id' => 'nullable|exists:users,id',
            'scheduled_at' => 'required|date',
            'follow_up_type' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:scheduled,completed,rescheduled,cancelled',
        ]);

        $followup = FollowUp::create($validated);

        if ($followup->lead) {
            $followup->lead->update(['next_followup_date' => $followup->scheduled_at]);
            $this->activityLogger->execute($followup->lead, 'call', "Follow-up scheduled: " . ucfirst(str_replace('_', ' ', $followup->follow_up_type)));
        } elseif ($followup->client) {
            $this->activityLogger->execute($followup->client, 'call', "Follow-up scheduled: " . ucfirst(str_replace('_', ' ', $followup->follow_up_type)));
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Follow-up scheduled successfully', 'followup' => $followup]);
        }

        return redirect()->back()->with('success', 'Follow-up scheduled successfully!');
    }

    /**
     * Update/edit follow-up.
     */
    public function update(Request $request, FollowUp $followup): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'scheduled_at' => 'required|date',
            'follow_up_type' => 'nullable|string',
            'assigned_to_user_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:scheduled,completed,rescheduled,cancelled',
        ]);

        $followup->update(array_filter($validated, fn($v) => $v !== null));

        if ($followup->lead) {
            $nextScheduled = FollowUp::where('lead_id', $followup->lead_id)
                ->where('status', 'scheduled')
                ->orderBy('scheduled_at', 'asc')
                ->value('scheduled_at');
            $followup->lead->update(['next_followup_date' => $nextScheduled]);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Follow-up updated successfully', 'followup' => $followup]);
        }

        return redirect()->back()->with('success', 'Follow-up updated successfully!');
    }

    /**
     * Mark follow-up as completed.
     */
    public function complete(FollowUp $followup): RedirectResponse|JsonResponse
    {
        $followup->update(['status' => 'completed']);

        if ($followup->lead) {
            $nextScheduled = FollowUp::where('lead_id', $followup->lead_id)
                ->where('status', 'scheduled')
                ->where('scheduled_at', '>=', now())
                ->orderBy('scheduled_at', 'asc')
                ->value('scheduled_at');
            $followup->lead->update(['next_followup_date' => $nextScheduled]);
            $this->activityLogger->execute($followup->lead, 'call', "Follow-up completed.");
        } elseif ($followup->client) {
            $this->activityLogger->execute($followup->client, 'call', "Follow-up completed.");
        }

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Follow-up marked as completed.']);
        }

        return redirect()->back()->with('success', 'Follow-up marked as completed.');
    }

    /**
     * Reschedule follow-up.
     */
    public function reschedule(Request $request, FollowUp $followup): RedirectResponse|JsonResponse
    {
        $request->validate([
            'scheduled_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $followup->update([
            'scheduled_at' => $request->scheduled_at,
            'status' => 'rescheduled',
            'notes' => $request->notes ?? $followup->notes,
        ]);

        if ($followup->lead) {
            $nextScheduled = FollowUp::where('lead_id', $followup->lead_id)
                ->where('status', 'scheduled')
                ->orderBy('scheduled_at', 'asc')
                ->value('scheduled_at');
            $followup->lead->update(['next_followup_date' => $nextScheduled]);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Follow-up rescheduled.', 'followup' => $followup]);
        }

        return redirect()->back()->with('success', 'Follow-up rescheduled successfully.');
    }

    /**
     * Delete follow-up.
     */
    public function destroy(FollowUp $followup): RedirectResponse|JsonResponse
    {
        $lead = $followup->lead;
        $followup->delete();

        if ($lead) {
            $nextScheduled = FollowUp::where('lead_id', $lead->id)
                ->where('status', 'scheduled')
                ->where('scheduled_at', '>=', now())
                ->orderBy('scheduled_at', 'asc')
                ->value('scheduled_at');
            $lead->update(['next_followup_date' => $nextScheduled]);
        }

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Follow-up deleted.']);
        }

        return redirect()->back()->with('success', 'Follow-up deleted successfully.');
    }
}
