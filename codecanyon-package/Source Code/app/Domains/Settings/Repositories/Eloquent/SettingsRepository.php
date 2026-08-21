<?php

declare(strict_types=1);

namespace App\Domains\Settings\Repositories\Eloquent;

use App\Domains\Settings\Models\Settings;
use App\Domains\Settings\Repositories\Contracts\SettingsRepositoryInterface;
use App\Shared\Services\BaseRepository;
use Illuminate\Support\Facades\Cache;

class SettingsRepository extends BaseRepository implements SettingsRepositoryInterface
{
    protected function model(): string
    {
        return Settings::class;
    }

    /**
     * Get a setting value by key. Caches the result forever.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("settings.{$key}", function () use ($key, $default) {
            /** @var Settings|null $setting */
            $setting = $this->model->newQuery()->where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value by key. Invalidates the cache.
     */
    public function set(string $key, mixed $value, string $group = 'general', bool $encrypt = false): void
    {
        $this->model->newQuery()->updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'is_encrypted' => $encrypt,
            ]
        );

        Cache::forget("settings.{$key}");
        Cache::forget("settings.group.{$group}");
    }

    /**
     * Get all settings grouped by group name.
     */
    public function getByGroup(string $group): array
    {
        return Cache::rememberForever("settings.group.{$group}", function () use ($group) {
            $settings = $this->model->newQuery()->where('group', $group)->get();
            $result = [];

            foreach ($settings as $setting) {
                $result[$setting->key] = $setting->value;
            }

            return $result;
        });
    }
}