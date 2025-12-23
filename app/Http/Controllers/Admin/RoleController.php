<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class RoleController extends Controller
{
    /**
     * Display the comprehensive roles & permissions dashboard.
     */
    public function index(Request $request)
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        $users = User::with('roles')->orderBy('name', 'asc')->paginate(15);

        // Group permissions by "target" (the word after action like 'view', 'create')
        $groupedPermissions = $permissions->groupBy(function($perm) {
            $name = str_replace('-', ' ', $perm->name); // normalize to spaces
            $parts = explode(' ', $name);
            // If it's "view users", the group is "users". 
            // If it's just "manage", group is "general".
            return count($parts) > 1 ? end($parts) : 'general';
        });

        return view('admin.roles.index', compact('roles', 'permissions', 'groupedPermissions', 'users'));
    }

    /**
     * Create or update a role.
     */
    public function storeRole(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles,name,' . ($request->id ?? 'NULL')]);
        
        Role::updateOrCreate(['id' => $request->id], ['name' => $request->name, 'guard_name' => 'web']);
        
        return back()->with('success', 'Role ' . ($request->id ? 'updated' : 'created') . ' successfully.');
    }

    /**
     * Delete a role.
     */
    public function destroyRole($id)
    {
        $role = Role::findOrFail($id);
        if (in_array($role->name, ['admin', 'manager'])) {
            return back()->with('error', 'Cannot delete system-protected roles.');
        }
        $role->delete();
        return back()->with('success', 'Role deleted successfully.');
    }

    /**
     * Create or update a permission.
     */
    public function storePermission(Request $request)
    {
        $request->validate(['name' => 'required|unique:permissions,name,' . ($request->id ?? 'NULL')]);
        
        Permission::updateOrCreate(['id' => $request->id], ['name' => $request->name, 'guard_name' => 'web']);
        
        return back()->with('success', 'Permission ' . ($request->id ? 'updated' : 'created') . ' successfully.');
    }

    /**
     * Delete a permission.
     */
    public function destroyPermission($id)
    {
        Permission::findOrFail($id)->delete();
        return back()->with('success', 'Permission deleted successfully.');
    }

    /**
     * AJAX Toggle role-permission mapping.
     */
    public function togglePermission(Request $request)
    {
        $role = Role::findOrFail($request->role_id);
        $permission = Permission::findOrFail($request->permission_id);

        if ($role->hasPermissionTo($permission->name)) {
            $role->revokePermissionTo($permission->name);
            $status = 'revoked';
        } else {
            $role->givePermissionTo($permission->name);
            $status = 'granted';
        }

        return response()->json([
            'success' => true,
            'message' => 'Permission ' . $status . ' for ' . $role->name,
            'status' => $status
        ]);
    }

    /**
     * AJAX Update user role assignment.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Spatie syncRoles accepts an array of names or IDs
        $user->syncRoles($request->role);
        
        // Update the legacy 'role' column for compatibility if it exists
        if (is_array($request->role)) {
            $user->role = $request->role[0] ?? null;
        } else {
            $user->role = $request->role;
        }
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User roles updated successfully.',
            'new_roles' => $user->getRoleNames(),
        ]);
    }
}
