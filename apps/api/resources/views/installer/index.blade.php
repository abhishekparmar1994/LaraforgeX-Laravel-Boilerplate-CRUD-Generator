<!DOCTYPE html>
<html lang="en" class="dark scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LaraforgeX — Setup & Web Installer</title>

  <!-- Google Fonts: Inter & Outfit -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  
  <!-- FontAwesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <!-- Tailwind CSS CDN -->
  <script src="/vendor/tailwind/tailwind.js"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            brand: {
              50: '#eef2ff',
              100: '#e0e7ff',
              200: '#c7d2fe',
              300: '#a5b4fc',
              400: '#818cf8',
              500: '#6366f1',
              600: '#4f46e5',
              700: '#4338ca',
              800: '#3730a3',
              900: '#312e81',
              950: '#1e1b4b',
            }
          },
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
            display: ['Outfit', 'sans-serif'],
          }
        }
      }
    }
  </script>

  <!-- SweetAlert2 & Axios -->
  <script src="/vendor/jquery/jquery.min.js"></script>
  <script src="/vendor/axios/axios.min.js"></script>
  <script src="/vendor/sweetalert2/sweetalert2.all.min.js"></script>

  <style>
    body {
      background-color: #0b0f19;
      color: #f3f4f6;
    }
    h1, h2, h3, h4, h5, h6 {
      font-family: 'Outfit', sans-serif;
    }
    .gradient-text {
      background: linear-gradient(135deg, #818cf8 0%, #c084fc 50%, #f472b6 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .gradient-bg {
      background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #db2777 100%);
    }
    .glass-card {
      background: rgba(17, 24, 39, 0.8);
      backdrop-filter: blur(16px);
      border: 1px solid rgba(255, 255, 255, 0.08);
    }
  </style>
</head>
<body class="antialiased selection:bg-brand-500 selection:text-white min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 font-sans relative overflow-x-hidden">

  <!-- Ambient Glow Backgrounds -->
  <div class="absolute w-[600px] h-[600px] bg-brand-600/15 rounded-full blur-[140px] -top-32 -left-32 pointer-events-none"></div>
  <div class="absolute w-[600px] h-[600px] bg-purple-600/10 rounded-full blur-[140px] -bottom-32 -right-32 pointer-events-none"></div>

  <div class="w-full max-w-4xl space-y-8 relative z-10">
    
    <!-- Brand Logo Header -->
    <div class="text-center space-y-3">
      <div class="inline-flex h-14 w-14 rounded-2xl gradient-bg items-center justify-center text-white font-extrabold text-2xl shadow-xl shadow-brand-600/30">
        LX
      </div>
      <div class="space-y-1">
        <h1 class="text-3xl font-extrabold text-white font-display tracking-tight">Laraforge<span class="gradient-text">X</span> Installation Wizard</h1>
        <p class="text-xs text-slate-400 font-medium">Follow the 4 simple steps below to set up your environment, database, and admin account</p>
      </div>
    </div>

    <!-- Wizard Steps Indicator -->
    <div class="glass-card p-4 rounded-2xl border border-slate-800 grid grid-cols-4 gap-2 text-center text-xs font-semibold">
      <div id="step-indicator-1" class="py-2.5 px-3 rounded-xl bg-brand-600 text-white font-bold transition flex items-center justify-center gap-2">
        <span class="h-5 w-5 rounded-full bg-white/20 text-[10px] flex items-center justify-center font-mono">1</span>
        <span class="hidden sm:inline">Requirements</span>
      </div>
      <div id="step-indicator-2" class="py-2.5 px-3 rounded-xl bg-slate-900 text-slate-400 transition flex items-center justify-center gap-2">
        <span class="h-5 w-5 rounded-full bg-slate-800 text-[10px] flex items-center justify-center font-mono">2</span>
        <span class="hidden sm:inline">Permissions</span>
      </div>
      <div id="step-indicator-3" class="py-2.5 px-3 rounded-xl bg-slate-900 text-slate-400 transition flex items-center justify-center gap-2">
        <span class="h-5 w-5 rounded-full bg-slate-800 text-[10px] flex items-center justify-center font-mono">3</span>
        <span class="hidden sm:inline">Database</span>
      </div>
      <div id="step-indicator-4" class="py-2.5 px-3 rounded-xl bg-slate-900 text-slate-400 transition flex items-center justify-center gap-2">
        <span class="h-5 w-5 rounded-full bg-slate-800 text-[10px] flex items-center justify-center font-mono">4</span>
        <span class="hidden sm:inline">Setup Admin</span>
      </div>
    </div>

    <!-- Step Content Containers -->
    <div class="glass-card p-8 sm:p-10 rounded-3xl border border-slate-800 space-y-8 shadow-2xl">
      
      <!-- ── STEP 1: SERVER REQUIREMENTS ────────────────────────────────────── -->
      <div id="step-content-1" class="space-y-6">
        <div class="border-b border-slate-800 pb-4">
          <h2 class="text-xl font-bold text-white font-display">Step 1: Server Requirements</h2>
          <p class="text-xs text-slate-400">Verifying PHP version and required PHP extensions on your server</p>
        </div>

        <div class="space-y-4">
          <!-- PHP Version Box -->
          <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-800 flex items-center justify-between">
            <div class="space-y-0.5">
              <div class="text-xs font-bold text-white">PHP Version (>= 8.2.0)</div>
              <div class="text-[11px] text-slate-400" id="php-version-text">Checking version...</div>
            </div>
            <div id="php-version-status">
              <i class="fa-solid fa-spinner fa-spin text-brand-400 text-lg"></i>
            </div>
          </div>

          <!-- Extensions List -->
          <div class="space-y-2">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Required PHP Extensions</div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 font-mono text-xs" id="extensions-list">
              <div class="p-3 rounded-xl bg-slate-900/50 border border-slate-800 text-slate-400">Loading extensions...</div>
            </div>
          </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-slate-800">
          <button type="button" id="btn-next-1" disabled onclick="goToStep(2)" class="px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-xs transition inline-flex items-center gap-2">
            Next: Check Permissions <i class="fa-solid fa-arrow-right"></i>
          </button>
        </div>
      </div>

      <!-- ── STEP 2: FOLDER PERMISSIONS ─────────────────────────────────────── -->
      <div id="step-content-2" class="space-y-6 hidden">
        <div class="border-b border-slate-800 pb-4">
          <h2 class="text-xl font-bold text-white font-display">Step 2: Folder Permissions</h2>
          <p class="text-xs text-slate-400">Verifying writable directory permissions required for storage, logs, and framework cache</p>
        </div>

        <div class="space-y-3 font-mono text-xs" id="permissions-list">
          <div class="p-4 rounded-xl bg-slate-900/50 border border-slate-800 text-slate-400">Checking folder permissions...</div>
        </div>

        <div class="flex justify-between pt-4 border-t border-slate-800">
          <button type="button" onclick="goToStep(1)" class="px-5 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:text-white font-bold text-xs transition">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back
          </button>
          <button type="button" id="btn-next-2" disabled onclick="goToStep(3)" class="px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-xs transition inline-flex items-center gap-2">
            Next: Configure Database <i class="fa-solid fa-arrow-right"></i>
          </button>
        </div>
      </div>

      <!-- ── STEP 3: DATABASE CONFIGURATION ─────────────────────────────────── -->
      <div id="step-content-3" class="space-y-6 hidden">
        <div class="border-b border-slate-800 pb-4">
          <h2 class="text-xl font-bold text-white font-display">Step 3: Database Configuration</h2>
          <p class="text-xs text-slate-400">Enter your MySQL / MariaDB database server connection parameters</p>
        </div>

        <form id="form-database" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="space-y-1 sm:col-span-2">
              <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Database Host</label>
              <input type="text" id="db_host" value="127.0.0.1" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white font-mono focus:outline-none focus:border-brand-500 transition">
            </div>
            <div class="space-y-1">
              <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Port</label>
              <input type="text" id="db_port" value="3306" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white font-mono focus:outline-none focus:border-brand-500 transition">
            </div>
          </div>

          <div class="space-y-1">
            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Database Name</label>
            <input type="text" id="db_name" value="laraforgex" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white font-mono focus:outline-none focus:border-brand-500 transition" placeholder="e.g. laraforgex">
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1">
              <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Database Username</label>
              <input type="text" id="db_user" value="root" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white font-mono focus:outline-none focus:border-brand-500 transition">
            </div>
            <div class="space-y-1">
              <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Database Password</label>
              <input type="password" id="db_pass" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white font-mono focus:outline-none focus:border-brand-500 transition" placeholder="Leave empty if none">
            </div>
          </div>

          <div class="pt-2">
            <button type="button" id="btn-test-db" onclick="testDatabaseConnection()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold border border-slate-700 transition inline-flex items-center gap-2">
              <i class="fa-solid fa-plug"></i> Test Connection
            </button>
          </div>
        </form>

        <div class="flex justify-between pt-4 border-t border-slate-800">
          <button type="button" onclick="goToStep(2)" class="px-5 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:text-white font-bold text-xs transition">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back
          </button>
          <button type="button" id="btn-next-3" onclick="goToStep(4)" class="px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs transition inline-flex items-center gap-2">
            Next: Admin Credentials <i class="fa-solid fa-arrow-right"></i>
          </button>
        </div>
      </div>

      <!-- ── STEP 4: SYSTEM & ADMIN SETUP ──────────────────────────────────── -->
      <div id="step-content-4" class="space-y-6 hidden">
        <div class="border-b border-slate-800 pb-4">
          <h2 class="text-xl font-bold text-white font-display">Step 4: Application & Admin Setup</h2>
          <p class="text-xs text-slate-400">Configure application metadata and create your Super Administrator account</p>
        </div>

        <form id="form-install" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1">
              <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Application Name</label>
              <input type="text" id="app_name" value="LaraforgeX" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-500 transition">
            </div>
            <div class="space-y-1">
              <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Application URL</label>
              <input type="url" id="app_url" value="http://127.0.0.1:8000" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-500 transition">
            </div>
          </div>

          <div class="border-t border-slate-800 pt-4 space-y-4">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Super Administrator Credentials</div>
            
            <div class="space-y-1">
              <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Full Name</label>
              <input type="text" id="admin_name" value="Administrator" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-500 transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="space-y-1">
                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Admin Email</label>
                <input type="email" id="admin_email" value="admin@laraforgex.com" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-500 transition">
              </div>
              <div class="space-y-1">
                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Admin Password</label>
                <input type="password" id="admin_password" value="password" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-500 transition" placeholder="Minimum 8 characters">
              </div>
            </div>
          </div>
        </form>

        <div class="flex justify-between pt-4 border-t border-slate-800">
          <button type="button" onclick="goToStep(3)" class="px-5 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:text-white font-bold text-xs transition">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back
          </button>
          <button type="button" id="btn-submit-install" onclick="executeInstallation()" class="px-8 py-3.5 rounded-xl gradient-bg text-white font-extrabold text-sm transition shadow-xl shadow-brand-600/30 inline-flex items-center gap-2">
            <i class="fa-solid fa-rocket"></i> Complete Installation & Launch
          </button>
        </div>
      </div>

    </div>

  </div>

  <script>
    let currentStep = 1;

    $(document).ready(function() {
        checkRequirements();
        checkPermissions();
    });

    function goToStep(step) {
        currentStep = step;
        for (let i = 1; i <= 4; i++) {
            if (i === step) {
                $(`#step-content-${i}`).removeClass('hidden');
                $(`#step-indicator-${i}`).removeClass('bg-slate-900 text-slate-400').addClass('bg-brand-600 text-white font-bold');
            } else {
                $(`#step-content-${i}`).addClass('hidden');
                $(`#step-indicator-${i}`).removeClass('bg-brand-600 text-white font-bold').addClass('bg-slate-900 text-slate-400');
            }
        }
    }

    async function checkRequirements() {
        try {
            const res = await axios.get('/api/v1/install/requirements');
            if (res.data.success) {
                const data = res.data.data;
                
                // PHP Version
                const phpIcon = data.php.satisfied ? '<i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>' : '<i class="fa-solid fa-circle-xmark text-rose-500 text-lg"></i>';
                $('#php-version-status').html(phpIcon);
                $('#php-version-text').text(`Installed: PHP ${data.php.current} (Required: >= ${data.php.minimum})`);

                // Extensions
                let html = '';
                data.extensions.forEach(ext => {
                    const icon = ext.satisfied ? '<i class="fa-solid fa-check text-emerald-400 mr-2"></i>' : '<i class="fa-solid fa-xmark text-rose-500 mr-2"></i>';
                    const color = ext.satisfied ? 'text-slate-200' : 'text-rose-400';
                    html += `<div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800 flex items-center justify-between ${color}">
                        <span>${ext.name}</span>
                        <span>${icon}</span>
                    </div>`;
                });
                $('#extensions-list').html(html);

                if (data.is_ready) {
                    $('#btn-next-1').prop('disabled', false);
                }
            }
        } catch (e) {
            console.error(e);
        }
    }

    async function checkPermissions() {
        try {
            const res = await axios.get('/api/v1/install/permissions');
            if (res.data.success) {
                const data = res.data.data;
                let html = '';
                data.directories.forEach(dir => {
                    const icon = dir.writable ? '<i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>' : '<i class="fa-solid fa-circle-xmark text-rose-500 text-lg"></i>';
                    const statusText = dir.writable ? `<span class="text-emerald-400 font-bold">Writable (${dir.permission})</span>` : `<span class="text-rose-400 font-bold">Not Writable (${dir.permission})</span>`;
                    html += `<div class="p-4 rounded-xl bg-slate-900/80 border border-slate-800 flex items-center justify-between">
                        <div>
                            <div class="font-bold text-white">${dir.name}</div>
                            <div class="text-[10px] text-slate-500">${dir.path}</div>
                        </div>
                        <div class="flex items-center gap-3">
                            ${statusText}
                            ${icon}
                        </div>
                    </div>`;
                });
                $('#permissions-list').html(html);

                if (data.is_ready) {
                    $('#btn-next-2').prop('disabled', false);
                }
            }
        } catch (e) {
            console.error(e);
        }
    }

    async function testDatabaseConnection() {
        const payload = {
            db_host: $('#db_host').val().trim(),
            db_port: $('#db_port').val().trim(),
            db_name: $('#db_name').val().trim(),
            db_user: $('#db_user').val().trim(),
            db_pass: $('#db_pass').val().trim()
        };

        $('#btn-test-db').html('<i class="fa-solid fa-spinner fa-spin"></i> Testing Connection...');

        try {
            const res = await axios.post('/api/v1/install/test-db', payload);
            if (res.data.success) {
                Swal.fire({
                    title: 'Connection Successful!',
                    text: res.data.message,
                    icon: 'success',
                    confirmButtonColor: '#4f46e5',
                    background: '#111827',
                    color: '#fff'
                });
            }
        } catch (err) {
            const msg = err.response && err.response.data && err.response.data.message ? err.response.data.message : 'Failed to connect to database.';
            Swal.fire({
                title: 'Connection Failed',
                text: msg,
                icon: 'error',
                confirmButtonColor: '#4f46e5',
                background: '#111827',
                color: '#fff'
            });
        } finally {
            $('#btn-test-db').html('<i class="fa-solid fa-plug"></i> Test Connection');
        }
    }

    async function executeInstallation() {
        const payload = {
            app_name: $('#app_name').val().trim(),
            app_url: $('#app_url').val().trim(),
            db_host: $('#db_host').val().trim(),
            db_port: $('#db_port').val().trim(),
            db_name: $('#db_name').val().trim(),
            db_user: $('#db_user').val().trim(),
            db_pass: $('#db_pass').val().trim(),
            admin_name: $('#admin_name').val().trim(),
            admin_email: $('#admin_email').val().trim(),
            admin_password: $('#admin_password').val().trim()
        };

        $('#btn-submit-install').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Installing LaraforgeX...');

        try {
            const res = await axios.post('/api/v1/install/process', payload);
            if (res.data.success) {
                Swal.fire({
                    title: 'Installation Complete!',
                    text: 'LaraforgeX has been installed successfully. Redirecting to admin login...',
                    icon: 'success',
                    confirmButtonColor: '#4f46e5',
                    background: '#111827',
                    color: '#fff'
                }).then(() => {
                    window.location.href = res.data.redirect_url;
                });
            }
        } catch (err) {
            const msg = err.response && err.response.data && err.response.data.message ? err.response.data.message : 'Installation failed.';
            Swal.fire({
                title: 'Installation Error',
                text: msg,
                icon: 'error',
                confirmButtonColor: '#4f46e5',
                background: '#111827',
                color: '#fff'
            });
            $('#btn-submit-install').prop('disabled', false).html('<i class="fa-solid fa-rocket"></i> Complete Installation & Launch');
        }
    }
  </script>
</body>
</html>
