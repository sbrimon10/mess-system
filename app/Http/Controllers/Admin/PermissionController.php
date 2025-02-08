<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        // Transform the name to slug format
    $slug = Str::slug($request->input('name'));

    // Apply validation with the slug
    $validated = $request->validate([
        'name' => [
            'required',
            function ($attribute, $value, $fail) use ($slug) {
                // Check if the slug already exists in the database
                $existingSlug = DB::table('permissions')->where('name', $slug)->exists();

                if ($existingSlug) {
                    $fail('The name is already taken.');
                }
            },
        ],
        'guard_name' => 'nullable|default:web',
    ]);

        Permission::create([
            'name' => $slug,
            'guard_name' => $request->input('guard_name')??'web',
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully');
    }
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }
    public function update(Request $request, Permission $permission){
        $request->validate([
            'name' => 'required',
            'guard_name' => 'required',
        ]);
        $permission->update($request->all());
        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');   
    }
    public function destroy(Permission $permission){
        if($permission->delete()){
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully');
    }
    }
}
