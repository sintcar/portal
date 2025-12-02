<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = Role::query()->with('permissions')->get();

        return response()->json($roles);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string|unique:roles,slug',
            'description' => 'nullable|string',
            'is_default' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $role = Role::query()->create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'is_default' => $data['is_default'] ?? false,
        ]);

        foreach ($data['permissions'] ?? [] as $permission) {
            RolePermission::query()->create([
                'role_id' => $role->id,
                'permission' => $permission,
                'is_allowed' => true,
            ]);
        }

        return response()->json($role->load('permissions'), 201);
    }

    public function updatePermissions(Request $request, Role $role): JsonResponse
    {
        $data = $request->validate([
            'permissions' => 'required|array',
            'permissions.*.permission' => 'required|string',
            'permissions.*.is_allowed' => 'boolean',
        ]);

        $role->permissions()->delete();

        foreach ($data['permissions'] as $permissionData) {
            RolePermission::query()->create([
                'role_id' => $role->id,
                'permission' => $permissionData['permission'],
                'is_allowed' => $permissionData['is_allowed'] ?? true,
            ]);
        }

        return response()->json($role->load('permissions'));
    }

    public function assignUser(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        $user->update(['role_id' => $data['role_id']]);

        return response()->json($user->load('role.permissions'));
    }
}
