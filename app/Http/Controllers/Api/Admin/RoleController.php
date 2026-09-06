<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(private ActivityLogService $logger) {}

    public function index(): JsonResponse
    {
        return response()->json(Role::with('permissions')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'role_name' => ['required', 'string', 'unique:roles,role_name'],
        ]);

        $role = Role::create($data);
        $this->logger->log($request->user()->id, 'created', 'roles', $role->id);

        return response()->json(['message' => 'Role created.', 'role' => $role], 201);
    }

    public function show(Role $role): JsonResponse
    {
        return response()->json($role->load('permissions'));
    }

    public function update(Request $request, Role $role): JsonResponse
    {
        $data = $request->validate([
            'role_name' => ['required', 'string', 'unique:roles,role_name,'.$role->id],
        ]);

        $role->update($data);
        $this->logger->log($request->user()->id, 'updated', 'roles', $role->id);

        return response()->json(['message' => 'Role updated.', 'role' => $role]);
    }

    public function destroy(Request $request, Role $role): JsonResponse
    {
        $this->logger->log($request->user()->id, 'deleted', 'roles', $role->id, "Deleted: {$role->role_name}");
        $role->delete();

        return response()->json(['message' => 'Role deleted.']);
    }

    public function assignPermission(Request $request, Role $role): JsonResponse
    {
        $data = $request->validate([
            'permission_id' => ['required', 'integer', 'exists:permissions,id'],
        ]);

        $role->permissions()->syncWithoutDetaching([$data['permission_id']]);
        $this->logger->log($request->user()->id, 'assigned_permission', 'role_permission', $role->id);

        return response()->json(['message' => 'Permission assigned.', 'permissions' => $role->fresh('permissions')->permissions]);
    }

    public function revokePermission(Request $request, Role $role, Permission $permission): JsonResponse
    {
        $role->permissions()->detach($permission->id);
        $this->logger->log($request->user()->id, 'revoked_permission', 'role_permission', $role->id);

        return response()->json(['message' => 'Permission revoked.']);
    }

    // ── Permissions master list ───────────────────────────────────────────────

    public function permissions(): JsonResponse
    {
        return response()->json(Permission::all());
    }

    public function storePermission(Request $request): JsonResponse
    {
        $data = $request->validate([
            'permission_name' => ['required', 'string', 'unique:permissions,permission_name'],
        ]);

        $perm = Permission::create($data);
        $this->logger->log($request->user()->id, 'created', 'permissions', $perm->id);

        return response()->json(['message' => 'Permission created.', 'permission' => $perm], 201);
    }
}
