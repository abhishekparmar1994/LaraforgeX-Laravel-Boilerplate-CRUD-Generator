<?php

namespace App\Http\Controllers\Admin;

use App\Actions\LogActivityAction;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Payment;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller for tracking client invoices and payments.
 */
class PaymentController extends Controller
{
    protected LogActivityAction $activityLogger;

    public function __construct(LogActivityAction $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display payments & invoices directory.
     */
    public function index(Request $request): View
    {
        $query = Payment::with(['client', 'project'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('invoice_number', 'like', "%{$s}%")
                    ->orWhereHas('client', function ($cq) use ($s) {
                        $cq->where('name', 'like', "%{$s}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        $payments = $query->paginate(15);
        $clients = Client::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();

        $stats = [
            'total_invoiced' => (float) Payment::where('status', '!=', 'cancelled')->sum('amount'),
            'total_paid' => (float) Payment::where('status', 'paid')->sum('amount'),
            'total_pending' => (float) Payment::whereIn('status', ['pending', 'partial'])->sum('amount'),
            'total_overdue' => (float) Payment::where('status', 'overdue')->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'clients', 'projects', 'stats'));
    }

    /**
     * Store new payment record / invoice.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:crm_clients,id',
            'project_id' => 'nullable|exists:crm_projects,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'payment_method' => 'required|string',
            'status' => 'required|in:pending,partial,paid,overdue,cancelled',
            'notes' => 'nullable|string',
        ]);

        $invoiceCount = Payment::count() + 1001;
        $validated['invoice_number'] = 'INV-' . date('Y') . '-' . $invoiceCount;

        $payment = Payment::create($validated);

        $client = Client::find($validated['client_id']);
        if ($client) {
            $this->activityLogger->execute($client, 'payment', "Invoice #{$payment->invoice_number} created for \${$payment->amount}.");
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Invoice recorded successfully', 'payment' => $payment]);
        }

        return redirect()->back()->with('success', 'Invoice recorded successfully!');
    }

    /**
     * Update payment / invoice status.
     */
    public function update(Request $request, Payment $payment): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'payment_method' => 'required|string',
            'status' => 'required|in:pending,partial,paid,overdue,cancelled',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $payment->status;
        $payment->update($validated);

        if ($oldStatus !== $payment->status && $payment->client) {
            $this->activityLogger->execute($payment->client, 'payment', "Invoice #{$payment->invoice_number} status updated to '{$payment->status}'.");
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Payment updated successfully', 'payment' => $payment]);
        }

        return redirect()->back()->with('success', 'Payment updated successfully!');
    }

    /**
     * Delete payment.
     */
    public function destroy(Payment $payment): RedirectResponse|JsonResponse
    {
        $inv = $payment->invoice_number;
        $payment->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => "Invoice '{$inv}' deleted."]);
        }

        return redirect()->back()->with('success', "Invoice '{$inv}' deleted.");
    }
}
