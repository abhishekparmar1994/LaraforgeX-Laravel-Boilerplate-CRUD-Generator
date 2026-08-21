<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Domains\User\Actions\ActivateUserAction;
use App\Domains\User\Actions\CreateUserAction;
use App\Domains\User\Actions\DeactivateUserAction;
use App\Domains\User\Actions\DeleteUserAction;
use App\Domains\User\Actions\RestoreUserAction;
use App\Domains\User\Actions\SuspendUserAction;
use App\Domains\User\Actions\UpdateUserAction;
use App\Domains\User\DTOs\CreateUserDTO;
use App\Domains\User\DTOs\UpdateUserDTO;
use App\Domains\User\Requests\CreateUserRequest;
use App\Domains\User\Requests\UpdateUserRequest;
use App\Domains\User\Resources\UserResource;
use App\Domains\User\Repositories\Contracts\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $repository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', \App\Domains\User\Models\User::class);

        $filters = $request->only(['search', 'status', 'role']);
        $users = $this->repository->searchAndPaginate($filters, (int) $request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => UserResource::collection($users),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request, CreateUserAction $action): JsonResponse
    {
        Gate::authorize('create', \App\Domains\User\Models\User::class);

        // Prevent assigning the 'administrator' role if the operator is not an administrator
        $roles = $request->input('roles', []);
        if (in_array('administrator', $roles) && !auth()->user()->hasRole('administrator')) {
            return response()->json([
                'success' => false,
                'message' => 'Only administrators can assign the administrator role.'
            ], 403);
        }

        $dto = new CreateUserDTO(
            name: $request->input('name'),
            email: $request->input('email'),
            password: $request->input('password'),
            status: $request->input('status', 'active'),
            roles: $roles
        );

        $user = $action->execute($dto);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'data' => new UserResource($user)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $user = $this->repository->findOrFail($id);
        Gate::authorize('view', $user);

        return response()->json([
            'success' => true,
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id, UpdateUserAction $action): JsonResponse
    {
        $user = $this->repository->findOrFail($id);
        Gate::authorize('update', $user);

        $roles = $request->input('roles', []);
        
        // Prevent non-admin user from adding or removing the 'administrator' role
        if (!auth()->user()->hasRole('administrator')) {
            $hasAdminRole = $user->hasRole('administrator');
            $wantsAdminRole = in_array('administrator', $roles);
            if ($hasAdminRole !== $wantsAdminRole) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only administrators can modify the administrator role assignment.'
                ], 403);
            }
        }

        $dto = new UpdateUserDTO(
            name: $request->input('name'),
            email: $request->input('email'),
            password: $request->input('password'),
            status: $request->input('status'),
            roles: $roles
        );

        $updatedUser = $action->execute($id, $dto);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'data' => new UserResource($updatedUser)
        ]);
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(string $id, DeleteUserAction $action): JsonResponse
    {
        $user = $this->repository->findOrFail($id);
        Gate::authorize('delete', $user);

        $action->execute($id);

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.'
        ]);
    }

    /**
     * Restore the specified soft-deleted resource.
     */
    public function restore(string $id, RestoreUserAction $action): JsonResponse
    {
        $user = $this->repository->findOrFail($id);
        Gate::authorize('suspend', $user);

        $restored = $action->execute($id);

        if (!$restored) {
            return response()->json([
                'success' => false,
                'message' => 'User could not be restored.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'User restored successfully.'
        ]);
    }

    /**
     * Suspend the user account.
     */
    public function suspend(string $id, SuspendUserAction $action): JsonResponse
    {
        $user = $this->repository->findOrFail($id);
        Gate::authorize('suspend', $user);

        $action->execute($id);

        return response()->json([
            'success' => true,
            'message' => 'User account suspended successfully.'
        ]);
    }

    /**
     * Activate the user account.
     */
    public function activate(string $id, ActivateUserAction $action): JsonResponse
    {
        $user = $this->repository->findOrFail($id);
        Gate::authorize('suspend', $user);

        $action->execute($id);

        return response()->json([
            'success' => true,
            'message' => 'User account activated successfully.'
        ]);
    }

    /**
     * Deactivate the user account.
     */
    public function deactivate(string $id, DeactivateUserAction $action): JsonResponse
    {
        $user = $this->repository->findOrFail($id);
        Gate::authorize('suspend', $user);

        $action->execute($id);

        return response()->json([
            'success' => true,
            'message' => 'User account deactivated successfully.'
        ]);
    }
}
