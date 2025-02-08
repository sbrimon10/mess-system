<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        // Define permissions
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'food-schedule-list',
            'food-schedule-create',
            'food-schedule-edit',
            'food-schedule-delete',
            'food-preference-list',
            'food-preference-create',
            'food-preference-edit',
            'food-preference-delete',
            'deposit-list',
            'deposit-create',
            'deposit-edit',
            'deposit-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $superAdminRole = Role::create(['name' => 'super-admin']);
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Assign all permissions to super admin
        $superAdminRole->givePermissionTo(Permission::all());

        // Assign a subset of permissions to admin
        $adminRole->givePermissionTo([
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'food-schedule-list',
            'food-schedule-create',
            'food-schedule-edit',
            'food-schedule-delete',
            'food-preference-list',
            'food-preference-create',
            'food-preference-edit',
            'food-preference-delete',
            'deposit-list',
            'deposit-create',
            'deposit-edit',
            'deposit-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
        ]);

        // Assign only user-related permissions to the user
        $userRole->givePermissionTo([
            'food-schedule-list',
            'food-preference-list',
            'deposit-list',
            'user-list',
        ]);
    }
}
