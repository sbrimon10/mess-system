<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class RolePermissionController extends Controller
{
    public function index(){
        $roles=Role::all();
        return view('admin.rolepermissions.index', compact('roles'));
    }
    public function edit(Role $role)
    {
        // Get all permissions
        $permissions = Permission::all();

        // Get the permissions the role already has
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.rolepermissions.edit', compact('role', 'permissions', 'rolePermissions'));
    }
    public function update(Request $request, Role $role)
    {
        // Validate the request to ensure permissions are an array of permission IDs
        $request->validate([
            'permissions' => 'array|nullable',
            'permissions.*' => 'exists:permissions,id',
        ]);
    
        // Get the permission names based on the IDs submitted in the form
        $permissionNames = Permission::whereIn('id', $request->permissions)->pluck('name');
    
        // Sync permissions with the role by using the permission names
        $role->syncPermissions($permissionNames);
    
        // Redirect back with success message
        return redirect()->route('admin.roles.permissions.edit', $role)->with('success', 'Permissions updated successfully.');
    }
}
