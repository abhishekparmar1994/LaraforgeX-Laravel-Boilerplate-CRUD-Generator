<?php

declare(strict_types=1);

namespace App\Domains\Setting\Repositories\Eloquent;

use App\Domains\Setting\Models\Setting;
use App\Domains\Setting\Repositories\Contracts\SettingRepositoryInterface;
use App\Shared\Services\BaseRepository;

class SettingRepository extends BaseRepository implements SettingRepositoryInterface
{
    protected function model(): string
    {
        return Setting::class;
    }
}