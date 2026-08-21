<?php

namespace App\Actions;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Action to log an activity in the unified CRM timeline.
 */
class LogActivityAction
{
    /**
     * Execute the activity log.
     *
     * @param Model $subject
     * @param string $type
     * @param string $description
     * @param array|null $properties
     * @param string|null $userId
     * @return Activity
     */
    public function execute(
        Model $subject,
        string $type,
        string $description,
        ?array $properties = null,
        ?string $userId = null
    ): Activity {
        $actorId = $userId ?? (Auth::check() ? Auth::id() : null);

        return Activity::create([
            'user_id' => $actorId,
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
            'type' => $type,
            'description' => $description,
            'properties' => $properties,
        ]);
    }
}
