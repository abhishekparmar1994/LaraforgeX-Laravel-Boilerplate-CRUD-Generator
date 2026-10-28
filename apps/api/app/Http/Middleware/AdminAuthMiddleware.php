<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

/**
 * Server-side authentication guard for Admin Portal routes.
 * Supports both standard web sessions and Sanctum Bearer token fallback.
 */
class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->authenticateRequest($request)) {
            return $next($request);
        }

        if ($request->is('api/*') || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        return redirect()->guest('/admin/login');
    }

    /**
     * Attempt authentication via web session or Sanctum token.
     */
    private function authenticateRequest(Request $request): bool
    {
        if (Auth::guard('web')->check()) {
            return true;
        }

        $token = $request->bearerToken()
            ?? $request->cookie('laraforgex_auth_token')
            ?? $request->header('X-Auth-Token');

        if (!$token) {
            return false;
        }

        $accessToken = PersonalAccessToken::findToken($token);
        if (!$accessToken || !$accessToken->tokenable) {
            return false;
        }

        Auth::guard('web')->login($accessToken->tokenable);
        return true;
    }
}
