<?php

declare(strict_types=1);

namespace App\Domains\Settings\Controllers;

use App\Domains\Settings\Actions\SaveSettingsAction;
use App\Domains\Settings\Repositories\Contracts\SettingsRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct(
        protected SettingsRepositoryInterface $repository
    ) {
    }

    /**
     * Get settings list.
     */
    public function index(Request $request): JsonResponse
    {
        $group = $request->query('group');
        $query = \App\Domains\Settings\Models\Settings::query();

        if ($group) {
            $query->where('group', $group);
        }

        $settings = $query->get()->map(function ($setting) {
            if (!auth('sanctum')->check() && ($setting->is_encrypted || in_array($setting->key, ['recaptcha_secret_key', 'smtp_password', 'mail_password'], true))) {
                $setting->value = '********';
            }
            return $setting;
        });

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }


    /**
     * Update a setting configuration value.
     */
    public function update(string $id, Request $request, SaveSettingsAction $action): JsonResponse
    {
        $request->validate([
            'value' => ['nullable'],
            'group' => ['string'],
            'is_encrypted' => ['boolean'],
        ]);

        $setting = \App\Domains\Settings\Models\Settings::find($id);

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Configuration key not found.'
            ], 404);
        }

        if ($setting->key === 'recaptcha_enabled') {
            $val = $request->input('value');
            $isEnabled = filter_var($val, FILTER_VALIDATE_BOOLEAN) || in_array($val, ['1', 1, 'true'], true);
            if ($isEnabled) {
                $siteKey = trim((string) \App\Domains\Settings\Models\Settings::get('recaptcha_site_key', ''));
                $secretKey = trim((string) \App\Domains\Settings\Models\Settings::get('recaptcha_secret_key', ''));
                if (empty($siteKey) || empty($secretKey)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot enable Google reCAPTCHA v2 without valid Site Key and Secret Key.'
                    ], 422);
                }
            }
        }

        $action->execute(
            key: $setting->key,
            value: $request->input('value'),
            group: $request->input('group', $setting->group),
            encrypt: (bool) $request->input('is_encrypted', $setting->is_encrypted)
        );

        return response()->json([
            'success' => true,
            'message' => 'Setting updated successfully.',
            'data' => $setting->fresh()
        ]);
    }

    /**
     * Save dynamic settings.
     */
    public function store(Request $request, SaveSettingsAction $action): JsonResponse
    {
        $request->validate([
            'settings' => ['required', 'array'],
            'settings.*.key' => ['required', 'string'],
            'settings.*.value' => ['nullable'],
            'settings.*.group' => ['string'],
            'settings.*.is_encrypted' => ['boolean'],
        ]);

        $settings = $request->input('settings');
        $settingsByKey = collect($settings)->keyBy('key');

        // Validation: Require non-empty Site Key and Secret Key when submitting reCAPTCHA config
        if ($settingsByKey->has('recaptcha_site_key') || $settingsByKey->has('recaptcha_secret_key') || $settingsByKey->has('recaptcha_enabled')) {
            $siteKey = $settingsByKey->has('recaptcha_site_key')
                ? trim((string) ($settingsByKey->get('recaptcha_site_key')['value'] ?? ''))
                : trim((string) \App\Domains\Settings\Models\Settings::get('recaptcha_site_key', ''));

            $secretKey = $settingsByKey->has('recaptcha_secret_key')
                ? trim((string) ($settingsByKey->get('recaptcha_secret_key')['value'] ?? ''))
                : trim((string) \App\Domains\Settings\Models\Settings::get('recaptcha_secret_key', ''));

            if (empty($siteKey) || empty($secretKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please enter both reCAPTCHA v2 Site Key and Secret Key before saving.'
                ], 422);
            }
        }

        foreach ($settings as $setting) {
            $action->execute(
                key: $setting['key'],
                value: $setting['value'] ?? null,
                group: $setting['group'] ?? 'general',
                encrypt: (bool) ($setting['is_encrypted'] ?? false)
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings saved successfully.'
        ]);
    }
}
