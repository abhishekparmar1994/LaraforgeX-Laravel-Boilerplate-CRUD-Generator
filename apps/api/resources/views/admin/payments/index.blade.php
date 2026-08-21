@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Invoices & Payments')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Invoices & Payments</span>
  </nav>
@endsection

@section('content')
<div class="space-y-6 font-sans">

  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <h2 class="text-xl font-extrabold text-slate-900">Billing & Payment Invoices</h2>
      <p class="text-xs text-slate-500 font-medium mt-0.5">Track client invoices, milestones collections, and accounts receivable.</p>
    </div>
    <button onclick="$('#modal-create-payment').removeClass('hidden')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs shadow-sm shadow-brand-600/20 transition cursor-pointer">
      <i class="fa-solid fa-plus"></i> Record Invoice / Payment
    </button>
  </div>

  <!-- Financial KPI Metrics -->
  <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
    <div class="bg-white border border-slate-200 p-4 rounded-3xl shadow-xs">
      <span class="text-[10px] font-bold text-slate-400 uppercase block">Total Invoiced</span>
      <h3 class="text-xl font-extrabold text-slate-900 mt-1">${{ number_format($stats['total_invoiced'], 0) }}</h3>
    </div>
    <div class="bg-white border border-slate-200 p-4 rounded-3xl shadow-xs">
      <span class="text-[10px] font-bold text-emerald-600 uppercase block">Collected Revenue</span>
      <h3 class="text-xl font-extrabold text-emerald-700 mt-1">${{ number_format($stats['total_paid'], 0) }}</h3>
    </div>
    <div class="bg-white border border-slate-200 p-4 rounded-3xl shadow-xs">
      <span class="text-[10px] font-bold text-amber-600 uppercase block">Pending Balance</span>
      <h3 class="text-xl font-extrabold text-amber-700 mt-1">${{ number_format($stats['total_pending'], 0) }}</h3>
    </div>
    <div class="bg-white border border-slate-200 p-4 rounded-3xl shadow-xs">
      <span class="text-[10px] font-bold text-rose-600 uppercase block">Overdue Invoices</span>
      <h3 class="text-xl font-extrabold text-rose-700 mt-1">${{ number_format($stats['total_overdue'], 0) }}</h3>
    </div>
  </div>

  <!-- Invoices Table -->
  <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-xs">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
          <tr>
            <th class="px-4 py-3.5">Invoice #</th>
            <th class="px-4 py-3.5">Client & Project</th>
            <th class="px-4 py-3.5">Amount</th>
            <th class="px-4 py-3.5">Payment Method</th>
            <th class="px-4 py-3.5">Due Date</th>
            <th class="px-4 py-3.5">Status</th>
            <th class="px-4 py-3.5 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
          @forelse($payments as $pay)
            <tr class="hover:bg-slate-50/70 transition">
              <td class="px-4 py-3.5 font-mono font-extrabold text-slate-900">
                #{{ $pay->invoice_number }}
              </td>
              <td class="px-4 py-3.5">
                <span class="font-bold text-slate-800 block">{{ $pay->client ? $pay->client->name : 'N/A' }}</span>
                <span class="text-[11px] text-slate-400">{{ $pay->project ? $pay->project->name : 'General Retainer' }}</span>
              </td>
              <td class="px-4 py-3.5 font-extrabold text-sm text-slate-900">
                ${{ number_format($pay->amount, 2) }}
              </td>
              <td class="px-4 py-3.5 font-semibold text-slate-600">
                {{ $pay->payment_method }}
              </td>
              <td class="px-4 py-3.5">
                <span class="{{ $pay->is_overdue ? 'text-rose-600 font-bold' : 'text-slate-600' }}">
                  {{ $pay->due_date ? $pay->due_date->format('M d, Y') : '—' }}
                </span>
              </td>
              <td class="px-4 py-3.5">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $pay->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($pay->status === 'overdue' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-amber-50 text-amber-700 border-amber-200') }}">
                  {{ ucfirst($pay->status) }}
                </span>
              </td>
              <td class="px-4 py-3.5 text-right space-x-2">
                <button onclick="confirmDeletePayment('{{ $pay->id }}')" class="text-slate-400 hover:text-rose-600 transition">
                  <i class="fa-regular fa-trash-can"></i>
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="p-8 text-center text-slate-400">No payment invoices registered.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-4 border-t border-slate-100">
      {{ $payments->withQueryString()->links() }}
    </div>
  </div>

</div>

<!-- Create Payment Modal -->
<div id="modal-create-payment" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4 py-6">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs" onclick="$('#modal-create-payment').addClass('hidden')"></div>
    <form action="/admin/payments" method="POST" class="relative bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-4 font-sans text-xs">
      @csrf
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <h3 class="font-extrabold text-base text-slate-900">Record Invoice / Payment</h3>
        <button type="button" onclick="$('#modal-create-payment').addClass('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">×</button>
      </div>

      <div class="space-y-3">
        <div>
          <label class="font-bold text-slate-700 block mb-1">Client *</label>
          <select name="client_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
            @foreach($clients as $c)
              <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Linked Project</label>
          <select name="project_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
            <option value="">-- None / General Billing --</option>
            @foreach($projects as $p)
              <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="grid grid-cols-2 gap-2">
          <div>
            <label class="font-bold text-slate-700 block mb-1">Amount ($) *</label>
            <input type="number" name="amount" step="0.01" required value="2500" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
          </div>
          <div>
            <label class="font-bold text-slate-700 block mb-1">Payment Method</label>
            <select name="payment_method" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
              <option value="Bank Wire">Bank Wire</option>
              <option value="Stripe">Stripe</option>
              <option value="PayPal">PayPal</option>
              <option value="Credit Card">Credit Card</option>
              <option value="Check">Check</option>
            </select>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-2">
          <div>
            <label class="font-bold text-slate-700 block mb-1">Due Date</label>
            <input type="date" name="due_date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
          </div>
          <div>
            <label class="font-bold text-slate-700 block mb-1">Status</label>
            <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
              <option value="pending">Pending</option>
              <option value="paid">Paid</option>
              <option value="partial">Partial</option>
              <option value="overdue">Overdue</option>
            </select>
          </div>
        </div>
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="$('#modal-create-payment').addClass('hidden')" class="px-4 py-2 bg-slate-100 rounded-xl font-bold text-slate-600">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white rounded-xl font-bold shadow-sm">Save Invoice</button>
      </div>
    </form>
  </div>
</div>

<script>
  async function confirmDeletePayment(id) {
    if (confirm('Delete this invoice record?')) {
      try {
        await axios.delete(`/admin/payments/${id}`);
        location.reload();
      } catch(e) {
        alert('Failed to delete payment record');
      }
    }
  }
</script>
@endsection
