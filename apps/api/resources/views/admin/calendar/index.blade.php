@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Agency Unified Calendar')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Agency Calendar</span>
  </nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">

  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <h2 class="text-xl font-extrabold text-slate-900">Agency Unified Calendar</h2>
      <p class="text-xs text-slate-500 font-medium mt-0.5">Aggregated task deadlines, client meetings, project milestones, domain/hosting renewals, and invoice due dates.</p>
    </div>

    <!-- Legend -->
    <div class="flex items-center gap-2 flex-wrap text-[10px] font-bold">
      <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700"><span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span> Tasks</span>
      <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700"><span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Follow-ups</span>
      <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-purple-50 text-purple-700"><span class="h-1.5 w-1.5 rounded-full bg-purple-500"></span> Projects</span>
      <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-pink-50 text-pink-700"><span class="h-1.5 w-1.5 rounded-full bg-pink-500"></span> Domains</span>
      <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-teal-50 text-teal-700"><span class="h-1.5 w-1.5 rounded-full bg-teal-500"></span> Invoices</span>
    </div>
  </div>

  <!-- Calendar Container Card -->
  <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs">
    <div id="fullcalendar-mount"></div>
  </div>

</div>

<!-- FullCalendar Library & Styles (CDN) -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<style>
  .fc .fc-toolbar-title { font-size: 1rem; font-weight: 800; color: #0f172a; }
  .fc .fc-button-primary { 
    background-color: #ffffff; 
    border-color: #e2e8f0; 
    color: #334155; 
    font-size: 0.75rem; 
    font-weight: 700; 
    border-radius: 0.75rem; 
    padding: 0.45rem 0.85rem; 
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease-in-out;
  }
  .fc .fc-prev-button,
  .fc .fc-next-button {
    width: 32px;
    height: 32px;
    padding: 0 !important;
  }
  .fc .fc-button-primary:hover { 
    background-color: #f8fafc; 
    border-color: #cbd5e1; 
    color: #0f172a; 
  }
  .fc .fc-button-primary:not(:disabled).fc-button-active { 
    background-color: #2b47ff; 
    border-color: #2b47ff; 
    color: #ffffff; 
  }
  .fc .fc-button-primary:focus {
    box-shadow: 0 0 0 2px rgba(43, 71, 255, 0.2);
  }

  /* Robust SVG chevron navigation icons for FullCalendar */
  .fc .fc-button .fc-icon-chevron-left,
  .fc .fc-button .fc-icon-chevron-right {
    display: inline-block;
    width: 14px;
    height: 14px;
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
  }
  .fc .fc-button .fc-icon-chevron-left {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.5' stroke='%23475569'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M15.75 19.5L8.25 12l7.5-7.5' /%3E%3C/svg%3E");
  }
  .fc .fc-button .fc-icon-chevron-right {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.5' stroke='%23475569'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M8.25 4.5l7.5 7.5-7.5 7.5' /%3E%3C/svg%3E");
  }
  .fc .fc-button:hover .fc-icon-chevron-left {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.5' stroke='%230f172a'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M15.75 19.5L8.25 12l7.5-7.5' /%3E%3C/svg%3E");
  }
  .fc .fc-button:hover .fc-icon-chevron-right {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.5' stroke='%230f172a'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M8.25 4.5l7.5 7.5-7.5 7.5' /%3E%3C/svg%3E");
  }
  .fc .fc-button .fc-icon-chevron-left:before,
  .fc .fc-button .fc-icon-chevron-right:before {
    content: "" !important;
    display: none !important;
  }

  .fc .fc-daygrid-day-number { font-size: 0.75rem; font-weight: 700; color: #64748b; padding: 4px 8px; }
  .fc .fc-col-header-cell-cushion { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; padding: 8px 0; }
  .fc .fc-event { border-radius: 6px; padding: 2px 4px; font-size: 11px; font-weight: 700; border: none; cursor: pointer; }
</style>

<script>
  document.addEventListener('DOMContentLoaded', async function () {
    const calendarEl = document.getElementById('fullcalendar-mount');
    if (!calendarEl) return;

    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      events: '/admin/calendar/events',
      eventClick: function(info) {
        if (info.event.url) {
          info.jsEvent.preventDefault();
          window.location.href = info.event.url;
        }
      }
    });

    calendar.render();
  });
</script>
@endsection
