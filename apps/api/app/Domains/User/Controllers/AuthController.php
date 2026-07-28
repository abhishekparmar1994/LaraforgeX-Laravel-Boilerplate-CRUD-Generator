<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Domains\User\Actions\AuthenticateMagicLinkAction;
use App\Domains\User\Actions\GenerateMagicLinkAction;
use App\Domains\User\Actions\LoginUserAction;
use App\Domains\User\Actions\LogoutUserAction;
use App\Domains\User\Actions\TwoFactorEnableAction;
use App\Domains\User\Actions\TwoFactorVerifyAction;
use App\Domains\User\Actions\ForgotPasswordAction;
use App\Domains\User\Actions\ResetPasswordAction;
use App\Domains\User\DTOs\LoginUserDTO;
use App\Domains\User\Requests\LoginRequest;
use App\Domains\User\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Get public reCAPTCHA configuration.
     */
    public function captchaConfig(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'recaptcha_enabled' => \App\Services\ReCaptchaService::isEnabled(),
                'recaptcha_site_key' => \App\Services\ReCaptchaService::getSiteKey(),
            ]
        ]);
    }

    /**
     * Handle authentication login requests.
     */
    public function login(LoginRequest $request, LoginUserAction $action): JsonResponse
    {
        if (\App\Services\ReCaptchaService::isEnabled()) {
            $token = $request->input('g-recaptcha-response') ?? $request->input('recaptcha_token');
            if (!\App\Services\ReCaptchaService::verify($token, $request->ip())) {
                return response()->json([
                    'success' => false,
                    'message' => 'reCAPTCHA verification failed. Please complete the captcha challenge.'
                ], 422);
            }
        }

        $dto = new LoginUserDTO(
            email: $request->input('email'),
            password: $request->input('password'),
            remember: $request->boolean('remember'),
            ipAddress: $request->ip()
        );

        $result = $action->execute($dto);

        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully.',
            'data' => [
                'token' => $result['token'],
                'user' => new UserResource($result['user']),
            ]
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request, LogoutUserAction $action): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            $action->execute($user);
        }

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $cookieSession = cookie()->forget(config('session.cookie', 'laraforgex_session'));
        $cookieXsrf = cookie()->forget('XSRF-TOKEN');

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.'
        ])->withCookie($cookieSession)->withCookie($cookieXsrf);
    }


    /**
     * Request a passwordless magic login link.
     */
    public function magicLink(Request $request, GenerateMagicLinkAction $action): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $link = $action->execute($request->input('email'));

        // In a real application, you would send this via mail/SMS.
        // We return it in JSON for development/API client consumption.
        return response()->json([
            'success' => true,
            'message' => 'Magic login link generated.',
            'data' => [
                'link' => $link
            ]
        ]);
    }

    /**
     * Authenticate via magic login link.
     */
    public function magicLoginVerify(Request $request, AuthenticateMagicLinkAction $action): JsonResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
        ]);

        $result = $action->execute(
            rawToken: $request->input('token'),
            ipAddress: $request->ip()
        );

        return response()->json([
            'success' => true,
            'message' => 'Authenticated successfully via magic link.',
            'data' => [
                'token' => $result['token'],
                'user' => new UserResource($result['user']),
            ]
        ]);
    }

    /**
     * Enable 2FA for the authenticated user.
     */
    public function twoFactorEnable(Request $request, TwoFactorEnableAction $action): JsonResponse
    {
        $result = $action->execute($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Two-factor authentication initiated. Scan the QR code to proceed.',
            'data' => $result
        ]);
    }

    /**
     * Confirm/Verify 2FA and get recovery codes.
     */
    public function twoFactorVerify(Request $request, TwoFactorVerifyAction $action): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $recoveryCodes = $action->execute(
            user: $request->user(),
            code: $request->input('code')
        );

        return response()->json([
            'success' => true,
            'message' => 'Two-factor authentication verified and enabled.',
            'data' => [
                'recovery_codes' => $recoveryCodes
            ]
        ]);
    }

    /**
     * Disable 2FA for the authenticated user.
     * Requires password confirmation to prevent accidental or unauthorized disabling.
     *
     * @param  Request $request  password (current account password for confirmation)
     * @return JsonResponse
     */
    public function twoFactorDisable(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = $request->user();

        if (!\Illuminate\Support\Facades\Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password confirmation failed. 2FA was not disabled.'
            ], 422);
        }

        // Clear all 2FA fields from the user record
        $user->forceFill([
            'two_factor_secret'       => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return response()->json([
            'success' => true,
            'message' => 'Two-factor authentication has been disabled.'
        ]);
    }

    /**
     * Request a password reset token.
     */
    public function forgotPassword(Request $request, ForgotPasswordAction $action): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        if (\App\Services\ReCaptchaService::isEnabled()) {
            $token = $request->input('g-recaptcha-response') ?? $request->input('recaptcha_token');
            if (!\App\Services\ReCaptchaService::verify($token, $request->ip())) {
                return response()->json([
                    'success' => false,
                    'message' => 'reCAPTCHA verification failed. Please complete the captcha challenge.'
                ], 422);
            }
        }

        $token = $action->execute($request->input('email'));

        return response()->json([
            'success' => true,
            'message' => 'Password reset token generated successfully.',
            'data' => [
                'token' => $token
            ]
        ]);
    }

    /**
     * Reset the user password.
     */
    public function resetPassword(Request $request, ResetPasswordAction $action): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (\App\Services\ReCaptchaService::isEnabled()) {
            $token = $request->input('g-recaptcha-response') ?? $request->input('recaptcha_token');
            if (!\App\Services\ReCaptchaService::verify($token, $request->ip())) {
                return response()->json([
                    'success' => false,
                    'message' => 'reCAPTCHA verification failed. Please complete the captcha challenge.'
                ], 422);
            }
        }

        $action->execute(
            email: $request->input('email'),
            token: $request->input('token'),
            password: $request->input('password')
        );

        return response()->json([
            'success' => true,
            'message' => 'Your password has been reset successfully.'
        ]);
    }
    /**
     * Change the authenticated user's password.
     *
     * @param  Request $request  current_password, password, password_confirmation
     * @return JsonResponse
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password'      => ['required', 'string'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ]);

        $user = $request->user();

        // Verify the current password before allowing a change
        if (!\Illuminate\Support\Facades\Hash::check($request->input('current_password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.'
            ], 422);
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->input('password')),
        ]);

        // Revoke all tokens and force re-login for security
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully. Please log in again.'
        ]);
    }

    /**
     * Return the authenticated user's profile data.
     *
     * @param  Request $request
     * @return JsonResponse  UserResource of the currently authenticated user
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new UserResource($request->user()->load('roles'))
        ]);
    }
}
