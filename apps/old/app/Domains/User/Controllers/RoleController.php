<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Domains\User\Models\Role;
use App\Domains\User\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    /**
     * Display a listing of roles and available system permissions.
     */
    public function index(): JsonResponse
    {
        Gate::authorize('admin-only');

        $roles = Role::with('parent', 'permissions')->get();
        $permissions = Permission::all();

        return response()->json([
            'success' => true,
            'data' => [
                'roles' => $roles,
                'permissions' => $permissions
            ]
        ]);
    }

    /**
     * Store a newly created role in the database.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('admin-only');

        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:roles,name'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'uuid', 'exists:roles,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name']
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
            'description' => $validated['description'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully.',
            'data' => $role->load('permissions')
        ]);
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        Gate::authorize('admin-only');

        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:roles,name,' . $role->id],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'uuid', 'exists:roles,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name']
        ]);

        if ($validated['parent_id'] && $validated['parent_id'] === $role->id) {
            return response()->json([
                'success' => false,
                'message' => 'A role cannot be its own parent.'
            ], 422);
        }

        $role->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null
        ]);

        $permissions = $validated['permissions'] ?? [];
        $role->syncPermissions($permissions);

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully.',
            'data' => $role->load('permissions')
        ]);
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        Gate::authorize('admin-only');

        $role = Role::findOrFail($id);
        
        if (in_array($role->name, ['administrator', 'educator', 'student'])) {
            return response()->json([
                'success' => false,
                'message' => 'System default roles cannot be deleted.'
            ], 422);
        }

        Role::where('parent_id', $role->id)->update(['parent_id' => null]);
        
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully.'
        ]);
    }
}
