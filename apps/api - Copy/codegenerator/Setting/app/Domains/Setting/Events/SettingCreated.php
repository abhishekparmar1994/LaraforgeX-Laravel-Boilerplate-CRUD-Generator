<?php

declare(strict_types=1);

namespace App\Domains\Setting\Events;

use App\Domains\Setting\Models\Setting;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SettingCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Setting $record
    ) {}
}