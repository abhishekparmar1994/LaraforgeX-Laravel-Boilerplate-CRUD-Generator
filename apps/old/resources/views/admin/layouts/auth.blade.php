<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'LaraforgeX Admin Panel')</title>

  <!-- Local Offline Tailwind JS (Play CDN script loaded locally) -->
  <script src="{{ asset('vendor/tailwind/tailwind.js') }}"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
          },
          colors: {
            brand: {
              50: '#f5f7ff',
              100: '#ebf0ff',
              200: '#d6e0ff',
              300: '#b3c7ff',
              400: '#85a3ff',
              500: '#5275ff',
              600: '#2b47ff',
              700: '#1b2eff',
              800: '#111fff',
              900: '#0007e0',
            }
          }
        }
      }
    }
  </script>
</head>

<body class="bg-slate-50 text-slate-700 antialiased selection:bg-brand-500 selection:text-white">


  @yield('content')

  <!-- Local Offline JavaScript Assets -->
  <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('vendor/axios/axios.min.js') }}"></script>
  <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
  <script src="{{ asset('vendor/fontawesome/all.min.js') }}"></script>

  <!-- Axios Interceptors & Authentication Guards -->
  <script>
    // Axios defaults
    axios.defaults.baseURL = '/api/v1';
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    // Inject CSRF Token automatically
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
      axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    }

    // Auth Request Interceptor
    axios.interceptors.request.use(function (config) {
      const authToken = localStorage.getItem('laraforgex_auth_token');
      if (authToken) {
        config.headers.Authorization = `Bearer ${authToken}`;
      }
      return config;
    }, function (error) {
      return Promise.reject(error);
    });

    // Auth Response Interceptor
    axios.interceptors.response.use(function (response) {
      return response;
    }, function (error) {
      if (error.response && error.response.status === 401) {
        localStorage.removeItem('laraforgex_auth_token');
        localStorage.removeItem('laraforgex_user');
        if (window.location.pathname !== '/admin/login') {
          window.location.href = '/admin/login';
        }
      }
      return Promise.reject(error);
    });

    // SweetAlert2 standard Toast configuration
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3500,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    });

    window.showToast = function (type, message) {
      Toast.fire({
        icon: type,
        title: message
      });
    }

    window.handleAjaxError = function (error, defaultMsg = 'An unexpected error occurred.') {
      console.error(error);
      const msg = error.response && error.response.data && error.response.data.message
        ? error.response.data.message
        : defaultMsg;
      showToast('error', msg);
    }

    // Toggle password visibility helper
    window.togglePasswordVisibility = function (inputId, iconId) {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(iconId);
      if (!input || !icon) return;

      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    };
  </script>

  @yield('scripts')
</body>

</html>