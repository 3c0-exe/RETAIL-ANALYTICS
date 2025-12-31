<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::withCount('users', 'permissions');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('display_name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $roles = $query->orderBy('is_system', 'desc')
                       ->orderBy('name')
                       ->paginate(15);

        ActivityLog::log('viewed_roles');

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('module')->orderBy('action')->get();
        $modules = Permission::getModules();
        $actions = Permission::getActions();

        return view('admin.roles.create', compact('permissions', 'modules', 'actions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles|alpha_dash',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'is_system' => false,
        ]);

        if (!empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        ActivityLog::log('created_role', Role::class, $role->id, [
            'name' => $role->display_name,
            'permissions_count' => count($validated['permissions'] ?? [])
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role created successfully!');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('module')->orderBy('action')->get();
        $modules = Permission::getModules();
        $actions = Permission::getActions();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'modules', 'actions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        // Prevent editing system roles' core attributes
        if ($role->is_system) {
            // Only allow permission changes for system roles
            $validated = $request->validate([
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,id',
            ]);

            $role->permissions()->sync($validated['permissions'] ?? []);

            ActivityLog::log('updated_role_permissions', Role::class, $role->id, [
                'name' => $role->display_name,
                'permissions_count' => count($validated['permissions'] ?? [])
            ]);

            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Role permissions updated successfully!');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('roles')->ignore($role->id)],
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        ActivityLog::log('updated_role', Role::class, $role->id, ['name' => $role->display_name]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role updated successfully!');
    }

    public function destroy(Role $role)
    {
        // Prevent deleting system roles
        if ($role->is_system) {
            return back()->with('error', 'Cannot delete system roles!');
        }

        // Prevent deleting roles with users assigned
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role with assigned users. Reassign users first.');
        }

        $roleName = $role->display_name;
        $roleId = $role->id;

        $role->permissions()->detach();
        $role->delete();

        ActivityLog::log('deleted_role', Role::class, $roleId, ['name' => $roleName]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role deleted successfully!');
    }
}
