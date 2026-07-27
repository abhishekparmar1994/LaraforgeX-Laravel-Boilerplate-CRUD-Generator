<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Domains\User\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * PermissionController
 *
 * Manages the direct CRUD lifecycle for Spatie permissions.
 * Permissions are decoupled from roles — they are assigned to roles
 * via the RoleController::syncPermissions flow.
 *
 * Dependencies: Spatie\Permission\Models\Permission (via custom Permission model)
 */
class PermissionController extends Controller
{
    /**
     * List all system permissions.
     *
     * @return JsonResponse  Array of all permission records
     */
    public function index(): JsonResponse
    {
        Gate::authorize('admin-only');

        $permissions = Permission::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }

    /**
     * Create a new permission.
     *
     * @param  Request $request  Must contain: name (string), guard_name (string, optional)
     * @return JsonResponse      The newly created permission record
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('admin-only');

        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:permissions,name'],
            'guard_name' => ['nullable', 'string', 'in:web,api'],
        ]);

        $permission = Permission::create([
            'name' => $validated['name'],
            'guard_name' => $validated['guard_name'] ?? 'web',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permission created successfully.',
            'data' => $permission
        ], 201);
    }

    /**
     * Update an existing permission's guard name.
     * The permission name itself is immutable once created to avoid breaking role assignments.
     *
     * @param  string  $id       UUID of the permission record
     * @param  Request $request  Must contain: guard_name (string)
     * @return JsonResponse      Updated permission record
     */
    public function update(string $id, Request $request): JsonResponse
    {
        Gate::authorize('admin-only');

        $permission = Permission::findOrFail($id);

        $validated = $request->validate([
            'guard_name' => ['required', 'string', 'in:web,api'],
        ]);

        $permission->update(['guard_name' => $validated['guard_name']]);

        return response()->json([
            'success' => true,
            'message' => 'Permission updated successfully.',
            'data' => $permission->fresh()
        ]);
    }

    /**
     * Delete a permission and detach it from all roles.
     *
     * @param  string $id  UUID of the permission to delete
     * @return JsonResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function destroy(string $id): JsonResponse
    {
        Gate::authorize('admin-only');

        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Permission deleted and detached from all roles.'
        ]);
    }
}
