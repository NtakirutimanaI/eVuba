<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Roles
        $roles = ['admin', 'manager', 'employee', 'customer'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // 2. Create Permissions (example)
        $permissions = [
            'view_dashboard',
            'manage_users',
            'view_tasks',
            'manage_inventory',
            'view_reports',
            'view_appointments',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // 3. Assign permissions to roles
        Role::findByName('admin')->syncPermissions(Permission::all());
        Role::findByName('manager')->syncPermissions([
            'view_dashboard', 'view_tasks', 'view_reports', 'view_appointments'
        ]);
        Role::findByName('employee')->syncPermissions([
            'view_dashboard', 'view_tasks', 'view_appointments'
        ]);
        Role::findByName('customer')->syncPermissions([
            'view_dashboard', 'view_appointments'
        ]);
    }
}
