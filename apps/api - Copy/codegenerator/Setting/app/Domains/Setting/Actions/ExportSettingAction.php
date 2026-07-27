<?php

declare(strict_types=1);

namespace App\Domains\Setting\Actions;

use App\Domains\Setting\Models\Setting;

class ExportSettingAction
{
    public function execute(string $format = 'csv'): array
    {
        $records = Setting::latest()->get();
        return $records->toArray();
    }
}