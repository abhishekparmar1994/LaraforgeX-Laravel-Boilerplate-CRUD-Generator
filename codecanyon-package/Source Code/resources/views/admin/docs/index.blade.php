@extends('admin.layouts.app')

@section('title', 'LaraforgeX — API Documentation')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition" data-i18n="dashboard">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">API Docs (Swagger UI)</span>
  </nav>
@endsection

@section('content')
  <div class="space-y-6 font-sans">

    <!-- Header Banner -->
    <div class="theme-hero-banner bg-gradient-to-r from-brand-900 via-brand-700 to-indigo-800 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
      <div class="absolute right-0 top-0 translate-x-8 -translate-y-8 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
      <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <div class="flex items-center gap-2 text-brand-200 text-xs font-bold uppercase tracking-widest mb-1">
            <i class="fa-solid fa-code text-amber-300"></i> OpenAPI 3.0 Standard
          </div>
          <h1 class="text-2xl font-extrabold tracking-tight">REST API Documentation & Explorer</h1>
          <p class="text-sm text-brand-100 mt-1 max-w-2xl">
            Interactive Swagger UI playground for LaraforgeX Headless API v1 endpoints.
          </p>
        </div>
      </div>
    </div>

    <!-- Swagger Container -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm overflow-hidden min-h-[600px]">
      <div id="swagger-ui"></div>
    </div>

  </div>
@endsection

@section('scripts')
<!-- Swagger UI CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5/swagger-ui.css" />
<script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5/swagger-ui-bundle.js"></script>

<script>
  $(document).ready(function() {
      const spec = {
          openapi: "3.0.0",
          info: {
              title: "LaraforgeX REST API v1",
              version: "1.0.0",
              description: "Headless RESTful API documentation for LaraforgeX Laravel Core"
          },
          paths: {
              "/api/v1/crud-generator/tables": {
                  get: {
                      summary: "List database tables",
                      responses: { "200": { description: "Success" } }
                  }
              },
              "/api/v1/backups": {
                  get: {
                      summary: "List database SQL backups",
                      responses: { "200": { description: "Success" } }
                  }
              },
              "/api/v1/webhooks": {
                  get: {
                      summary: "List registered webhook endpoints",
                      responses: { "200": { description: "Success" } }
                  }
              }
          }
      };

      SwaggerUIBundle({
          spec: spec,
          dom_id: '#swagger-ui',
          deepLinking: true,
          presets: [
              SwaggerUIBundle.presets.apis,
              SwaggerUIBundle.SwaggerUIStandalonePreset
          ]
      });
  });
</script>
@endsection
