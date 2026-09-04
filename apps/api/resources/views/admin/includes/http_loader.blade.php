<!-- ── Global HTTP/HTTPS Request Progress & Spinner Engine ── -->
<div id="laraforgex-http-progress"
     style="position: fixed; top: 0; left: 0; width: 0%; height: 3px; z-index: 9999999; pointer-events: none; opacity: 0; transition: width 0.22s ease-out, opacity 0.25s ease; background: linear-gradient(90deg, #2b47ff 0%, #8b5cf6 50%, #ec4899 100%); box-shadow: 0 0 10px rgba(99, 102, 241, 0.8), 0 0 5px rgba(236, 72, 153, 0.6);">
</div>

<div id="laraforgex-http-spinner"
     style="position: fixed; top: 14px; right: 18px; z-index: 9999998; pointer-events: none; opacity: 0; transform: translateY(-8px); transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1); display: flex; align-items: center; gap: 8px; padding: 6px 14px; border-radius: 9999px; background: rgba(15, 23, 42, 0.88); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.12); color: #ffffff; font-size: 11px; font-weight: 600; font-family: ui-sans-serif, system-ui, sans-serif; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.2);">
  <span style="display: inline-block; width: 12px; height: 12px; border-radius: 50%; border: 2px solid rgba(255, 255, 255, 0.25); border-top-color: #ffffff; animation: lf-spin 0.65s linear infinite;"></span>
  <span id="laraforgex-http-spinnerText">Loading...</span>
</div>

<style>
  @keyframes lf-spin {
    to { transform: rotate(360deg); }
  }
</style>

<script>
  (function () {
    const progressBar = document.getElementById('laraforgex-http-progress');
    const spinner = document.getElementById('laraforgex-http-spinner');
    const spinnerText = document.getElementById('laraforgex-http-spinnerText');

    let activeRequests = 0;
    let progressTimer = null;
    let currentProgress = 0;

    const HttpLoader = {
      start: function (msg = 'Loading...') {
        activeRequests++;
        if (activeRequests === 1) {
          if (spinnerText) spinnerText.textContent = msg;
          this.show();
        }
      },

      done: function () {
        activeRequests = Math.max(0, activeRequests - 1);
        if (activeRequests === 0) {
          this.finish();
        }
      },

      show: function () {
        if (!progressBar) return;
        clearInterval(progressTimer);
        currentProgress = 20;
        progressBar.style.opacity = '1';
        progressBar.style.width = currentProgress + '%';

        if (spinner) {
          spinner.style.opacity = '1';
          spinner.style.transform = 'translateY(0)';
        }

        // Simulate steady network progress
        progressTimer = setInterval(() => {
          if (currentProgress < 85) {
            currentProgress += Math.random() * 9 + 3;
            progressBar.style.width = Math.min(85, currentProgress) + '%';
          }
        }, 180);
      },

      finish: function () {
        if (!progressBar) return;
        clearInterval(progressTimer);
        progressBar.style.width = '100%';

        if (spinner) {
          spinner.style.opacity = '0';
          spinner.style.transform = 'translateY(-8px)';
        }

        setTimeout(() => {
          progressBar.style.opacity = '0';
          setTimeout(() => {
            progressBar.style.width = '0%';
            currentProgress = 0;
          }, 220);
        }, 200);
      }
    };

    window.HttpLoader = HttpLoader;

    // ── 1. Intercept Native XMLHttpRequest (Axios, jQuery, Dropzone, etc.) ──
    const origOpen = XMLHttpRequest.prototype.open;
    const origSend = XMLHttpRequest.prototype.send;

    XMLHttpRequest.prototype.open = function (method, url) {
      const urlStr = typeof url === 'string' ? url : (url ? String(url) : '');
      this._isHttpTracked = !urlStr.includes('/agent/') && !urlStr.includes('no_loader=1');
      return origOpen.apply(this, arguments);
    };

    XMLHttpRequest.prototype.send = function () {
      if (this._isHttpTracked) {
        HttpLoader.start();
        const onComplete = () => {
          if (!this._httpDoneReported) {
            this._httpDoneReported = true;
            HttpLoader.done();
          }
        };

        this.addEventListener('loadend', onComplete);
        this.addEventListener('error', onComplete);
        this.addEventListener('abort', onComplete);
        this.addEventListener('timeout', onComplete);
      }
      return origSend.apply(this, arguments);
    };

    // ── 2. Intercept Native Fetch ─────────────────────────────────────
    if (window.fetch) {
      const origFetch = window.fetch;
      window.fetch = function (resource) {
        const urlStr = typeof resource === 'string' ? resource : (resource?.url || '');
        if (urlStr.includes('/agent/') || urlStr.includes('no_loader=1')) {
          return origFetch.apply(this, arguments);
        }
        HttpLoader.start();
        return origFetch.apply(this, arguments)
          .then(res => {
            HttpLoader.done();
            return res;
          })
          .catch(err => {
            HttpLoader.done();
            throw err;
          });
      };
    }
  })();
</script>
