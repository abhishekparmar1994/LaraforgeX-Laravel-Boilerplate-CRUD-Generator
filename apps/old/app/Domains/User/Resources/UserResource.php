<?php

declare(strict_types=1);

namespace App\Domains\User\Resources;

use App\Domains\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read User $resource
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'avatar' => $this->resource->avatar,
            'status' => $this->resource->status,
            'last_login_at' => $this->resource->last_login_at?->toIso8601String(),
            'last_login_ip' => $this->resource->last_login_ip,
            'email_verified_at' => $this->resource->email_verified_at?->toIso8601String(),
            'two_factor_enabled' => $this->resource->isTwoFactorEnabled(),
            'roles' => $this->whenLoaded('roles', function () {
                return $this->resource->roles->pluck('name');
            }, function () {
                return $this->resource->getRoleNames();
            }),
            'permissions' => $this->whenLoaded('permissions', function () {
                return $this->resource->permissions->pluck('name');
            }, function () {
                return $this->resource->getPermissionNames();
            }),
            'created_at' => $this->resource->created_at?->toIso8601String(),
            'updated_at' => $this->resource->updated_at?->toIso8601String(),
        ];
    }
}