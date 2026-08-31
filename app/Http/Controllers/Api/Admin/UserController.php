<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(private ActivityLogService $logger) {}

    public function index(Request $request): JsonResponse
    {
        $users = User::with('roles')
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"))
            ->paginate(15);

        return response()->json($users);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role_ids' => ['sometimes', 'array'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (! empty($data['role_ids'])) {
            $user->roles()->sync($data['role_ids']);
        }

        $this->logger->log($request->user()->id, 'created', 'users', $user->id);

        return response()->json(['message' => 'User created.', 'user' => $user->load('roles')], 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($user->load('roles.permissions'));
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'unique:users,email,'.$user->id],
            'password' => ['sometimes', 'string', 'min:8'],
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        $this->logger->log($request->user()->id, 'updated', 'users', $user->id);

        return response()->json(['message' => 'User updated.', 'user' => $user->fresh('roles')]);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'Cannot delete your own account.', 'code' => 422], 422);
        }

        $this->logger->log($request->user()->id, 'deleted', 'users', $user->id, "Deleted: {$user->email}");
        $user->delete();

        return response()->json(['message' => 'User deleted.']);
    }

    public function assignRole(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'role_id' => ['required', 'integer', 'exists:roles,id'],
        ]);

        $user->roles()->syncWithoutDetaching([$data['role_id']]);
        $this->logger->log($request->user()->id, 'assigned_role', 'role_user', $user->id);

        return response()->json(['message' => 'Role assigned.', 'roles' => $user->fresh('roles')->roles]);
    }

    public function revokeRole(Request $request, User $user, Role $role): JsonResponse
    {
        $user->roles()->detach($role->id);
        $this->logger->log($request->user()->id, 'revoked_role', 'role_user', $user->id);

        return response()->json(['message' => 'Role revoked.', 'roles' => $user->fresh('roles')->roles]);
    }
}
