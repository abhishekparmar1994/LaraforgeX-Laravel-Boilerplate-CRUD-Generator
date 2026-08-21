<?php

declare(strict_types=1);

namespace App\Domains\Settings\Repositories\Contracts;

use App\Shared\Contracts\RepositoryInterface;

interface SettingsRepositoryInterface extends RepositoryInterface
{
    /**
     * Get a setting value by key.
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Set a setting value by key.
     */
    public function set(string $key, mixed $value, string $group = 'general', bool $encrypt = false): void;

    /**
     * Get all settings grouped by group name.
     */
    public function getByGroup(string $group): array;
}