{{-- Lead Detail: Internal Team Notes Timeline --}}
<div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
  <div class="flex items-center justify-between">
    <h3 class="font-extrabold text-sm text-slate-900">Internal Solar Team Notes</h3>
  </div>

  <!-- Quick Note Add Box -->
  <form action="/admin/notes" method="POST" class="bg-slate-50 border border-slate-200/80 rounded-2xl p-3 space-y-2">
    @csrf
    <input type="hidden" name="notable_type" value="App\Models\Lead">
    <input type="hidden" name="notable_id" value="{{ $lead->id }}">
    <input type="text" name="title" placeholder="Note subject (e.g. DISCOM net-metering discussion, quote sent)..."
      class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
    <textarea name="content" rows="2" required placeholder="Write a note about this solar inquiry..."
      class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs font-medium text-slate-700 focus:outline-none focus:border-amber-500"></textarea>
    <div class="flex items-center justify-between pt-1">
      <label class="flex items-center gap-1.5 text-[11px] font-bold text-slate-500 cursor-pointer">
        <input type="checkbox" name="is_pinned" value="1" class="rounded text-amber-600">
        <span>Pin Note</span>
      </label>
      <button type="submit"
        class="px-4 py-1.5 bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
        Post Note
      </button>
    </div>
  </form>

  <!-- Notes List -->
  <div class="space-y-3" id="notesListContainer">
    @forelse($lead->leadNotes ?? [] as $note)
      <div
        class="p-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-xs space-y-1 {{ $note->is_pinned ? 'border-l-4 border-l-amber-400' : '' }}">
        <div class="flex items-center justify-between">
          <span class="font-bold text-slate-800">{{ $note->title ?? 'Note' }}</span>
          <span class="text-[10px] text-slate-400">{{ $note->created_at->diffForHumans() }}</span>
        </div>
        <p class="text-slate-600 font-medium whitespace-pre-line">{{ $note->content }}</p>
        @if($note->user)
          <p class="text-[10px] text-slate-400 font-semibold pt-1">Added by {{ $note->user->name }}</p>
        @endif
      </div>
    @empty
      <p class="text-xs text-slate-400 italic py-2">No solar team notes logged yet.</p>
    @endforelse
  </div>
</div>