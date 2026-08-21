<?php

declare(strict_types=1);

namespace App\Shared\Traits;

use App\Domains\AuditLog\Jobs\WriteAuditLogJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait HasAuditTrail
{
    /**
     * Boot the trait to capture changes automatically.
     */
    protected static function bootHasAuditTrail(): void
    {
        static::created(function (Model $model) {
            static::auditEvent($model, 'created', null, $model->getAttributes());
        });

        static::updated(function (Model $model) {
            $oldValues = array_intersect_key($model->getOriginal(), $model->getChanges());
            $newValues = $model->getChanges();
            
            // Remove password changes from plain-text audit logs for security
            unset($oldValues['password'], $newValues['password']);

            if (empty($newValues)) {
                return;
            }

            static::auditEvent($model, 'updated', $oldValues, $newValues);
        });

        static::deleted(function (Model $model) {
            static::auditEvent($model, 'deleted', $model->getOriginal(), null);
        });
    }

    /**
     * Dispatch the audit trail write job.
     */
    protected static function auditEvent(Model $model, string $event, ?array $oldValues, ?array $newValues): void
    {
        $request = request();
        $userAgent = $request->header('User-Agent', '');

        // Standard regex parsers for simple OS/Browser detection
        $browser = 'Unknown';
        if (preg_match('/MSIE/i', $userAgent) && !preg_match('/Opera/i', $userAgent)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Opera/i', $userAgent)) {
            $browser = 'Opera';
        }

        $os = 'Unknown';
        if (preg_match('/windows|win32/i', $userAgent)) {
            $os = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $os = 'Mac OS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $os = 'Linux';
        } elseif (preg_match('/iphone|ipad/i', $userAgent)) {
            $os = 'iOS';
        } elseif (preg_match('/android/i', $userAgent)) {
            $os = 'Android';
        }

        $device = preg_match('/mobile|phone|tablet|android|iphone/i', $userAgent) ? 'Mobile' : 'Desktop';

        $auditData = [
            'user_id' => Auth::id(),
            'event' => $event,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request->ip(),
            'user_agent' => substr($userAgent, 0, 500),
            'browser' => $browser,
            'device' => $device,
            'os' => $os,
        ];

        // Dispatch background job to prevent blocking database traffic
        dispatch(new WriteAuditLogJob($auditData));
    }
}
