<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $this->authorize('view roles');

        $roles = Role::with('permissions')
            ->withCount('users')
            ->get();

        return Inertia::render('admin/Roles/Index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create roles');

        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode(' ', $permission->name)[0]; // Group by first word (e.g., 'user', 'post')
        });

        return Inertia::render('Admin/Roles/Create', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create roles');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        DB::transaction(function () use ($validated) {
            $role = Role::create(['name' => $validated['name']]);
            $role->syncPermissions($validated['permissions']);
        });

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $this->authorize('view roles');

        $role->load('permissions', 'users');

        return Inertia::render('Admin/Roles/Show', [
            'role' => $role,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $this->authorize('edit roles');

        // Prevent editing of super admin role (optional)
        if ($role->name === 'super-admin') {
            abort(403, 'Cannot edit super admin role.');
        }

        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode(' ', $permission->name)[0];
        });

        $role->load('permissions');

        return Inertia::render('Admin/Roles/Edit', [
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name'),
            ],
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $this->authorize('edit roles');

        // Prevent updating super admin role (optional)
        if ($role->name === 'super-admin') {
            abort(403, 'Cannot update super admin role.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        DB::transaction(function () use ($role, $validated) {
            $role->update(['name' => $validated['name']]);
            $role->syncPermissions($validated['permissions']);
        });

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete roles');

        // Prevent deleting super admin role (optional)
        if ($role->name === 'super-admin') {
            abort(403, 'Cannot delete super admin role.');
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete role that has users assigned.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Bulk delete roles
     */
    public function bulkDestroy(Request $request)
    {
        $this->authorize('delete roles');

        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:roles,id',
        ]);

        $roles = Role::whereIn('id', $validated['ids'])
            ->where('name', '!=', 'super-admin') // Prevent deleting super admin
            ->get();

        // Check if any roles have users
        $rolesWithUsers = $roles->filter(function ($role) {
            return $role->users()->count() > 0;
        });

        if ($rolesWithUsers->count() > 0) {
            return response()->json([
                'error' => 'Some roles have users assigned and cannot be deleted.',
                'roles_with_users' => $rolesWithUsers->pluck('name')
            ], 422);
        }

        Role::whereIn('id', $validated['ids'])
            ->where('name', '!=', 'super-admin')
            ->delete();

        return response()->json(['message' => 'Selected roles deleted successfully.']);
    }
}