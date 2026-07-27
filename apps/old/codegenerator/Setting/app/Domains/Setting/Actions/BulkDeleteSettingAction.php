<?php

declare(strict_types=1);

namespace App\Domains\Setting\Actions;

use App\Domains\Setting\Models\Setting;

class BulkDeleteSettingAction
{
    public function execute(array $ids): int
    {
        return Setting::whereIn('id', $ids)->delete();
    }
}