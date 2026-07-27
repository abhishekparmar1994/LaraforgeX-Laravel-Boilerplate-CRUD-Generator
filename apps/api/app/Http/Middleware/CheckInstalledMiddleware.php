<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstalledMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Redirects users to /install if LaraforgeX has not been installed yet,
     * or redirects away from /install to /admin/login if installation is complete.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $installedFilePath = storage_path('installed');
        $isInstalled = file_exists($installedFilePath);
        $isInstallRoute = $request->is('install*') || $request->is('api/v1/install*');

        if ($isInstalled && $isInstallRoute) {
            return redirect('/admin/login');
        }

        if (!$isInstalled && !$isInstallRoute && !$request->is('vendor/*') && !$request->is('css/*') && !$request->is('js/*')) {
            return redirect('/install');
        }

        return $next($request);
    }
}
