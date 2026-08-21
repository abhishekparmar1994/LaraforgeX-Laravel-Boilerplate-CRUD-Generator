<?php

declare(strict_types=1);

namespace App\Services;

use App\Domains\Settings\Models\Settings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReCaptchaService
{
    /**
     * Check if reCAPTCHA v2 is enabled.
     */
    public static function isEnabled(): bool
    {
        $enabled = Settings::get('recaptcha_enabled', false);
        return filter_var($enabled, FILTER_VALIDATE_BOOLEAN) || $enabled === '1' || $enabled === 1 || $enabled === 'true';
    }

    /**
     * Get the configured public site key.
     */
    public static function getSiteKey(): string
    {
        return (string) Settings::get('recaptcha_site_key', '');
    }

    /**
     * Get the configured secret key.
     */
    public static function getSecretKey(): string
    {
        return (string) Settings::get('recaptcha_secret_key', '');
    }

    /**
     * Verify the g-recaptcha-response token against Google's API.
     */
    public static function verify(?string $token, ?string $clientIp = null): bool
    {
        if (!self::isEnabled()) {
            return true;
        }

        if (empty($token)) {
            return false;
        }

        $secret = self::getSecretKey();

        if (empty($secret)) {
            Log::warning('reCAPTCHA is enabled but recaptcha_secret_key is unconfigured.');
            return true;
        }

        try {
            $response = Http::asForm()->timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $clientIp,
            ]);

            if ($response->successful()) {
                $body = $response->json();
                return (bool) ($body['success'] ?? false);
            }
        } catch (\Throwable $e) {
            Log::error('reCAPTCHA Verification Error: ' . $e->getMessage());
        }

        return false;
    }
}
