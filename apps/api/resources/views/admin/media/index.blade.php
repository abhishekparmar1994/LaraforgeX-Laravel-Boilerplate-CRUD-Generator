@extends('admin.layouts.app')

@section('title', 'LaraforgeX — Media Cloud Manager')

@section('breadcrumbs')
<nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
  <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <span class="text-slate-700">Media Manager</span>
</nav>
@endsection

@section('content')
<div class="space-y-6 font-sans">
  <div class="flex items-center justify-between">
    <div>
      <h2 class="text-xl font-bold text-slate-900">Media Manager</h2>
      <p class="text-xs text-slate-500 mt-0.5 font-medium">Direct-to-bucket S3 and GCP binary media uploader.</p>
    </div>
  </div>

  <!-- Drag & Drop Uploader Area -->
  <div class="bg-white border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center transition hover:border-brand-500 cursor-pointer" id="media-dropzone-uploader">
    <div class="dz-message space-y-3">
      <div class="h-12 w-12 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-500 text-2xl mx-auto"><i class="fa-solid fa-cloud-arrow-up"></i></div>
      <p class="text-sm font-semibold text-slate-700">Drag files here or click to select files for upload</p>
      <p class="text-xs text-slate-400">Supports direct browser uploads to AWS S3 or GCP buckets via presigned links.</p>
    </div>
  </div>

  <!-- ── Filter Bar ──────────────────────────────────────────────── -->
  <div class="bg-white border border-slate-100 rounded-xl px-4 py-3 shadow-sm">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
      <!-- File Name Search -->
      <div class="space-y-1">
        <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">File Name</label>
        <input id="filter-media-name" type="text" placeholder="Search file name..."
               class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brand-500 transition">
      </div>
      <!-- MIME Type -->
      <div class="space-y-1">
        <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">File Type</label>
        <select id="filter-media-type"
                class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 transition">
          <option value="">All Types</option>
          <option value="image">Images</option>
          <option value="video">Videos</option>
          <option value="application/pdf">PDFs</option>
          <option value="application">Documents</option>
        </select>
      </div>
      <!-- Reset -->
      <div class="flex items-end">
        <button id="btn-reset-media-filters"
                class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-500 text-xs font-semibold transition">
          <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset Filters
        </button>
      </div>
    </div>
  </div>

  <!-- Reusable Responsive DataTable -->
  <div id="media-datatable"></div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let dropzoneInstance = null;

    // ── Row Renderer ───────────────────────────────────────────
    function mediaRow(m) {
        const isVideo = m.mime_type.startsWith('video/');
        const isImage = m.mime_type.startsWith('image/');
        
        let preview = `<div class="h-10 w-10 rounded bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 text-base"><i class="fa-regular fa-file-invoice"></i></div>`;
        if (isImage && m.url) {
            preview = `<div class="h-10 w-10 rounded border border-slate-200 bg-cover bg-center shrink-0" style="background-image: url('${m.url}')"></div>`;
        } else if (isVideo) {
            preview = `<div class="h-10 w-10 rounded bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 text-base"><i class="fa-regular fa-file-video"></i></div>`;
        }

        const sizeMB = (m.size / (1024 * 1024)).toFixed(2);

        return `
          <tr class="hover:bg-slate-50/60 transition">
            <td class="px-5 py-3 shrink-0">${preview}</td>
            <td class="px-5 py-3">
              <div class="font-semibold text-slate-900 text-sm max-w-xs truncate" title="${m.name}">${m.name}</div>
              <div class="text-[10px] text-slate-400 font-semibold mt-0.5">ID: ${m.id}</div>
            </td>
            <td class="px-5 py-3 text-xs text-slate-500 font-medium">${sizeMB} MB</td>
            <td class="px-5 py-3 text-xs text-slate-500 font-mono">${m.mime_type || '—'}</td>
            <td class="px-5 py-3">
              <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-600">${m.disk.toUpperCase()}</span>
            </td>
            <td class="px-5 py-3">
              <div class="flex justify-end gap-1.5">
                <button onclick="copyMediaLink('${m.url || ''}')" class="px-2.5 py-1.5 rounded bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold text-xs transition"><i class="fa-regular fa-copy mr-1"></i> Link</button>
                <button onclick="deleteMedia('${m.id}')" class="px-2.5 py-1.5 rounded bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-600 font-semibold text-xs transition"><i class="fa-solid fa-trash-can mr-1"></i> Delete</button>
              </div>
            </td>
          </tr>
        `;
    }

    // ── Init AdminTable ────────────────────────────────────────
    const mediaTable = new AdminTable({
        container: '#media-datatable',
        columns: [
            { key: 'preview',   label: 'Preview',   sortable: false },
            { key: 'name',      label: 'File Name', sortable: true },
            { key: 'size',      label: 'Size',      sortable: true,  responsive: 'sm' },
            { key: 'mime_type', label: 'Type',      sortable: true,  responsive: 'md' },
            { key: 'disk',      label: 'Disk',      sortable: true,  responsive: 'lg' },
            { key: 'actions',   label: 'Actions',   sortable: false, class: 'text-right' }
        ],
        fetch: async () => {
            const params = {};
            const name = $('#filter-media-name').val().trim();
            const type = $('#filter-media-type').val();
            if (name) params.name = name;
            if (type) params.type = type;
            const response = await axios.get('/media', { params });
            const files = response.data.data.files || [];
            return files;
        },
        row: mediaRow
    });

    mediaTable.load().then(() => {
        initDropzoneUploader();
    });

    // ── Server-Side Filter Logic ──────────────────────────────────
    let _mediaFilterTimer;
    $('#filter-media-name').on('input', () => {
      clearTimeout(_mediaFilterTimer);
      _mediaFilterTimer = setTimeout(() => mediaTable.reload(), 400);
    });
    $('#filter-media-type').on('change', () => mediaTable.reload());

    $('#btn-reset-media-filters').on('click', () => {
      $('#filter-media-name').val('');
      $('#filter-media-type').val('');
      mediaTable.reload();
    });

    // ── Actions ────────────────────────────────────────────────
    window.copyMediaLink = function(url) {
        if (!url) {
            showToast('warning', 'File URL is not available yet.');
            return;
        }
        navigator.clipboard.writeText(url);
        showToast('success', 'Public file URL copied to clipboard.');
    }

    window.deleteMedia = function(id) {
        Swal.fire({
            title: 'Delete Cloud File?',
            text: 'This file will be permanently deleted from S3 or GCP buckets.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Confirm delete',
            background: '#ffffff',
            color: '#0f172a'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    await axios.delete(`/media/${id}`);
                    showToast('success', 'File deleted successfully.');
                    mediaTable.reload();
                } catch (e) {
                    handleAjaxError(e);
                }
            }
        });
    }

    function initDropzoneUploader() {
        if (dropzoneInstance) return;

        Dropzone.autoDiscover = false;
        dropzoneInstance = new Dropzone("#media-dropzone-uploader", {
            url: "#",
            autoProcessQueue: false,
            maxFilesize: 250,
            addedfile: async function(file) {
                try {
                    showToast('info', `Initiating presigned URL for: ${file.name}`);
                    const response = await axios.post('/media/presign', {
                        name: file.name,
                        mime_type: file.type || 'application/octet-stream',
                        size: file.size,
                        folder_id: null
                    });

                    if (response.data.success) {
                        const { upload_url, media, headers } = response.data.data;
                        showToast('info', 'Uploading directly to bucket...');

                        const options = {
                            headers: {
                                ...headers,
                                'Content-Type': file.type || 'application/octet-stream'
                            }
                        };
                        
                        await axios.put(upload_url, file, options);
                        showToast('info', 'Verifying upload registry...');

                        await axios.post(`/media/${media.id}/confirm`);
                        
                        showToast('success', 'File uploaded and secured successfully!');
                        mediaTable.reload();
                    }
                } catch (e) {
                    handleAjaxError(e, 'Failed to complete cloud file upload.');
                }
            }
        });
    }
});
</script>
@endsection
