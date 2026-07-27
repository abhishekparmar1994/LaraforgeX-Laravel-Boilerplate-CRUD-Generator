<?php

declare(strict_types=1);

namespace App\Domains\Setting\Observers;

use App\Domains\Setting\Models\Setting;

class SettingObserver
{
    public function created(Setting $record): void {}
    public function updated(Setting $record): void {}
    public function deleted(Setting $record): void {}
}